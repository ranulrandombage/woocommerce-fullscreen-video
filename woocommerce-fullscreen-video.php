<?php
/*
Plugin Name: WooCommerce Fullscreen Video
Plugin URI: https://yourwebsite.com/woocommerce-fullscreen-video
Description: Displays fullscreen videos for products based on device type
Version: 1.0.5
Author: Zegenlink
Author URI: https://zegenlink.com
License: GPL-2.0+
Text Domain: woocommerce-fullscreen-video
Domain Path: /languages
*/

defined('ABSPATH') || exit;

// Define plugin constants
define('WCFV_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WCFV_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCFV_VERSION', '1.0.5');

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', 'wcfv_woocommerce_missing_notice');
    return;
}

function wcfv_woocommerce_missing_notice() {
    echo '<div class="error"><p>';
    _e('WooCommerce Fullscreen Video requires WooCommerce to be installed and active.', 'woocommerce-fullscreen-video');
    echo '</p></div>';
}

// Include required files
require_once WCFV_PLUGIN_PATH . 'includes/class-video-handler.php';

// Initialize the plugin
function wcfv_init() {
    new WC_Fullscreen_Video_Handler();
}
add_action('plugins_loaded', 'wcfv_init');