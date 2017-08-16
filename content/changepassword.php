<?php
/**
 * changepassword content for HOME part
 *
 * content file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Home\Content
 * @namespace Home
 * @filesource
 */

if(isset($UserConnected) AND $UserConnected){ ?>
<div class="container content">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 text-center center">
            <div class="myForm">
                <h2> <strong> Change password</strong></h2>
                <form class="chatform" method="post" action="../account/changePassword.php">
                    <div class="row">
                        <div class="col-lg-offset-5 col-lg-2">
                            <label for="pseudo"> <strong> New password :</strong> </label>
                            <input class="form_input" type="password" name="password" id="password" required/>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-lg-offset-5 col-lg-2">
                            <label for="pseudoConfirm"> <strong> Confirm new password : </strong></label>
                            <input class="form_input" type="password" name="password2" id="password2" required/>
                        </div>
                    </div>
                    <div class="row text-center" style="margin-top: 15px;">
                        <input class="button" type="submit" value="Change password" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } else {
    header('Location: /index.php');
    exit();
}