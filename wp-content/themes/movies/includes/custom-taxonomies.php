<?php
function register_genre_taxonomy() {
    register_taxonomy('genre', 'movies', [
        'labels' => [
            'name' => __('Genres'),
            'singular_name' => __('Genre'),
            'search_items' => __('Search Genres'),
            'all_items' => __('All Genres'),
            'edit_item' => __('Edit Genre'),
            'update_item' => __('Update Genre'),
            'add_new_item' => __('Add New Genre'),
            'new_item_name' => __('New Genre Name'),
        ],
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'genre'],
    ] );
}
add_action( 'init', 'register_genre_taxonomy' );
