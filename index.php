<?php
include "config.php";

$path = $folder.$_REQUEST['filename'];

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

// Only image and video
if(in_array($ext,['jpg','jpeg','png','bmp','gif','mp4','avi','mpg','mpeg'])){
    file_put_contents($path,file_get_contents('php://input'));
    if(file_exists($path)){
        //upload to telegram
        upload($path);
        //remove file
        unlink($path);
    }
}else{
    echo "not allowed";
}


function upload($path)
{
    global $chat_id, $bot_id;
    if(file_exists($path)){
        $bot_url    = "https://api.telegram.org/$bot_id/";
        $mime = mime_content_type($path);
        if (strpos($mime, "video") !== false) {
            $url        = $bot_url . "sendVideo?parse_mode=markdown&chat_id=" . $chat_id;
            $post_fields = array(
                'chat_id'   => $chat_id,
                'supports_streaming' => true,
                'parse_mode' => 'markdown',
                'video'     => new CURLFile(realpath($path))
            );
        } else {
            $url        = $bot_url . "sendPhoto?chat_id=" . $chat_id;

            $post_fields = array(
                'chat_id'   => $chat_id,
                'parse_mode' => 'markdown',
                'photo'     => new CURLFile(realpath($path))
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
    }else{
        return "FILE NOT EXISTS $path\n";
    }
    return $output;
}