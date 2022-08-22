<?php

include 'config.php'; //A PHP array containing the data that we want to log.
include 'logging.php'; //A logging function
LogToFile('Recieved Request');
$path = $folder.getTitleFromName($_REQUEST['filename']).'/'; // get the folder path

$ext = strtolower(pathinfo($path.$_REQUEST['filename'], PATHINFO_EXTENSION)); // get the extension of the file

// Only image and video
if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'mp4', 'avi', 'mpg', 'mpeg'])) {
    if ($mode == 'local') {
        saveToDisk($path);
    } elseif ($mode == 'telegram') {
        sendToTelegram($path);
    } elseif ($mode == 'both') {
        saveToDisk($path);
        sendToTelegram($path);

    }
} else {
    echo 'not allowed';
    LogToFile('not allowed');
}
function saveToDisk($path)
{
    if (!file_put_contents($path.$_REQUEST['filename'], file_get_contents('php://input'))) { // if the directory does not exsit create it and try again
        mkdir($path);
        file_put_contents($path.$_REQUEST['filename'], file_get_contents('php://input'));
    }
}
function getTitleFromName($filename)
{
    $id = (explode('.', explode('-', $filename)[1])[0]);
    $json = json_decode(file_get_contents('./game_id.json'), true);
    if (array_key_exists($id, $json)) {
        return $json[$id];
    } else {
        LogToFile('Game does not exsit in database, you can add it yourself with this id: '.$id);

        return $id;
    }
}

function sendToTelegram()
{
    $path = "tmp/";
    LogToFile('Sending to telegram');
    if(!file_put_contents($path.$_REQUEST['filename'], file_get_contents('php://input'))){
        mkdir($path);
    }
    global $chat_id, $bot_id;
    if (file_exists($path.$_REQUEST['filename'])) {
        $bot_url = "https://api.telegram.org/bot". $bot_id."/";
        $mime = mime_content_type($path.$_REQUEST['filename']);
        if (strpos($mime, 'video') !== false) {
            $url = $bot_url.'sendVideo?parse_mode=markdown&chat_id='.$chat_id;
            $post_fields = [
                'chat_id' => $chat_id,
                'supports_streaming' => true,
                'parse_mode' => 'markdown',
                'video' => new CURLFile(realpath($path.$_REQUEST['filename'])),
            ];
        } else {
            $url = $bot_url.'sendPhoto?chat_id='.$chat_id;
            

            $post_fields = [
                'chat_id' => $chat_id,
                'parse_mode' => 'markdown',
                'caption' => getTitleFromName($_REQUEST['filename']),
                'photo' => new CURLFile(realpath($path.$_REQUEST['filename'])),
            ];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:multipart/form-data',
        ]);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        LogToFile($url);
        LogToFile($output);
        curl_close($ch);
        echo $output;
    } else {
        LogToFile("FILE NOT EXISTS". $path.$_REQUEST['filename'] . "\n");
    }
    unlink($path.$_REQUEST['filename']);

    return $output;
}
