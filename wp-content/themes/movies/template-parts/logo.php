<?php
$logo = get_custom_logo();

if ( $logo ): ?>
    <?php echo $logo; ?>
<?php else: ?>
    <p class="header-text">
        <?php bloginfo( 'name' ); ?>
    </p>
<?php endif; ?>