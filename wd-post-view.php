<?php

/**
 * Plugin Name:       WeDevs Posts View
 * Plugin URI:        https://zabiranik.me
 * Description:       Tracks post view count.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Zabir Anik
 * Author URI:        https://zabiranik.me
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wd-posts-email-notificaition
 */

if ( ! defined( 'ABSPATH' ) ) exit;

final class WD_Post_View {

    /**
     * WD Posts View Count Version
     * @var string
     */
    const version = '1.0.0';

    /**
     * Class Constructor
     */
    private function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action('plugins_loaded', [ $this, 'init_plugin' ]);
    }

    /**
     * Initializes a Singleton
     * @return \WD_Posts_View_Count
     */
    public static function init() {

        static $instance = false;

        if ( ! $instance ) {
            $instance = new Self();
        }

        return $instance;
    }

    /**
     * Defines plugin constants
     * @return void
     */
    public function define_constants() {

        define('WD_POST_VIEW_VERSION', self::version);
    }

    /**
     * Plugin init
     * @return void
     */
    public function init_plugin() {

        add_filter( 'the_content', [ $this, 'post_view_count' ], 1 );
        add_filter( 'modify_count_value', [ $this, 'modify_count_element' ] );
    }

    /**
     * Modifies the count element
     *
     * @param string $count
     * @return string
     */
    public function modify_count_element( $count ) {
        
        return "<em>{$count}</em>";
    }

    /**
     * Executes on plugin activation
     * @return void
     */
    public function activate() {

        $installed = get_option('wd_post_view_installed');

        if (!$installed) {
            update_option('wd_post_view_installed', time());
        }

        update_option('wd_post_view_version', WD_POST_VIEW_VERSION);
    }

    /**
     * Tracks the view count of a post
     *
     * @param string $content
     * @return string
     */
    public function post_view_count( $content ) {

        if ( is_singular() && in_the_loop() && is_main_query() ) {
            $count_key = 'post_views_count';
            $count = get_post_meta( get_the_ID(), $count_key, true);
            if ( $count == '' ) {
                $count = 0;
                delete_post_meta( get_the_ID(), $count_key );
                add_post_meta( get_the_ID(), $count_key, '0' );
            } else {
                $count++;
                update_post_meta( get_the_ID(), $count_key, $count );
            }

            return $content . '<p>View Count: ' . apply_filters( 'modify_count_value', $count ) . '</p>';
        }

        return $content;
    }
}

/**
 * WD Posts View Count Instance init
 * @return \WD_Post_View
 */
function WD_post_view_init() {

    return WD_Post_View::init();
}

// Turn on the plugin
WD_post_view_init();
