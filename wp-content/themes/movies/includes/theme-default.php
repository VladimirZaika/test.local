<?php
add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'movies', get_theme_file_uri( 'languages' ) );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		add_theme_support(
			'post-formats',
			[
				'aside',
				'image',
				'video',
				'quote',
				'link',
			]
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			[
				'height'      => 200,
				'width'       => 50,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);

		register_nav_menus(
			[
				'primary' => __( 'Primary Menu', 'movies' ),
			]
		);
	}
);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
add_action(
	'after_setup_theme',
	function () {
		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = apply_filters( 'movies_content_width', 1208 );
		}
	},
	0
);

function movie_customize_register($wp_customize) {
    // Primary color
    $wp_customize->add_setting('primary_color', [
        'default'   => '#ff902b',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
        'label'    => __('Primary color', 'movies'),
        'section'  => 'colors',
        'settings' => 'primary_color',
    ]));

    // Secondary color
    $wp_customize->add_setting('secondary_color', [
        'default'   => '#2f2105',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', [
        'label'    => __('Secondary color', 'movies'),
        'section'  => 'colors',
        'settings' => 'secondary_color',
    ]));

    // Text color #1
    $wp_customize->add_setting('text_color', [
        'default'   => '#7e7d7a',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', [
        'label'    => __('Text color #1', 'movies'),
        'section'  => 'colors',
        'settings' => 'text_color',
    ]));

    // Text color #2
    $wp_customize->add_setting('text_color_2', [
        'default'   => '#000',
        'transport' => 'refresh',
    ]);
	
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color_2', [
        'label'    => __('Text color #2', 'movies'),
        'section'  => 'colors',
        'settings' => 'text_color_2',
    ]));

    // Body color
    $wp_customize->add_setting('body_color', [
        'default'   => '#fff',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_color', [
        'label'    => __('Body color', 'movies'),
        'section'  => 'colors',
        'settings' => 'body_color',
    ]));

	// Body color
	$wp_customize->add_setting('body_color', [
		'default'   => '#fff',
		'transport' => 'refresh',
	]);

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_color', [
		'label'    => __('Body color', 'movies'),
		'section'  => 'colors',
		'settings' => 'body_color',
	]));

	// Section color
	$wp_customize->add_setting('section_color', [
		'default'   => '#f6ebda',
		'transport' => 'refresh',
	]);

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'section_color', [
		'label'    => __('Section color', 'movies'),
		'section'  => 'colors',
		'settings' => 'section_color',
	]));
}

add_action('customize_register', 'movie_customize_register');

/**
 * Remove original posts
 *
 */
function remove_default_post_type_menu() {
    remove_menu_page('edit.php');
}

add_action( 'admin_menu', 'remove_default_post_type_menu' );

function disable_post_type_in_rest_api($endpoints) {
    if ( isset($endpoints['/wp/v2/posts']) ) {
        unset($endpoints['/wp/v2/posts']);
    }

    return $endpoints;
}

add_filter( 'rest_endpoints', 'disable_post_type_in_rest_api' );

function exclude_posts_from_admin_queries($query) {
    if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'post') {
        $query->set('post_type', '');
    }
}

add_action('pre_get_posts', 'exclude_posts_from_admin_queries');

function remove_post_metaboxes() {
    if (get_post_type() === 'post') {
        remove_meta_box('tagsdiv-post_tag', 'post', 'side');
        remove_meta_box('categorydiv', 'post', 'side');
        remove_meta_box('postimagediv', 'post', 'side');
    }
}

add_action('add_meta_boxes', 'remove_post_metaboxes', 10);

function disable_post_type_archive($query) {
    if (!is_admin() && is_post_type_archive('post')) {
        $query->set_404();
    }
}

add_action('pre_get_posts', 'disable_post_type_archive');

function disable_single_post_view($query) {
    if (!is_admin() && is_singular('post')) {
        $query->set_404();
    }
}

add_action('template_redirect', 'disable_single_post_view');

function unregister_post_post_type() {
    unregister_post_type('post');
}

add_action('init', 'unregister_post_post_type');


