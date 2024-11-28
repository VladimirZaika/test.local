<?php /**
 * Block Name: Hero Block
*
* This is the template that displays the Hero Block
*/

if( isset( $block['data']['preview_image_help'] )  ) :
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
else:

    $sectionName = 'hero';
    $blockId = wp_unique_id('block-');
    $sectionClass = get_field('hero_class') ? ' '.get_field('hero_class') : '';
    $bgc = get_field('hero_bgc') ? 'background-color: ' . get_field('hero_bgc') . ';' : false;
    $background = $bgc ? 'style="' . $bgc . '"' : false;

    $customHeading = get_field('heading_custom');
    $title = $customHeading ? get_field('hero_title') : get_the_title();

    $content = get_field('hero_content');

    $link = get_field('hero_link');
    $linkArgs = [
        'link' => $link,
    ];

    $btn = get_field('hero_btn');
    $btnArgs = [
        'label' => $btn,
        'type' => 'button',
    ];

    $img = get_field('hero_img');

    if ( !empty($content) || !empty($title) || !empty($img) ): ?>
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
                <?php if ( !empty($content) || !empty($title) ): ?>
                    <div class="left-block-desk">
                        <?php if ( !empty($title) ):
                            $titleSize = get_field('hero_size') ?? 'h1'; ?>

                            <div class="title-wrapper">
                            <?php echo '<' . $titleSize . '>' . $title . '</' . $titleSize . '>'; ?>
                            </div>
                        <?php endif;

                        if ( !empty($content) ): ?>
                            <div class="content-wrapper">
                                <p><?php echo $content; ?></p>
                            </div>
                        <?php endif;

                        if ( !empty($link) || !empty($btn) ): ?>
                            <div class="btn-wrapper">
                                <?php
                                    get_template_part( 'template-parts/secondary', 'btn', $btnArgs );
                                    get_template_part( 'template-parts/link', 'btn', $linkArgs );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif;

                if ( !empty($img) ):
                    $showImgTxt = get_field('hero_show_img_txt');
                    $showTotalSubscr = get_field('hero_show_total_subscr');
                    $showTotalRating = get_field('hero_show_total_rating');
                    $imgText = get_field('total_title', 'options');
                    $imgSubscr = get_field('total_subscr', 'options');
                    $imgRating = get_field('total_rating', 'options');
                    $imgRatingArgs = [
                        'rating' => $imgRating,
                    ];?>

                    <div class="right-block-desk" data-position="mob-left">
                        <div class="img-wrapper">
                            <div class="img">
                                <picture>
                                    <source srcset="<?php echo esc_url($img['sizes']['medium']); ?>" media="(max-width: 767px)">
                                    <source srcset="<?php echo esc_url($img['sizes']['large']); ?>" media="(min-width: 768px)">
                                    <img src="<?php echo esc_url($img['sizes']['large']); ?>" alt="<?php echo esc_attr($img['alt']); ?>">
                                </picture>
                            </div>

                            <?php if ( $showImgTxt || $showTotalSubscr || $showTotalRating ): ?>
                                <div class="img-content">
                                    <?php if ( $showImgTxt && !empty($imgText) ): ?>
                                        <div class="total-wrap total-title-wrap">
                                            <span class="total total-title">
                                                <span class="total-text"><?php echo $imgText; ?></span>
                                            </span>
                                        </div>
                                    <?php endif;

                                    if ( $showTotalRating && !empty($imgRating) ):
                                        get_template_part( 'template-parts/rating', 'block', $imgRatingArgs );
                                    endif;

                                    if ( $showTotalSubscr && !empty($imgSubscr) ): ?>
                                        <div class="total-wrap total-subscr-wrap">
                                            <span class="total total-subscr">
                                                <span class="total-text"><?php echo $imgSubscr; ?></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif;
endif; ?>