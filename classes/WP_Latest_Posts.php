<?php

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WP_Latest_Posts {
    private static $instance = null;
    private $logger;

    public static function init() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->logger = new class implements LoggerInterface {
            public function emergency($message, array $context = array()) {}
            public function alert($message, array $context = array()) {}
            public function critical($message, array $context = array()) {}
            public function error($message, array $context = array()) {
                error_log('[ERROR] ' . $message);
            }
            public function warning($message, array $context = array()) {}
            public function notice($message, array $context = array()) {}
            public function info($message, array $context = array()) {}
            public function debug($message, array $context = array()) {}
            public function log($level, $message, array $context = array()) {
                error_log('[' . strtoupper($level) . '] ' . $message);
            }
        };

        add_shortcode('latest_posts', array($this, 'render_latest_posts'));
    }

    public function render_latest_posts($atts) {
        try {
            $atts = shortcode_atts(array(
                'count' => get_option('wp_latest_posts_count', 10)
            ), $atts, 'latest_posts');

            $query = new WP_Query(array(
                'posts_per_page' => $atts['count']
            ));

            if (!$query->have_posts()) {
                throw new Exception('No posts found.');
            }

            ob_start();

            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';

            wp_reset_postdata();

            return ob_get_clean();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return '<p>An error occurred while fetching the latest posts.</p>';
        }
    }
}
