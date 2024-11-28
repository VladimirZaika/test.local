<?php
/*
Plugin Name: NASA's Beautiful Images
Description: Display beautiful images from NASA.
Version: 1.0
Author: VZ
*/

require_once plugin_dir_path( __FILE__ ) . 'includes/class-nasa-api-service.php';

if ( !defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

class NasaBeautifulImages {

    public function __construct() {
        // Hooks for menus, logging settings and loading scripts
        add_action( 'admin_menu', [$this, 'add_admin_menu'] );
        add_action( 'admin_init', [$this, 'register_settings'] );
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );

        // Add action for WP Cron
        add_action( 'nasa_fetch_api_data', ['NasaAPIService', 'fetch_and_save_api_data'] );

        if ( !wp_next_scheduled('nasa_fetch_api_data') ) {
            wp_schedule_event( time(), 'twicedaily', 'nasa_fetch_api_data' );
        }
    }

    public function enqueue_assets() {
        $screen = get_current_screen();

        if ( $screen->id === 'toplevel_page_nasa_beautiful_images' || $screen->id === 'nasas-beautiful-images_page_nasa_beautiful_images_settings' ) {
            wp_enqueue_style( 'nasa-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.min.css' );

            $RequestChoice = get_option( 'request_choice', 'php' );

            if ( $RequestChoice === 'js' ) {
                wp_enqueue_script( 'nasa-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.min.js', ['jquery'], null, true );

                $localized_data = apply_filters( 'nasa_request_args_js', [
                    'api_key' => get_option( 'nasa_api_key', '' ),
                    'image_days' => get_option( 'nasa_image_days', '1' ),
                    'language_choice' => $RequestChoice,
                    'api_url' => 'https://api.nasa.gov/planetary/apod',
                    'pluginUrl' => esc_url( plugin_dir_url(__FILE__) ),
                ] );

                wp_localize_script( 'nasa-plugin-script', 'nasaSettings', $localized_data );
            }
        }
    }

    public function add_admin_menu() {
        add_menu_page(
            "NASA's Beautiful Images", 
            "NASA's Beautiful Images", 
            'manage_options', 
            'nasa_beautiful_images', 
            [$this, 'view_images_page'], 
            'dashicons-admin-media', 
            6
        );

        add_submenu_page(
            'nasa_beautiful_images',
            'Settings', 
            'Settings', 
            'manage_options', 
            'nasa_beautiful_images_settings', 
            [$this, 'settings_page']
        );
    }

    public function view_images_page() {
        $nasaApiData = get_option( 'nasa_api_data', '' );
        $nasaImages = !empty( $nasaApiData ) ? json_decode( $nasaApiData, true ) : [];
        $templatePath = plugin_dir_path( __FILE__ ) . 'templates/view-images-template.php';

        if ( file_exists($templatePath) ) {
            include $templatePath;
        } else {
            echo '<div class="wrap"><h1>NASA\'s Beautiful Images</h1>';
            echo '<p>Template not found.</p></div>';
        }
    }

    public function settings_page() {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( isset($_POST['nasa_api_key']) ) {
                update_option( 'nasa_api_key', sanitize_text_field($_POST['nasa_api_key']) );
            }

            if ( isset($_POST['nasa_image_days']) ) {
                update_option( 'nasa_image_days', sanitize_text_field($_POST['nasa_image_days']) );
            }

            if ( isset($_POST['request_choice']) ) {
                update_option( 'request_choice', sanitize_text_field($_POST['request_choice']) );
            }
    
            // Call method for API processing
            NasaAPIService::fetch_and_save_api_data();
    
            add_action( 'nasa_before_form_content', array($this, 'success') );
        }
    
        ?>

        <div class="wrap">
            <h1><?php _e( 'NASA\'s Beautiful Images Settings', 'nbi' ); ?></h1>
            <?php do_action( 'nasa_before_form_content' ); ?>
            <form method="post" action="">
                <?php
                settings_fields('nasa_images_settings_group');
                do_settings_sections('nasa_beautiful_images_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function success() {
        return print_r('<div class="notice notice-success"><p>The settings have been successfully saved.</p></div>');
    }

    public function register_settings() {
        register_setting( 'nasa_images_settings_group', 'nasa_api_key' );
        register_setting( 'nasa_images_settings_group', 'nasa_image_days' );
        register_setting( 'nasa_images_settings_group', 'request_choice' );
        register_setting( 'nasa_images_settings_group', 'nasa_api_data' );

        add_settings_section(
            'nasa_images_main_section', 
            'Main Settings', 
            null, 
            'nasa_beautiful_images_settings'
        );

        add_settings_field(
            'nasa_api_key', 
            'NASA API Key', 
            [$this, 'api_key_field_callback'], 
            'nasa_beautiful_images_settings', 
            'nasa_images_main_section'
        );

        add_settings_field(
            'nasa_image_days', 
            'Images from the last few days', 
            [$this, 'image_days_field_callback'], 
            'nasa_beautiful_images_settings', 
            'nasa_images_main_section'
        );

        add_settings_field(
            'request_choice', 
            'Request type', 
            [$this, 'language_choice_field_callback'], 
            'nasa_beautiful_images_settings', 
            'nasa_images_main_section'
        );
    }

    public function api_key_field_callback() {
        $value = get_option( 'nasa_api_key', '' );
        echo '<input type="password" name="nasa_api_key" value="' . esc_attr( $value ) . '" />';
    }

    public function image_days_field_callback() {
        $value = get_option( 'nasa_image_days', '1' );
        echo '<select name="nasa_image_days">
                <option value="0"' . selected( $value, '0', false ) . '>1 Day</option>
                <option value="2"' . selected( $value, '2', false ) . '>3 Days</option>
                <option value="4"' . selected( $value, '4', false ) . '>5 Days</option>
                <option value="6"' . selected( $value, '6', false ) . '>7 Days</option>
                <option value="14"' . selected( $value, '14', false ) . '>15 Days</option>
              </select>';
    }

    public function language_choice_field_callback() {
        $value = get_option( 'request_choice', 'php' );
        echo '<input type="radio" name="request_choice" value="php"' . checked( $value, 'php', false ) . '> php ';
        echo '<input type="radio" name="request_choice" value="js"' . checked( $value, 'js', false ) . '> js';
    }
}

// Plug-in initialization
if ( is_admin() ) {
    $NasaBeautifulImages = new NasaBeautifulImages();
}


// Remove options on unistall
register_uninstall_hook(__FILE__, 'nasa_beautiful_images_uninstall');

function nasa_beautiful_images_uninstall() {
    delete_option('nasa_api_key');
    delete_option('request_choice');
    delete_option('nasa_image_days');
    delete_option('nasa_api_data');
}
