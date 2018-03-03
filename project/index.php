<?php
/**
 * The Index file of PROJECT part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Stock
 * @namespace Stock
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
        -projectHome.php
        -specificProject.php
        -recapitulate.php
        -createNewProject.php
        -workOrder.php
        -scopeOfWork.php
        -ERV.php
        -PIF.php
        */
        $possibilities = array('specificProject','recapitulate','workOrder','ERV');
        if($lvl_access > 2) {
            $possibilities = array_merge($possibilities, array('createNewProject','scopeOfWork','PIF'));

            if($lvl_access > 3) {
                $possibilities = array_merge($possibilities, array('close'));
            }
        }
        if (in_array($currentPage, $possibilities)) {

            if(isset($_GET['excel'])) {
                include('excel/' . $currentPage . '.php');
                exit();
            } else {
                include('action/' . $currentPage . '.php');
            }
        } else {
            include('action/projectHome.php');
        }
    } else {
        include('action/projectHome.php');
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
    $current_choice = 'project';
    include("../ui/sidebar.php"); ?>

    <!------------------------------- Top Menu ------------------------------->
    <?php include("../ui/topmenu.php"); ?>

    <!------------------------------- Content ------------------------------->
    <?php
    if(isset($currentPage) AND isset($possibilities)) {
        if (in_array($currentPage, $possibilities)) {
            include('content/' . $currentPage . '.php');
        } else {
            include('content/projectHome.php');
        }
    } else {
        include('content/projectHome.php');
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