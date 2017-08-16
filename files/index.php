<?php
/**
 * This file permit to check if the requester of a file is connected to the system
 *
 * Every request for a file will be redirect by htaccess to this index file.
 * If the user is connected, then the file is sended, otherwise the user is redirected to home page to be login
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE is licensed under a Creative Commons Attribution-NonCommercial 4.0 International License.
 * @category Files
 * @package Files
 */

session_start();
//Create and set var '$UserConnected' to true or false
include('../account/checkConnection.php');

/**
 * HTML Content is send if user is connected
 */
if(!$UserConnected) {
    header('Location: /index.php');
    exit();
}

//$_GET défined into .htaccess
$filename = htmlspecialchars($_GET['file']);
$format = substr($filename, strripos($filename, '.') + 1, strlen($filename) - strripos($filename, '.') - 1);

/**
 * Check if the requested extension is authorized in the system and edit the header.
 */
if(file_exists($filename)) {
    switch ($format) {
        case 'xlsx':
            header('Content-disposition: attachment');
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            readfile($filename);
            break;
        case 'pdf':
            header('Content-type: application/pdf');
            readfile($filename);
            break;
        case 'zip':
            header('Content-disposition: attachment');
            header('Content-type: application/zip');
            readfile($filename);
            break;
        case 'docx':
            header('Content-disposition: attachment');
            header('Content-type: application/msword');
            readfile($filename);
            break;
        default:
            echo $filename;
            header('Location: /error/404.php');
            break;
    }
} else {
    echo $filename;
    header('Location: /error/404.php');
}