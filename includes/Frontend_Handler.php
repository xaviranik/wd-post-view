<?php

namespace WD\Samurai;

use WD\Samurai\Frontend\Shortcode;

class Frontend_Handler {

    /**
     * Initializes the frontend
     */
    public function __construct() {
        Shortcode::render();
    }
}