<?php
$menuArgs = [
    'theme_location'  => 'primary',
    'container'       => 'nav',
    'container_class' => 'menu-wrapper',
    'menu_class'      => 'menu-list',
    'orderby' => 'menu_order',
    'order' => 'ASC',
];

wp_nav_menu( $menuArgs );
?>