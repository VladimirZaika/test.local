<?php
function movie_dynamic_styles() {
    $styleVersion = filemtime( get_theme_file_path('dist/css/main.css') );
    $scriptVersion = filemtime( get_theme_file_path('dist/js/main.js') );
    $singleStyleVer = filemtime( get_theme_file_path('dist/css/template/single-movies/single-movies.css') );

	wp_enqueue_style('movies-variables', get_stylesheet_uri());
	wp_enqueue_style( 'movies-styles', get_theme_file_uri( 'dist/css/main.css' ), [], $styleVersion  );

    if (is_singular('movies')) {
        wp_enqueue_style( 'single-movies-styles', get_theme_file_uri( 'dist/css/template/single-movies/single-movies.css' ), [], $singleStyleVer  );
    }

	wp_enqueue_script( 'movies-scripts', get_theme_file_uri( 'dist/js/main.js' ), [], $scriptVersion, true );

    $wpData = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restPath' => home_url('/wp-json/movies/v1/'),
    ];

    wp_localize_script( 'movies-scripts', 'wpData', $wpData );
    
    $primaryColor = get_theme_mod('primary_color', '#ff902b');
    $secondaryColor = get_theme_mod('secondary_color', '#2f2105');
    $textColor = get_theme_mod('text_color', '#7e7d7a');
    $textColor2 = get_theme_mod('text_color_2', '#000');
    $bodyColor = get_theme_mod('body_color', '#fff');
    $sectionColor = get_theme_mod('section_color', '#f6ebda');

    $customCss = "
        :root {
            --primary-color: $primaryColor;
            --secondary-color: $secondaryColor;
            --text-color: $textColor;
            --text-color-2: $textColor2;
            --body-color: $bodyColor;
            --section-color: $sectionColor;
        }";

    wp_add_inline_style( 'movies-variables', $customCss );
};

add_action('wp_enqueue_scripts', 'movie_dynamic_styles');

