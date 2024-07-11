<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WP_Latest_Posts_Settings {
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_init', array(__CLASS__, 'settings_init'));
    }

    public static function add_admin_menu() {
        add_options_page(
            'Shortpointer', 
            'Shortpointer', 
            'manage_options', 
            'wp_latest_posts', 
            array(__CLASS__, 'settings_page')
        );
    }

    public static function settings_init() {
        register_setting('wp_latest_posts', 'wp_latest_posts_count');

        add_settings_section(
            'wp_latest_posts_section', 
            __('Настройки Shortpointer', 'wp-latest-posts'), 
            null, 
            'wp_latest_posts'
        );

        add_settings_field(
            'wp_latest_posts_count', 
            __('Количество постов', 'wp-latest-posts'), 
            array(__CLASS__, 'render_count_field'), 
            'wp_latest_posts', 
            'wp_latest_posts_section'
        );
    }

    public static function render_count_field() {
        $value = get_option('wp_latest_posts_count', 10);
        echo '<input type="number" name="wp_latest_posts_count" value="' . esc_attr($value) . '" min="1">';
    }

    public static function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p>Используйте шорткод [latest_posts] для вывода постов.</p>
            <form action="options.php" method="post">
                <?php
                settings_fields('wp_latest_posts');
                do_settings_sections('wp_latest_posts');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
