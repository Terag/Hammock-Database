<?php
/**
 * File who includes other parts of code to build and structure the page.
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @filesource
 */

session_start();
//Create and set var '$UserConnected' to true or false
include('account/checkConnection.php');

    /*HTML Content is send if user is connected*/
if($UserConnected) {

    unset($_SESSION['error']);

    /* Include action file to manage forms */
    if(isset($_GET['page'])) {
        $currentPage = $_GET['page'];

        switch ($currentPage) {
            case 'changepassword':
                $current_choice = 'changePWD';
                break;
            case 'accountsAdmin':
                $current_choice = 'admin';
                break;
            default:
                $current_choice = 'home';
                break;
        }
        /*select the content of the home page.
        Can be :
        -defaulthome
        -changepassword
        -ataReference
        -accountsAdmin
        */
        $possibilities = array('changepassword');
        if($lvl_access > 3) {
            $possibilities = array_merge($possibilities, array('accountsAdmin'));
        }
        if (in_array($currentPage, $possibilities)) {
            include('action/' . $currentPage . '.php');
        } else {
            include('action/defaulthome.php');
        }
    } else {
        $current_choice = 'home';
        include('action/defaulthome.php');
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include("ui/head_default.php"); ?>
    </head>

    <body>

    <!------------------------------- Navigation/Sidebar ------------------------------->
    <?php include("ui/sidebar.php"); ?>

    <!------------------------------- Top Menu ------------------------------->
    <?php include("ui/topmenu.php"); ?>

    <!------------------------------- Content ------------------------------->
    <?php /* Include content file to display content */
    if(isset($currentPage) AND isset($possibilities)) {
        if (in_array($currentPage, $possibilities)) {
            include('content/' . $currentPage . '.php');
        } else {
            include('content/defaulthome.php');
        }
    } else {
        include('content/defaulthome.php');
    }
    ?>

    <!------------------------------- Footer ------------------------------->
    <?php include("ui/footer.php"); ?>

    </body>

    </html>
    <?php
} else {
    header('Location: index.php');
    exit();
}
