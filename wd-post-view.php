<?php

/**
 * Plugin Name:       WeDevs Posts View
 * Plugin URI:        https://zabiranik.me
 * Description:       Tracks post view count, recent posts shortcode.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Zabir Anik
 * Author URI:        https://zabiranik.me
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wd-posts-email-notificaition
 */

use WD\Samurai\Frontend_Handler;
use WD\Samurai\Post_View;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/vendor/autoload.php';

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

        $this->register_page_handler();
        $this->register_post_view_count();
    }

    /**
     * Executes on plugin activation
     * @return void
     */
    public function activate() {

        $installed = get_option('wd_post_view_installed');

        if ( ! $installed ) {
            update_option('wd_post_view_installed', time());
        }

        update_option('wd_post_view_version', WD_POST_VIEW_VERSION);
    }

    /**
     * Register handler based on Admin/Frontend
     *
     * @return void
     */
    public function register_page_handler() {
        if ( ! is_admin() ) {
            new Frontend_Handler;
        }
    }

    /**
     * Registers post view count
     *
     * @return void
     */
    public function register_post_view_count() {
        $post_view = new Post_View;
        add_filter( 'the_content', [ $post_view, 'post_view_count' ], 1 );
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
