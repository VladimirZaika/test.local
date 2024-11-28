<?php if (!defined('ABSPATH')) exit;

$requestType = get_option('request_choice');
$placeholderImage = esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/placeholder.png'); ?>

<div class="wrap nbi-wrap">
    <div class="title-wrap">
        <h1><?php _e('NASA\'s Beautiful Images', 'nbi'); ?></h1>
    </div>

    <?php 
    // Hook for content before the main content
    do_action('nasa_before_images_content'); 
    ?>

    <?php if ($requestType !== 'js'): ?>
        <?php if (!empty($nasaImages) && (!isset($nasaImages['error']) && empty($nasaImages['error']))) : ?>
            <div class="nasa-images-gallery" id="nasa-content">
                <?php foreach (array_reverse($nasaImages) as $image) : ?>
                    <article class="nasa-image-item">
                        <h2><?php echo esc_html($image['title'] ?: 'No title'); ?></h2>
                        <img src="<?php echo esc_url($image['url'] ?: $placeholderImage); ?>" alt="<?php echo esc_attr($image['title'] ?: 'No image aviable'); ?>"/>
                        <p><?php echo esc_html($image['explanation'] ?: 'No text'); ?></p>
                        <div class="footer-cart-wrap">
                            <p><strong><?php _e('Date:', 'nbi'); ?></strong> <?php echo esc_html($image['date'] ?: 'No date'); ?></p>
                            <p><strong><?php _e('Author:', 'nbi'); ?></strong> <?php echo esc_html($image['copyright'] ?: 'No author'); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="nasa-images-gallery" id="nasa-content">
                <div class="no-images">
                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/no-request.png'); ?>" alt="No images aviable" />
                </div>
            </div>
        <?php endif;
    else: ?>
        <div class="preloader">
            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/preloader.png'); ?>" alt="Preloader" />
        </div>
        <div class="nasa-images-gallery nasa-images-gallery-js" id="nasa-js-content"></div>
    <?php endif;

    // Hook for content after the main content
    do_action('nasa_after_images_content'); 
    ?>
</div>
