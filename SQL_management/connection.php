<?php
/**
 * This file is use to manage connection to DataBase. You can have more information about DataBase on 1&1 account
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package SQL
 * @namespace SQL
 * @filesource
 */

/*
date_default_timezone_set('Asia/Kuala_Lumpur');
*/
/* For Test on Local */
$db_host_name  = DTB_HOST;
$db_database   = DTB_NAME;
$db_user_name  = DTB_LOGIN;
$db_password   = DTB_PASS;

try {
    $bdd = new PDO('mysql:host='.$db_host_name.';dbname='.$db_database,$db_user_name,$db_password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


//*------------------------------------------------------------------------*//
//*--------------------Secured by token functions--------------------------*//
//*------------------------------------------------------------------------*//

/**
 * Save and return a token
 *
 * @param string $name optional name for token
 * @return string token to send
 *
 * @tags Form Token
 * @source SQL_management/connection.php
 */
function generate_token($name = '')
{
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    $token = uniqid(rand(), true);
    $_SESSION[$name.'_token'] = $token;
    $_SESSION[$name.'_token_time'] = time();

    return $token;
}

/**
 * Check token validity
 *
 * @param string $timeout validity time of token
 * @param string $referer requested address to validate the token
 * @param string $name
 * @return bool
 *
 * @tags Form Token
 * @source SQL_management\connection.php
 */
function check_token($timeout, $referer, $name = '')
{
    if(session_status() == PHP_SESSION_NONE)
        session_start();

    if(isset($_SESSION[$name.'_token']) && isset($_SESSION[$name.'_token_time']) && isset($_POST['token']))
        if($_SESSION[$name.'_token'] == $_POST['token'])
            if($_SESSION[$name.'_token_time'] >= (time() - $timeout))
                if($_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"] == $referer OR 'www.'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"] == $referer)
                    return true;

    return false;
}

/**
 * Get visitor IP address
 *
 * @return string
 *
 * @tags Token
 * @source SQL_management\connection.php
 */
function get_ip() {
    // IP if shared internet
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    // IP if proxy
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // IP normal case
    else {
        return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    }
}

/**
 * Sanitize a string before to use it in a SQL request
 *
 * @param $str
 * @return string
 *
 * @tags Form
 * @source SQL_management\connection.php
 */
function sanitize_string($str) {
    $str = '"'.addslashes($str).'"';
    return ($str);
}