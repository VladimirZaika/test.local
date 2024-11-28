<?php
add_action(
	'widgets_init',
	function () {
		register_sidebar(
			[
				'name'          => __( 'Sidebar', 'movies' ),
				'id'            => 'sidebar-movies',
				'description'   => '',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
	}
);