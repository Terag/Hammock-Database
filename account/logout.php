<?php
/**
 * logout for account module
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

// Suppression des variables de session et de la session
$_SESSION['pseudo']=null;

session_destroy();

// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');

header('Location: ../index.php');
exit();