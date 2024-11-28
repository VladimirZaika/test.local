<?php
/**
 * movies functions
 *
 * @package movies
 */

/**
 * Register custom post types
 */
require_once get_template_directory() . '/includes/custom-post-types.php';

/**
 * Register custom taxonomies
 */
require_once get_template_directory() . '/includes/custom-taxonomies.php';

/**
 * Set up theme defaults and registers support for various WordPress feaures.
 */
require_once get_template_directory() . '/includes/theme-default.php';

/**
 * Register widget area.
 */
require_once get_template_directory() . '/includes/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require_once get_template_directory() . '/includes/scripts.php';

/**
 * Plugin settings: ACF
 */
require_once get_template_directory() . '/includes/plugin-settings/acf.php';

/**
 * Custom functions
 */
require_once get_template_directory() . '/includes/custom-functions.php';
