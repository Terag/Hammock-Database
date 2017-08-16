<?php
/**
 * accountsAdmin content for HOME part
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

if(isset($_SESSION['error'])) {
    display_modal($_SESSION['error']->getMessage());
}

$req = $bdd->query('SELECT * FROM T_USER;');
$data_accounts = $req->fetchAll();
?>

<!-- Form Content -->
<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);console.log('send form');
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>New Account</strong></h2>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_login"><b>Login</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_login" id="f_login" required/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_password"><b>Password</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="password" name="f_password" id="f_password" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%">
                <div class="col-lg-2" style="text-align: right">
                    <label for="f_access">Level Access</label>
                </div>
                <div class="col-lg-4">
                    <span class="custom-dropdown custom-dropdown--white">
                        <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="f_status">
                            <option value="1">View</option>
                            <option value="2">Hangar</option>
                            <option value="3">Office</option>
                            <option value="4">Admin</option>
                        </select>
                    </span>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_code">Certification</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_code"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%">
                <div class="col-lg-12 clearfix">
                    <button type="button" onclick="document.getElementById('id01').style.display='none'" class="button cancelbtn">Cancel</button>
                    <button type="submit" class="button signupbtn">Create</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container content">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 text-center center">
            <h2>Accounts</h2>
            <input type="text" id="myFilterInput" onkeyup="filter(2)" placeholder="Search for names.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:35%;">Login</span>
                    <span class="th" style="width:11%;">Lvl Access</span>
                    <span class="th" style="width:21%;">Password</span>
                    <span class="th" style="width:31%;">Certification</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_accounts as $account) { ?>
                    <form id="delete_row-<?php echo $account['U_ID'];?>" method="post" style="display: none;">
                        <input type="hidden" name="f_delete" value="<?php echo $account['U_ID'];?>" />
                    </form>
                    <form class="tr" id="row-<?php echo $account['U_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $account['U_ID'];?>', 'row-<?php echo $account['U_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                        <?php include('row/accountsAdmin.php');?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 3%;">
        <div class="col-lg-2 col-lg-offset-5">
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">Add Account</button>
            <script>
                // Get the modal
                var modal = document.getElementById('id01');

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>
        </div>
    </div>
</div>