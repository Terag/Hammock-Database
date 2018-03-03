<?php
/**
 * The Index file of MANAGEMENT part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Management
 * @namespace Management
 * @filesource
 */

include('../config.php');
session_start();
//Create and set var '$UserConnected' to true or false
include('../account/checkConnection.php');
/*HTML Content is send if user is connected*/
if($UserConnected) {

    unset($_SESSION['error']);

    //Include action file to manage forms
    if(isset($_GET['page'])) {
        $currentPage = $_GET['page'];
        /*select the content of the project page.
        Can be :
        -managementHome
        */
        $possibilities = array('managementHome','workUserCalendar');
        if($lvl_access > 2) {
            $possibilities = array_merge($possibilities, array('workProjectCalendar'));
        }
        if (in_array($currentPage, $possibilities)) {
            include('action/' . $currentPage . '.php');
        } else {
            include('action/managementHome.php');
        }
    } else {
        include('action/managementHome.php');
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include("../ui/head_default.php"); ?>
    </head>

    <body>

    <!------------------------------- Navigation/Sidebar ------------------------------->
    <?php
    $current_choice = 'management';
    include("../ui/sidebar.php"); ?>

    <!------------------------------- Top Menu ------------------------------->
    <?php include("../ui/topmenu.php"); ?>

    <!------------------------------- Content ------------------------------->
    <?php
    if(isset($currentPage) AND isset($possibilities)) {
        if (in_array($currentPage, $possibilities)) {
            include('content/' . $currentPage . '.php');
        } else {
            include('content/managementHome.php');
        }
    } else {
        include('content/managementHome.php');
    }
    ?>

    <!------------------------------- Footer ------------------------------->
    <?php include("../ui/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header('Location: /index.php');
    exit();
}