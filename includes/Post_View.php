<?php

namespace WD\Samurai;

class Post_View {

    protected $count_key = 'post_views_count';

    /**
     * Modifies the count element
     *
     * @param string $count
     * @return string
     */
    public function modify_count_element( $count )
    {
        return "<em>{$count}</em>";
    }

    /**
     * Tracks the view count of a post
     *
     * @param string $content
     * @return string
     */
    public function post_view_count( $content )
    {
        if ( is_singular() && in_the_loop() && is_main_query() ) {
            $count = $this->get_post_view_count();
            $count++;
            update_post_meta( get_the_ID(), $this->count_key, $count );

            add_filter('modify_count_value', [ $this, 'modify_count_element'] );
            return $content . '<p>View Count: ' . apply_filters( 'modify_count_value', $count ) . '</p>';
        }
    }

    /**
     * Gets the post view count
     *
     * @return integer
     */
    public function get_post_view_count() {
        $count = get_post_meta( get_the_ID(), $this->count_key, true );
        if ( $count == '' ) {
            $count = 0;
            delete_post_meta( get_the_ID(), $this->count_key );
            add_post_meta( get_the_ID(), $this->count_key, '0' );
        }
        return $count;
    }
}
