<?php
/**
 * changePassword for account module
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Account
 * @namespace Account
 * @filesource
 */

session_start();
//Create and set var '$UserConnected' to true or false
include('checkConnection.php');

/*HTML Content is send if user is connected*/
if($UserConnected) {
    include("../SQL_management/connection.php");

    $pass = htmlspecialchars($_POST['password']);
    $pass2 = htmlspecialchars($_POST['password2']);

    if ($pass == $pass2) {
        $pass_hash = sha1($pass);
        $req = $bdd->prepare('CALL change_password(:user_ID, :new_pass_hash);');
        $req->execute(array(
            'new_pass_hash' => $pass_hash,
            'user_ID' =>$_SESSION['user_id']));
        header('Location: ../home.php?page='.$_SESSION['currentPage']);
        exit;
    } else {
        header('Location: ../home.php?page=changepassword');
    }
} else {
    header('Location: ../index.php');
    exit();
}