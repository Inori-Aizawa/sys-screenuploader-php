<?php

function LogToFile($data)
{
    // from https://thisinterestsme.com/logging-file-php/
    //A PHP array containing the data that we want to log.
    $dataToLog = [
date('Y-m-d H:i:s'), //Date and time
$data,
];
    //Turn array into a delimited string using
    //the implode function
    $data = implode(' - ', $dataToLog);

    //Add a newline onto the end.
    $data .= PHP_EOL;

    //The name of your log file.
    //Modify this and add a full path if you want to log it in
    //a specific directory.
    $pathToFile = 'log.log';

    //Log the data to your file using file_put_contents.
    file_put_contents($pathToFile, $data, FILE_APPEND);
}
