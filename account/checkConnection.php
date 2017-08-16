<?php
/**
 * checkConnection for account module
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

if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] != null && ($_SESSION['new'] == TRUE))) {
    $UserConnected = true ;
    $lvl_access = $_SESSION['access'];
} else {
    $UserConnected = false ;
    $lvl_access = 0;
}