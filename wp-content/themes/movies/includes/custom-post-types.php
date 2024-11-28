<?php
function register_movies_post_type() {
    register_post_type('movies', [
        'labels' => [
            'name' => __('Movies'),
            'singular_name' => __('Movie'),
            'add_new' => __('Add New Movie'),
            'add_new_item' => __('Add New Movie'),
            'edit_item' => __('Edit Movie'),
            'new_item' => __('New Movie'),
            'view_item' => __('View Movie'),
            'search_items' => __('Search Movies'),
            'not_found' => __('No Movies found'),
            'not_found_in_trash' => __('No Movies found in Trash'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => get_template_directory_uri() . '/src/images/admin-icon.svg',
        'rewrite' => ['slug' => 'movies'],
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'],
        'taxonomies' => ['genre'],
    ]);
}
add_action('init', 'register_movies_post_type');
