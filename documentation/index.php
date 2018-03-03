<?php
/**
 * The Index file of DOCUMENTATION part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Documentation
 * @namespace Documentation
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
        -documentationHome
        -documentationAircraft
        -documentationManual
        -documentationSubTask
        -documentationPart
        */
        $possibilities = array('documentationHome','documentationAircraft','documentationManual','documentationSubTask');
        if (in_array($currentPage, $possibilities)) {
            include('action/' . $currentPage . '.php');
        } else {
            include('action/documentationHome.php');
        }
    } else {
        include('action/documentationHome.php');
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
    $current_choice = 'documentation';
    include("../ui/sidebar.php"); ?>

    <!------------------------------- Top Menu ------------------------------->
    <?php include("../ui/topmenu.php"); ?>

    <!------------------------------- Content ------------------------------->
    <?php
    if(isset($currentPage) AND isset($possibilities)) {
        if (in_array($currentPage, $possibilities)) {
            include('content/' . $currentPage . '.php');
        } else {
            include('content/documentationHome.php');
        }
    } else {
        include('content/documentationHome.php');
    }
    ?>

    <!------------------------------- Footer ------------------------------->
    <?php include("../ui/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header('Location: ../index.php');
    exit();
}