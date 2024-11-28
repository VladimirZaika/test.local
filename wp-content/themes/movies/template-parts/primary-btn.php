<?php
$btn = ( isset($args['label']) && !empty($args['label']) ) ? $args['label'] : '';
$link = ( isset($args['link']) && !empty($args['link']) ) ? $args['link'] : 'button';
$target = ( isset($args['target']) && !empty($args['type']) ) ? $args['target'] : '_self';
$class = ( isset($args['custom_class']) && !empty($args['custom_class']) ) ? ' ' . $args['custom_class'] : '';

if ( !empty($btn) ): ?>
    <a
        class="button button-primary<?php echo $class; ?>"
        href="<?php echo $link; ?>"
        aria-label="<?php echo $btn; ?>"
        target="<?php echo $target; ?>"
    >
        <span><?php echo $btn; ?></span>
    </a>
<?php endif; ?>