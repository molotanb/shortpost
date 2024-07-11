<?php

if (!defined('ABSPATH')) {
    exit;
}

require 'vendor/autoload.php';

require_once JWR_SP . 'classes/include.php';

function wp_latest_posts_plugin_init() {
    WP_Latest_Posts::init();
    WP_Latest_Posts_Settings::init();
}

add_action('plugins_loaded', 'wp_latest_posts_plugin_init');