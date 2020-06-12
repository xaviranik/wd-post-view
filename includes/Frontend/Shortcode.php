<?php

namespace WD\Samurai\Frontend;

use WD\Samurai\Post_View;
use WP_Query;

class Shortcode {

    /**
     * Renders shortcode
     *
     * @return void
     */
    public static function render() {
        add_shortcode( 'recent-posts', [ new self, 'prepare' ] );
    }

    /**
     * Prepares shortcode
     *
     * @return string
     */
    public function prepare( $attr = [], $content = null ) {

        $output = "";
        $params = shortcode_atts([
            'posts_per_page' => 10,
            'orderby'        => 'meta_value',
            'meta_key'       => 'post_views_count',
            'order'          => 'desc',
            'category_name' => '',
            'post_type' => 'any', 
        ], $attr);

        if ( isset( $attr['post_ids'] ) ) {
            $params['post__in'] = explode('|', $attr['post_ids']);
        }
        
        $query = new WP_Query( $params );

        if ($query->have_posts()) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                add_filter('add_view_count', [$this, 'add_view_count'], 1);
                $output .= '<p><a href="' . get_permalink() . '">' . get_the_title() . '</a>' . '<span> Views:' . apply_filters('add_view_count', $output) . '</span></p>';
            }
            wp_reset_postdata();
        }

        return $output;
    }

    /**
     * Append view count to shortcode output
     *
     * @param string $output
     * @return string
     */
    public function add_view_count( $output ) {
        $post_view = new Post_View;
        return $post_view->get_post_view_count();
    }


}