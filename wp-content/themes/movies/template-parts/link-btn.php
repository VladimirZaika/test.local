<?php
$link = ( isset($args['link']) && !empty($args['link']) ) ? $args['link'] : '';
$class = ( isset($args['custom_class']) && !empty($args['custom_class']) ) ? ' ' . $args['custom_class'] : '';

if ( !empty($link) ):
    $target = $link['target'] ? $link['target']: '_blank';
    $url = $link['url'] ? $link['url']: '';
    $descr = $link['title'] ? $link['title']: ''; ?>

    <a
        class="link<?php echo $class; ?>"
        href="<?php echo esc_url($url); ?>"
        target="<?php echo esc_attr($target); ?>"
        aria-label="<?php echo esc_html($descr); ?>"
    >
    <span><?php echo esc_html($descr); ?></span>
    </a>
<?php endif; ?>