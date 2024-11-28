<?php
$rating = ( isset($args['rating']) && !empty($args['rating']) ) ? $args['rating'] : '';

if ( !empty($rating) ): ?>
    <div class="total-wrap total-rating-wrap">
        <span class="total total-rating">
            <span class="total-text"><?php echo $rating; ?></span>
            <?php get_template_part( 'template-parts/rating', 'icon' ); ?>
        </span>
    </div>
<?php endif; ?>