<?php /**
 * Block Name: Filters Block
*
* This is the template that displays the Filters Block
*/

if( isset( $block['data']['preview_image_help'] )  ) :
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
else:
    $sectionName = 'filters';
    $blockId = wp_unique_id('block-');
    $sectionClass = get_field('filters_class') ? ' '.get_field('filters_class') : '';
    $bgc = get_field('filters_bgc') ? 'background-color: ' . get_field('filters_bgc') . ';' : false;
    $background = $bgc ? 'style="' . $bgc . '"' : false;
    $postsPerPage = get_field('filters_posts_per_page') ?? 6;
    $dataRequest = get_field('filters_data_request') ? 'rest' : 'ajax';
    $currentUrl = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'];

    $customHeading = get_field('filters_heading_custom');
    $title = $customHeading ? get_field('filters_title') : get_the_title();

    $btnApply = get_field('filters_btn_1') ?? __('Apply', 'movies');
    $applyArgs = [
        'label' => $btnApply,
        'type' => 'submit',
        'preloader' => true,
        'custom_class' => 'button-submit',
    ];

    $btnLoadMore = get_field('filters_btn_2') ?? __('Load More', 'movies');
    $loadMoreArgs = [
        'label' => $btnLoadMore,
        'type' => 'button',
        'preloader' => true,
        'custom_class' => 'button-load-more',
    ];

    $btnReset = get_field('filters_btn_3') ?? __('Reset', 'movies');
    $resetArgs = [
        'label' => $btnReset,
        'type' => 'button',
        'preloader' => false,
        'custom_class' => 'button-reset d-none',
    ];

    $years = get_field('movies_date_select', 'options');

    if ( $years && is_array($years) ) {
        usort( $years, function($a, $b) {
            return $b['monie_date'] <=> $a['monie_date'];
        } );
    }

    $getGenre = $_GET['movie_genre'] ?? null;
    $getFrom = $_GET['movie_from'] ?? null;
    $getTo = $_GET['movie_to'] ?? null;
    $getPage = $_GET['movie_page'] ?? null;
    $getSort = $_GET['movie_sort'] ?? null;
    $getSearch = $_GET['movie_search'] ?? '';

    $sortArr = [
        'rating_asc' => __('Rating >', 'movies'),
        'rating_desc' => __('Rating <', 'movies'),
        'date_asc' => __('Date >', 'movies'),
        'date_desc' => __('Date <', 'movies'),
    ];

    if ( !isset($page) || empty($page) ) {
        $getPage = isset($wp_query->query['paged']) ? (int)$wp_query->query['paged'] : 1;
    }

    $args = [
        'type'                     => 'movies',
        'orderby'                  => 'name',
        'order'                    => 'ASC',
        'hide_empty'               => 0,
        'taxonomy'                 => 'genre',
        'parent'                   => 0,
    ];

    $genreCategories = get_categories( $args );

    $params = [
        'movie_genre' => $getGenre,
        'movie_from' => $getFrom,
        'movie_to' => $getTo,
        'movie_sort' => $getSort,
        'movie_page' => $getPage,
        'movie_search' => $getSearch,
        'posts_per_page' => $postsPerPage,
    ];
    
    $response = MovieRequest::handle_request( $params );
    $nonce = MovieRequest::get_nonce() ?? '';
?>

    <section
        class="section-<?php  echo $sectionName; echo $sectionClass; ?>"

        <?php if ( $blockId ): ?>
            id="<?php echo $blockId; ?>"
        <?php endif;

        if ( $background ):
            echo $background;
        endif; ?>
    >
        <div class="container <?php echo $sectionName; ?>-container">
            <?php if ( !empty($title) ):
                $titleSize = get_field('hero_size') ?? 'h2'; ?>

                <div class="title-wrapper">
                    <?php echo '<' . $titleSize . ' class="h1">' . $title . '</' . $titleSize . '>'; ?>
                </div>
            <?php endif; ?>

            <div class="filters-content-wrapper">
                <aside class="filters-wrapper">
                    <form
                        class="filters-form"
                        id="movies-filters-form"
                        action="<?php echo $currentUrl; ?>"
                        method="get"
                        data-request="<?php echo $dataRequest; ?>"
                    >
                        <input
                            class="input input-hidden"
                            id="input-hidden-paged"
                            type="hidden"
                            name="movie_page"
                            value="<?php echo $getPage; ?>"
                        >

                        <input
                            class="input input-hidden"
                            id="input-hidden-per-page"
                            type="hidden"
                            name="posts_per_page"
                            value="<?php echo $postsPerPage; ?>"
                        >

                        <input
                            class="input input-hidden input-hidden-nonce"
                            id="input-hidden-nonce"
                            type="hidden"
                            name="nonce"
                            value="<?php echo $nonce; ?>"
                        >

                        <div class="button-preloader-wrap button-preloader-wrap-search">
                            <div class="search-wrapper">
                                <div class="field search-field">
                                    <input
                                        class="input input-search"
                                        id="input-search"
                                        type="text"
                                        name="movie_search"
                                        placeholder="<?php _e('Search by name', 'movies'); ?>"
                                        value="<?php echo $getSearch; ?>"
                                    >
                                </div>
                            </div>
                            <?php
                                get_template_part( 'template-parts/preloader' );
                                get_template_part( 'template-parts/search', 'icon' );
                            ?>
                        </div>

                        <?php if ( !empty($years) || ( isset($genreCategories) && !empty($genreCategories) ) ): ?>
                            <div class="select-wrapper">
                                <div class="form-title-wrapper">
                                    <h5 class="h5"><?php _e( 'Filter:', 'movies' ); ?></h5>
                                </div>

                                <div class="field-select-wrapper">
                                    <?php if ( isset($genreCategories) && !empty($genreCategories) ): ?>
                                        <div class="field-select">
                                            <label class="field-label" for="movie_genre"><?php _e('Genre', 'movies'); ?></label>
                                            <div class="select-decore-wrapper">
                                                <select
                                                    class="select select-genre"
                                                    name="movie_genre"
                                                    id="movie_genre"
                                                >
                                                    <option value=""><?php _e('Select genre', 'movies'); ?></option>

                                                    <?php foreach ( $genreCategories as $genre ): ?>
                                                        <option value="<?php echo $genre->slug ?>" <?php echo ( $getGenre && ( $getGenre && ( $genre->slug === $getGenre ) ) ) ? 'selected' : '' ?>>
                                                            <?php echo $genre->name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif;

                                    if ( !empty($years) ): ?>
                                        <div class="field-select">
                                            <label class="field-label" for="movie_from"><?php _e('Date from:', 'movies'); ?></label>
                                            <div class="select-decore-wrapper">
                                                <select
                                                    class="select select-from"
                                                    name="movie_from"
                                                    id="movie_from"
                                                >
                                                    <option value=""><?php _e('Select year', 'movies'); ?></option>

                                                    <?php foreach ( $years as $year ):
                                                        $date = $year['monie_date']; ?>

                                                        <option value="<?php echo $date; ?>" <?php echo ( $getFrom && ( $getFrom && ( $date === $getFrom ) ) ) ? 'selected' : '' ?>>
                                                            <?php echo $date; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <label class="field-label" for="movie_to"><?php _e('to', 'movies'); ?></label>
                                            <div class="select-decore-wrapper">
                                                <select
                                                    class="select select-to"
                                                    name="movie_to"
                                                    id="movie_to"
                                                >
                                                    <option value=""><?php _e('Select year', 'movies'); ?></option>

                                                    <?php foreach ( $years as $year ):
                                                        $date = $year['monie_date']; ?>

                                                        <option value="<?php echo $date; ?>" <?php echo ( $getTo && ( $getTo && ( $date === $getTo ) ) ) ? 'selected' : '' ?>>
                                                            <?php echo $date; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif;

                        if ( !empty($btnApply) || !empty($btnReset) ): ?>
                            <div class="btn-wrapper">
                                <?php
                                    get_template_part( 'template-parts/secondary', 'btn', $applyArgs );
                                    get_template_part( 'template-parts/secondary', 'btn', $resetArgs );
                                ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </aside>

                <div class="card-wrapper">
                    <div class="field-sort">
                        <label class="field-label field-label-sort" for="movie_sort"><?php _e('Sort by:', 'movies'); ?></label>
                        <div class="select-decore-wrapper">
                            <select
                                class="select select-sort"
                                name="movie_sort"
                                id="movie_sort"
                                form="movies-filters-form"
                            >
                                <option value=""><?php _e('Select sort', 'movies'); ?></option>

                                <?php foreach ( $sortArr as $key => $val ):
                                    $date = $year['monie_date']; ?>

                                    <option value="<?php echo $key; ?>" <?php echo ( $getSort && ( $getSort && ( $key === $getSort ) ) ) ? 'selected' : '' ?>>
                                        <?php echo $val; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="card-grid">
                        <?php if ( $response['success'] ):
                            foreach ( $response['data']['posts'] as $movie ):
                                get_template_part( 'template-parts/movie', 'card', $movie );
                            endforeach;
                        else:
                            echo '<p>' . esc_html( $response['message'] ) . '</p>';
                        endif; ?>
                    </div>

                    <?php if ( !empty($btnLoadMore) ): ?>
                        <div
                            class="btn-wrapper"
                            id="load-more-wrapper"
                            data-max-pages="<?php echo $response['data']['max_num_pages']; ?>"
                            data-per-page="<?php echo $postsPerPage; ?>"
                        >
                            <?php get_template_part( 'template-parts/secondary', 'btn', $loadMoreArgs ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>