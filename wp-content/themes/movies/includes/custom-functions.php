<?php
require_once get_template_directory() . '/includes/api/imdb.php';
include get_template_directory() . '/includes/api/moviesRequest.php';

/**
 * Replace all macros entries in the string:
 */
function movie_prepare_macros( $str ) {
	return str_replace(
		[
			'((',
			'))',
		],

		[
			'<span class="text-decor">',
			'</span>',
		],
		$str
	);
}

function movie_allow_html_in_title( $title ) {
	$title = movie_prepare_macros( $title );
	return $title;
}

add_filter( 'the_title', 'movie_allow_html_in_title', 10, 2 );
add_filter( 'the_content', 'movie_allow_html_in_title', 10, 2 );

function movie_filter_title_parts( $title ) {
        $title['title'] = str_replace( '((', '', $title['title'] );
        $title['title'] = str_replace( '))', '', $title['title'] );
    return $title;
}

add_filter( 'document_title_parts', 'movie_filter_title_parts' );

/**
 * Custom post type movies icon styles
 */
function custom_movies_icon_css() {
    ?>
		<style>
			#adminmenu .menu-icon-movies div.wp-menu-image img {
				width: 100%;
				height: auto;
				max-width: 24px;
			}
		</style>
    <?php
}

add_action( 'admin_head', 'custom_movies_icon_css' );


/**
 * Complete movies from imdb.dev
 */
function update_movie_data( $post_id, $post, $update ) {
    if ( $post->post_type !== 'movies' ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    $release_date = get_post_meta( $post_id, 'release_date', true );

    if ( $release_date ) {
        update_acf_movies_date_select( $release_date );
    }

    $movie_id = get_field( 'movie_id', $post_id );

    if ( empty( $movie_id ) ) {
        return;
    }

    $imdbAPI = new IMDbAPI();

    try {
        $response = $imdbAPI->queryTitleById( $movie_id );

        if ( empty($response) || !isset($response['data']['title']) ) {
            return;
        }

        $movie = $response['data']['title'];

        global $wpdb;

        if ( isset($movie['primary_title']) ) {
            $new_title = sanitize_text_field( $movie['primary_title'] );
            $new_slug = sanitize_title( $new_title );

            $wpdb->update(
                $wpdb->posts,
                [
                    'post_title' => $new_title,
                    'post_name' => $new_slug,
                ],
                [ 'ID' => $post_id ]
            );
        }

        if ( isset($movie['rating']['aggregate_rating']) ) {
            update_post_meta( $post_id, 'rating', floatval( $movie['rating']['aggregate_rating'] ) );
        }

        if ( isset($movie['start_year']) ) {
            $start_year = intval($movie['start_year']);

            update_post_meta( $post_id, 'release_date', $start_year );

            update_acf_movies_date_select( $start_year );
        }

        if ( isset($movie['plot']) ) {
            $plot = sanitize_text_field( $movie['plot'] );
            $excerpt = wp_trim_words( $plot, 20 );

            $wpdb->update(
                $wpdb->posts,
                [
                    'post_content' => $plot,
                    'post_excerpt' => $excerpt,
                ],
                [ 'ID' => $post_id ]
            );
        }

        if ( isset($movie['posters'][0]['url']) ) {
            $imageUrl = esc_url( $movie['posters'][0]['url'] );
            $attachmentId = upload_image_to_media_library( $imageUrl, $post_id );

            if ( $attachmentId ) {
                set_post_thumbnail( $post_id, $attachmentId );
            }
        }

        if ( isset($movie['genres']) && is_array($movie['genres']) ) {
            $genres = array_map( 'sanitize_text_field', $movie['genres'] );
            $terms = [];

            foreach ( $genres as $genre ) {
                $terms[] = $genre;
            }

            wp_set_object_terms( $post_id, $terms, 'genre' );
        }
    } catch ( Exception $e ) {
        error_log( 'Movie update error: ' . $e->getMessage() );
    }
}

function update_acf_movies_date_select( $year ) {
    if ( !$year ) {
        return;
    }

    $movies_date_select = get_field( 'movies_date_select', 'option' );

    if ( !is_array( $movies_date_select ) ) {
        $movies_date_select = [];
    }

    foreach ( $movies_date_select as $row ) {
        if ( isset( $row['monie_date'] ) && $row['monie_date'] == $year ) {
            return;
        }
    }

    $movies_date_select[] = [ 'monie_date' => $year ];

    update_field( 'movies_date_select', $movies_date_select, 'option' );
}

function upload_image_to_media_library( $imageUrl, $post_id ) {
    $uploadDir = wp_upload_dir();
    $imageData = file_get_contents( $imageUrl );
    if ( !$imageData ) {
        return false;
    }

    $filename = basename( $imageUrl );
    $filePath = $uploadDir['path'] . '/' . $filename;

    file_put_contents( $filePath, $imageData );

    $fileType = wp_check_filetype( $filename, null );
    $attachment = [
        'post_mime_type' => $fileType['type'],
        'post_title' => sanitize_file_name( $filename ),
        'post_content' => '',
        'post_status' => 'inherit',
    ];

    $attachmentId = wp_insert_attachment( $attachment, $filePath, $post_id );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attachmentData = wp_generate_attachment_metadata( $attachmentId, $filePath );
    wp_update_attachment_metadata( $attachmentId, $attachmentData );

    return $attachmentId;
}

add_action('wp_after_insert_post', 'update_movie_data', 10, 3);


/**
 * MOvies request init
 */
add_action('init', ['MovieRequest', 'init']);
