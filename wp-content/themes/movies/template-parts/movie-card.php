<?php
    $title = ( isset($args['title']) && !empty($args['title']) ) ? $args['title'] : '';
    $link = ( isset($args['link']) && !empty($args['link']) ) ? $args['link'] : '';
    $rating = ( isset($args['rating']) && !empty($args['rating']) ) ? $args['rating'] : '';
    $id = ( isset($args['id']) && !empty($args['id']) ) ? ' ' . $args['id'] : '';

    $btnArgs = [
        'label' => __('Read more', 'movies'),
        'link' => $link,
        'target' => '_self',
    ];

    $imgRatingArgs = [
        'rating' => $rating,
    ];
?>
<article class="movie-card">
    <div class="img-wrapper">
        <?php if ( has_post_thumbnail($id) ) : ?>
            <picture>
                <source srcset="<?php echo get_the_post_thumbnail_url( $id, 'medium' ); ?>" media="(max-width: 767px)">
                <source srcset="<?php echo get_the_post_thumbnail_url( $id, 'large' ); ?>" media="(min-width: 768px)">
                <img
                    class="movie-card-img"
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
        <?php endif;

        get_template_part( 'template-parts/rating', 'block', $imgRatingArgs ); ?>
    </div>

    <?php if ( !empty($link) || !empty($title) ): ?>
        <div class="movie-content-wrapper">
            <?php if ( !empty($title) ): ?>
                <div class="card-title-wrapper">
                    <h5 class="h5"><?php echo $title; ?></h5>
                </div>
            <?php endif; ?>

            <?php if ( !empty($link) ): ?>
                <div class="card-btn-wrapper">
                    <?php get_template_part( 'template-parts/primary', 'btn', $btnArgs ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</article>