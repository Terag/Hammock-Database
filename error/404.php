<?php
/**
 * Error 404 page file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Error
 * @namespace Error
 * @filesource
 */

session_start();
//Create and set var '$UserConnected' to true or false
include($_SERVER['DOCUMENT_ROOT'].'/account/checkConnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT']."/ui/head_default.php"); ?>
</head>

<body>

<!------------------------------- Navigation/Sidebar ------------------------------->
<?php include($_SERVER['DOCUMENT_ROOT']."/ui/sidebar.php"); ?>

<?php
/*Add top menu if user is connected*/
if($UserConnected) {
    include($_SERVER['DOCUMENT_ROOT']."/ui/topmenu.php");
 } ?>

<!------------------------------- Content ------------------------------->
<div class="container content">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 text-center center">
            <h1 id="title">404 Page Not Found : Are you lost ?</h1>
            <h2><a href="/index.php">Back Home</a></h2>
            <img src="/img/travolta.gif"/>
        </div>
    </div>
</div>

<!------------------------------- Footer ------------------------------->
<?php include($_SERVER['DOCUMENT_ROOT']."/ui/footer.php") ?>

</body>

</html>