<?php
/**
 * ACF JSON
 */
function acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}

add_filter( 'acf/settings/save_json', 'acf_json_save_point' );

/**
 * ACF options
 */
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page( [
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ] );

    acf_add_options_sub_page( [
        'page_title'    => 'Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ] );

    acf_add_options_sub_page( [
        'page_title'    => 'Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ] );
}


/**
 * Dinamyc date
 */
if ( !is_admin() ) {
    add_filter( 'acf/load_value/name=footer_text', function ( $value ) {
        return str_replace( '@year', date( 'Y' ), $value );
    } );
}

/**
 * ACF Gutenberg block register
 */
function custom_blocks_acf_init() {
	$icon = '<svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M28.7625 5.24063C33.6985 5.24063 37.4157 7.3125 37.4157 9.14062C37.4157 10.9688 33.6985 13.0406 28.7625 13.0406C23.8266 13.0406 20.1094 10.9688 20.1094 9.14062C20.1094 7.3125 23.8266 5.24063 28.7625 5.24063ZM28.7625 3.65625C23.0953 3.65625 18.525 6.09375 18.525 9.07969C18.525 12.0656 23.0953 14.5031 28.7625 14.5031C34.4297 14.5031 39 12.1266 39 9.14062C39 6.15469 34.4297 3.65625 28.7625 3.65625Z" fill="#20293B"/>
                <path d="M28.7625 9.81093C29.4692 9.81093 30.0422 9.51082 30.0422 9.14062C30.0422 8.77042 29.4692 8.47031 28.7625 8.47031C28.0557 8.47031 27.4828 8.77042 27.4828 9.14062C27.4828 9.51082 28.0557 9.81093 28.7625 9.81093Z" fill="#20293B"/>
                <path d="M28.7625 3.65625C26.3859 3.65625 24.1312 4.14375 22.425 4.875C22.6078 4.81406 22.7906 4.75312 22.9125 4.69219H1.58437C0.73125 4.69219 0 5.3625 0 6.27656V26.6906C0 27.5437 0.73125 28.275 1.58437 28.275H19.1344V30.1641C19.1344 32.9672 23.4 35.2828 28.7016 35.2828C34.0031 35.2828 38.2687 32.9672 38.2687 30.1641V11.0297C38.6953 10.4203 38.8781 9.81094 38.8781 9.14062C39 6.15469 34.4297 3.65625 28.7625 3.65625ZM22.0594 4.99687C21.9375 5.05781 21.8766 5.11875 21.7547 5.11875C21.8766 5.11875 21.9984 5.05781 22.0594 4.99687ZM20.1703 6.15469C20.0484 6.21563 19.9875 6.3375 19.8656 6.39844C19.9875 6.3375 20.0484 6.27656 20.1703 6.15469ZM15.9656 5.97188H18.525V8.53125H15.9656V5.97188ZM5.78906 26.9953H3.22969V24.4359H5.78906V26.9953ZM5.78906 8.47031H3.22969V5.97188H5.78906V8.47031ZM12.1875 26.9953H9.62813V24.4359H12.1875V26.9953ZM12.1875 8.47031H9.62813V5.97188H12.1875V8.47031ZM18.525 26.9953H15.9656V24.4359H18.525V26.9953ZM18.525 9.14062C18.525 8.95781 18.525 8.83594 18.5859 8.65312C18.5859 8.775 18.525 8.95781 18.525 9.14062ZM19.3172 7.06875C19.1953 7.19063 19.1344 7.3125 19.0125 7.49531C19.0734 7.37344 19.1953 7.19063 19.3172 7.06875ZM18.9516 7.55625C18.8906 7.67812 18.8297 7.8 18.7687 7.98281C18.8297 7.86094 18.8906 7.67812 18.9516 7.55625ZM36.7453 30.225C36.7453 31.6875 33.6984 33.7594 28.7625 33.7594C23.8266 33.7594 20.7188 31.6875 20.7188 30.225V12.5531C22.6078 13.8328 25.4719 14.625 28.7016 14.625C31.9312 14.625 34.8563 13.8328 36.6844 12.5531V30.225H36.7453ZM28.7625 13.0406C23.8266 13.0406 20.1094 10.9688 20.1094 9.14062C20.1094 7.3125 23.8266 5.24063 28.7625 5.24063C33.6984 5.24063 37.4156 7.3125 37.4156 9.14062C37.4156 10.9688 33.6984 13.0406 28.7625 13.0406Z" fill="#20293B"/>
                <path d="M27.4828 16.8188V19.3781" stroke="#20293B" stroke-width="1.5" stroke-miterlimit="10" stroke-linejoin="round"/>
                <path d="M30.0422 16.8188V23.1563" stroke="#20293B" stroke-width="1.5" stroke-miterlimit="10" stroke-linejoin="round"/>
                <path d="M24.3141 12.9797V33.3938" stroke="#20293B" stroke-width="1.5" stroke-miterlimit="10" stroke-linejoin="round"/>
            </svg>';

	if( function_exists('acf_register_block') ) {

		acf_register_block( [
			'name'              => 'block-hero',
			'title'             => __('Hero Block'),
			'description'       => __('Hero Block Module'),
			'render_template'   => '/template-parts/blocks/block-hero/block-hero.php',
			'category'          => 'base-template-blocks',
			'keywords'          => ['primary', 'hero',''],
			'multiple'          => true,
			'icon' 				=> $icon,
			'mode'              => 'edit',
			'example'  => [
	            'attributes' => [
	                'mode' => 'preview',
	                'data' => [
	                	'preview_image_help' => get_theme_file_uri().'/src/images/block-hero.png',
					]
				]
			],
            'enqueue_assets' => function() {
                $stylePath = 'dist/css/blocks/block-hero/block-hero.css';
                $styleVersion = filemtime( get_theme_file_path($stylePath) );

                wp_enqueue_style(
                    'block-hero-style', 
                    get_theme_file_uri() . '/' . $stylePath, [], $styleVersion
                );
            },
		] );

        acf_register_block( [
			'name'              => 'block-filters',
			'title'             => __('Filters Block'),
			'description'       => __('Filters Block Module'),
			'render_template'   => '/template-parts/blocks/block-filters/block-filters.php',
			'category'          => 'base-template-blocks',
			'keywords'          => ['primary', 'filters',''],
			'multiple'          => true,
			'icon' 				=> $icon,
			'mode'              => 'edit',
			'example'  => [
	            'attributes' => [
	                'mode' => 'preview',
	                'data' => [
	                	'preview_image_help' => get_theme_file_uri().'/src/images/block-filters.png',
					]
				]
			],
            'enqueue_assets' => function() {
                $stylePath = 'dist/css/blocks/block-filters/block-filters.css';
                $styleVersion = filemtime( get_theme_file_path($stylePath) );

                wp_enqueue_style(
                    'block-filters-style', 
                    get_theme_file_uri() . '/' . $stylePath, [], $styleVersion
                );
            },
		] );
	}
}

add_action('acf/init', 'custom_blocks_acf_init');

/**
 * Define Movies category module Gutenberg
 */
function custom_block_category( $categories, $post ) {
	$custom_block = [
	  'slug' => 'base-template-blocks',
	  'title' => __( 'Movies Modules', 'custom-blocks' ),
	];

	$categories_sorted = [];
	$categories_sorted[0] = $custom_block;

	foreach ($categories as $category) {
		$categories_sorted[] = $category;
	}

	return $categories_sorted;
}

add_filter( 'block_categories', 'custom_block_category', 10, 2);
