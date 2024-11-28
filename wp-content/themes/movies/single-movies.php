<?php /**
 * Post: Movies
*
* This is the template that displays the single movies post
*/

get_header();
$id = get_the_ID();
$sectionName = 'single-movies';
$blockId = wp_unique_id('block-');

$descrTitle = get_field('movies_description_title', 'options');

$date = get_field('release_date');
$rating = get_field('rating');
$genres = get_the_terms($id, 'genre');
?>
    <section
        class="section-<?php  echo $sectionName; ?>"

        <?php if ( $blockId ): ?>
            id="<?php echo $blockId; ?>"
        <?php endif; ?>
    >
        <div class="container <?php echo $sectionName; ?>-container">
            <div class="title-wrapper">
                <h1 class="h1"><?php the_title(); ?></h1>
            </div>

            <div class="top-content-wrapper">
                <div class="left-content-wrap">
                    <?php if ( has_post_thumbnail($id) ) : ?>
                        <picture>
                            <source srcset="<?php echo get_the_post_thumbnail_url( $id, 'medium' ); ?>" media="(max-width: 767px)">
                            <source srcset="<?php echo get_the_post_thumbnail_url( $id, 'large' ); ?>" media="(min-width: 768px)">
                            <img
                                class="movie-img"
                                src="<?php echo get_the_post_thumbnail_url( $id, 'large' ); ?>"
                                alt="<?php _e('Movie post image', 'movies'); ?>"
                            >
                        </picture>
                    <?php else : ?>
                        <img
                            class="movie-card-img"
                            src="<?php echo get_template_directory_uri() ?>/src/images/placeholder.png"
                            alt="Placeholder Image"
                        />
                    <?php endif; ?>
                </div>

                <div class="right-content-wrap">
                    <ul class="movie-ul">
                        <?php if ( get_the_title() ): ?>
                            <li class="movie-li">
                                <span><strong><?php _e('Tilte: ', 'movies') ?></strong></span>
                                <span><?php the_title(); ?></span>
                            </li>
                        <?php endif;

                        if ( !empty($date) ): ?>
                            <li class="movie-li">
                                <span><strong><?php _e('Date: ', 'movies') ?></strong></span>
                                <span><?php echo $date; ?></span>
                            </li>
                        <?php endif;

                        if ( !empty($rating) ): ?>
                            <li class="movie-li">
                                <span><strong><?php _e('Rating: ', 'movies') ?></strong></span>
                                <span><?php echo $rating; ?></span>
                            </li>
                        <?php endif;

                        if ( !empty($genres) && isset($genres) ): ?>
                            <li class="movie-li">
                                <span><strong><?php _e('Genre: ', 'movies'); ?></strong></span>
                                <span>
                                    <?php 
                                        $genre_names = wp_list_pluck($genres, 'name'); 
                                        echo implode(', ', $genre_names); 
                                    ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="bottom-content-wrapper">
                <?php if ( !empty($descrTitle) ): ?>
                    <div class="title-wrapper">
                        <h2 class="h2"><?php echo $descrTitle; ?></h2>
                    </div>
                <?php endif; ?>

                <div class="movie-descr">
                    <?php get_template_part( 'template-parts/loop' ); ?>
                </div>
            </div>
        </div>
    </section>

<?php get_footer();