<?php

if ( !defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

class NasaAPIService {

    public static function fetch_and_save_api_data() {
        $apiKey = get_option('nasa_api_key');
        $imageDays = get_option('nasa_image_days', '1');
        $apiUrl = 'https://api.nasa.gov/planetary/apod';

        if ( empty($apiKey) ) {
            update_option( 'nasa_api_data', '' );
            return;
        }

        $endDate = date( 'Y-m-d' );
        $startDate = date( 'Y-m-d', strtotime("-{$imageDays} days") );

        // Request params
        $request_args = apply_filters( 'nasa_request_args', [
            'thumbs'     => 'true',
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'api_key'    => $apiKey,
        ] );

        // Request
        $response = wp_remote_get($apiUrl, [
            'body' => $request_args,
        ]);

        if ( is_wp_error($response) ) {
            error_log( 'Error in API request: ' . $response->get_error_message() );
            return;
        }

        $data = wp_remote_retrieve_body($response);

        // Save data
        if ( !empty($data) ) {
            update_option( 'nasa_api_data', $data );
        } else {
            error_log( 'Empty response from API' );
        }
    }
}
