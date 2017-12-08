<?php
/**
 * login for account module
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

ob_start();
// Connection to database
include("../SQL_management/connection.php");

// Get posted information
$pass = sanitize_string($_POST['password']);
$pseudo = sanitize_string($_POST['pseudo']);
$pass_hash = sha1($pass);

var_dump($_POST['pseudo'] . "   =>   " . $pseudo);
var_dump($_POST['password'] . "   =>   " . $pass);

//user identification
$req = $bdd->prepare('SELECT * FROM T_USER WHERE U_NAME = :pseudo');
$req->execute(array(
    'pseudo' => $pseudo));
$datas = $req->fetchAll();
var_dump($datas);
/*if ($datas AND $datas['U_PASSWORD']==$pass_hash)
{
    session_start();
    $_SESSION['user_id'] = $datas['U_ID'];
    $_SESSION['pseudo'] = $datas['U_NAME'];
    $_SESSION['access'] = $datas['U_STATUS'];
    $_SESSION['new'] = TRUE;
    $_SESSION['currentPage'] = 'defaultHome.php';
    $req->closeCursor();

    //header("Location: ../home.php");
    //exit;
}
else
{
    $req->closeCursor();
    //header('Location: ../index.php');
    //exit;
}*/
