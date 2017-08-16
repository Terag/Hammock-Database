<?php
/**
 * The Index file of STOCK part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Home
 * @namespace Home
 * @filesource
 */

session_start();
//Create and set var '$UserConnected' to true or false
include('account/checkConnection.php');

/*Go to Home page if user is already connected*/
if($UserConnected) {
    header('Location: home.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("ui/head_default.php"); ?>
</head>

<body>

    <!-- ----------------------------- Content ----------------------------- -->
    <div class="container content">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 text-center center">
                <div class="myForm">
                    <img src="img/Logo.svg" style="width: 300px;"/>
                    <h1 id="title">Hammock Database</h1>
                    <form action="account/login.php" method="post">
                        <div class="row">
                            <div class="col-lg-offset-5 col-lg-2">
                                <label for="pseudo"> <Strong> Username : </Strong> </label>
                                <input class="form_input" type="text" name="pseudo" id="pseudo" required/>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-lg-offset-5 col-lg-2">
                                <label for="password"> <Strong> Password  : </Strong></label>
                                <input class="form_input" type="password" name="password" id="password" required/>
                            </div>
                        </div>
                        <div class="row text-center" style="margin-top: 15px;">
                            <input class="button" TYPE="submit" NAME="nom" VALUE="Login" id="Valider">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ----------------------------- Footer ----------------------------- -->
    <?php include("ui/footer.php") ?>

</body>

</html>