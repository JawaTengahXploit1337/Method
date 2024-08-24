<?php

$targetFile = 'path/to/your/file.php';
$backupFile = 'path/to/backup/file.php';

function restoreFile($target, $backup) {
    if (file_exists($backup)) {
        copy($backup, $target);
        echo "File restored successfully.";
    } else {
        echo "Backup file does not exist.";
    }
}

if (!file_exists($targetFile)) {
    restoreFile($targetFile, $backupFile);
} else {
    echo "File exists.";
}
?>
