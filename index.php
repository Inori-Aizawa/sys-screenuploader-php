<?php

include 'config.php'; //A PHP array containing the data that we want to log.
include 'logging.php'; //A logging function

$path = $folder.getTitleFromName($_REQUEST['filename']).'/'; // get the folder path 

$ext = strtolower(pathinfo($path.$_REQUEST['filename'], PATHINFO_EXTENSION)); // get the extension of the file

// Only image and video
if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'mp4', 'avi', 'mpg', 'mpeg'])) {
    if (!file_put_contents($path.$_REQUEST['filename'], file_get_contents('php://input'))) { // if the directory does not exsit create it and try again
        mkdir($path);
        file_put_contents($path.$_REQUEST['filename'], file_get_contents('php://input'));
    }
} else {
    echo 'not allowed';
    LogToFile('not allowed');
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
// from https://www.php.net/manual/en/function.file-put-contents.php#84180
function file_force_contents($dir, $contents)
{
    $parts = explode('/', $dir);
    $file = array_pop($parts);
    $dir = '';
    foreach ($parts as $part) {
        if (!is_dir($dir .= "/$part")) {
            mkdir($dir);
        }
    }
    file_put_contents("$dir/$file", $contents);
}
