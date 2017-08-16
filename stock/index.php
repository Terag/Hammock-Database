<?php
/**
 * The Index file of STOCK part
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

session_start();
//Create and set var '$UserConnected' to true or false
include('../account/checkConnection.php');

/*HTML Content is send if user is connected*/
if($UserConnected) {

    unset($_SESSION['error']);

    if(!isset($_GET['page']) AND $lvl_access<3) {
        header('Location: /stock/?page=available');
        exit();
    }

    //Include action file to manage forms
    if(isset($_GET['page'])) {
        $currentPage = ($lvl_access < 2)? 'available' : $_GET['page'];


        if(in_array($currentPage, array('stock', 'order')) AND isset($_GET['excel']) AND $lvl_access > 2) {
            include('excel/' . $currentPage . '.php');
            exit();
        }

        /*select the content of the project page.
        Can be :
        -stockHome
        -receive
        -edit
        -available
        -content
        -order
        */
        $possibilities = array('stockHome','receive','edit','available','content','order','vendor');
        if (in_array($currentPage, $possibilities)) {

            if(isset($_GET['excel'])) {
                include('excel/' . $currentPage . '.php');
                exit();
            } else {
                include('action/' . $currentPage . '.php');
            }
        } else {
            include('action/stockHome.php');
        }
    } else {
        include('action/stockHome.php');
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include("../ui/head_default.php"); ?>

        <!-- additional files -->
        <script src="../js/functions.js"></script> <!-- TabFilter JavaScript -->
    </head>

    <body>

    <!------------------------------- Navigation/Sidebar ------------------------------->
    <?php
    $current_choice = 'stock';
    include("../ui/sidebar.php"); ?>

    <!------------------------------- Top Menu ------------------------------->
    <?php include("../ui/topmenu.php"); ?>

    <!------------------------------- Content ------------------------------->
    <?php
    if(isset($currentPage) AND isset($possibilities)) {
        if (in_array($currentPage, $possibilities)) {
            include('content/' . $currentPage . '.php');
        } else {
            include('content/stockHome.php');
        }
    } else {
        include('content/stockHome.php');
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