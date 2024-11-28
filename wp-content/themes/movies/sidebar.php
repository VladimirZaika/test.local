<?php
if ( ! is_active_sidebar( 'sidebar-movies' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-movies' ); ?>
</aside>

