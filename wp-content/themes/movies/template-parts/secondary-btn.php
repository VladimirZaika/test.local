<?php
$btn = ( isset($args['label']) && !empty($args['label']) ) ? $args['label'] : '';
$type = ( isset($args['type']) && !empty($args['type']) ) ? $args['type'] : 'button';
$class = ( isset($args['custom_class']) && !empty($args['custom_class']) ) ? ' ' . $args['custom_class'] : '';
$preloader = ( isset($args['preloader']) && !empty($args['preloader']) ) ? $args['preloader'] : false;

if ( !empty($btn) ):
    if ( $preloader ): ?>
        <div class="button-preloader-wrap">
    <?php endif; ?>

        <button class="button button-secondary<?php echo $class; ?>" type="<?php echo $type; ?>">
            <span class="button-text"><?php echo $btn; ?></span>
        </button>

        <?php if ( $preloader ):
            get_template_part( 'template-parts/preloader' );
        endif;

    if ( $preloader ): ?>
        </div>
    <?php endif;
endif; ?>