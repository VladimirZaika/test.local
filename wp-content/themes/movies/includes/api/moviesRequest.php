<?php

class MovieRequest {
    public static function init() {
        add_action('wp_ajax_get_movies', [__CLASS__, 'ajax']);
        add_action('wp_ajax_nopriv_get_movies', [__CLASS__, 'ajax']);
        
        add_action('rest_api_init', function() {
            register_rest_route('movies/v1', '/get', [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'rest'],
                'permission_callback' => '__return_true',
            ]);
        });
    }

    public static function get_nonce() {
        return wp_create_nonce('movies-request-security');
    }

    public static function rest(WP_REST_Request $request) {
        $params = $request->get_params();
        $response = self::handle_request($params);
        $renderMarkup = self::render($response);

        return $renderMarkup;
    }

    public static function ajax() {
        if ( !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'movies-request-security') ) {
            die( '<div class="movie-error-wrapper"><span class="movie-error-text">Permissions check failed.</span></div>' );
        }

        $params = $_GET;
        $response = self::handle_request($params);
        $renderMarkup = self::render($response);

        if ($renderMarkup['success']) {
            wp_send_json_success($renderMarkup['data']);
        } else {
            wp_send_json_error($renderMarkup['message']);
        }
    }

    private static function render($response) {
        if ( $response['success'] ) {
            ob_start();

            foreach ( $response['data']['posts'] as $movie ) {
                get_template_part( 'template-parts/movie', 'card', $movie );
            }

            $response['data']['posts'] = ob_get_contents();

            ob_end_clean();
        }

        return $response;
    }

    public static function handle_request($params) {
        $getGenre = $params['movie_genre'] ?? null;
        $getFrom = $params['movie_from'] ?? null;
        $getTo = $params['movie_to'] ?? null;
        $getPage = $params['movie_page'] ?? 1;
        $getSort = $params['movie_sort'] ?? null;
        $getSearch = $params['movie_search'] ?? '';
        $postsPerPage = $params['posts_per_page'] ?? 6;

        if (!empty($getSearch)) {
            $getPage = 1;
        }

        $sortParams = [
            'rating_asc' => ['meta_key' => 'rating', 'orderby' => 'meta_value_num', 'order' => 'ASC'],
            'rating_desc' => ['meta_key' => 'rating', 'orderby' => 'meta_value_num', 'order' => 'DESC'],
            'date_asc' => ['meta_key' => 'release_date', 'orderby' => 'meta_value', 'order' => 'ASC'],
            'date_desc' => ['meta_key' => 'release_date', 'orderby' => 'meta_value', 'order' => 'DESC'],
        ];

        $args = [
            'post_type' => 'movies',
            'posts_per_page' => $postsPerPage,
            'paged' => $getPage,
            's' => !empty($getSearch) ? $getSearch : null,
            'meta_query' => ['relation' => 'AND'],
        ];

        if ($getGenre) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'genre',
                    'field' => 'slug',
                    'terms' => $getGenre,
                ],
            ];
        }

        if ($getFrom || $getTo) {
            if ($getFrom && $getTo && $getTo < $getFrom) {
                [$getFrom, $getTo] = [$getTo, $getFrom];
            }
        
            if ($getFrom && $getTo) {
                $dateFilter = [
                    'key' => 'release_date',
                    'value' => [$getFrom, $getTo],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            } elseif ($getFrom) {
                $dateFilter = [
                    'key' => 'release_date',
                    'value' => $getFrom,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ];
            } elseif ($getTo) {
                $dateFilter = [
                    'key' => 'release_date',
                    'value' => $getTo,
                    'compare' => '<=',
                    'type' => 'NUMERIC',
                ];
            }
        
            $args['meta_query'][] = $dateFilter;
        }        

        if ($getSort && isset($sortParams[$getSort])) {
            $args['meta_key'] = $sortParams[$getSort]['meta_key'];
            $args['orderby'] = $sortParams[$getSort]['orderby'];
            $args['order'] = $sortParams[$getSort]['order'];
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $posts = [];
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();

                $posts[] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'rating' => get_field('rating', $id),
                    'id' => $id,
                ];
            }

            wp_reset_postdata();

            return [
                'success' => true,
                'data' => [
                    'posts' => $posts,
                    'max_num_pages' => $query->max_num_pages,
                    'paged' => $getPage,
                ],
            ];
        } else {
            return [
                'success' => false,
                'message' => __('No movies found.', 'movies'),
            ];
        }
    }

    public static function search($searchTerm) {
        $args = [
            'post_type' => 'movies',
            'posts_per_page' => -1,
            's' => $searchTerm,
            'orderby' => 'title',
            'order' => 'ASC',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $posts = [];
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();

                $posts['data']['posts'] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'rating' => get_field('rating', $id),
                    'id' => $id,
                ];
            }

            wp_reset_postdata();

            $renderMarkup = self::render($posts);

            return $renderMarkup;
        }

        return [];
    }
}
