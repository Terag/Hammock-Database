<?php
/**
 * The Form file new_vendor
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Form
 * @namespace Form
 * @filesource
 */

global $data_project;
global $id_project;
?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form id="new_row" class="modal-form" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>New Vendor</strong></h2>
            <!-- User Input -->
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_name"><b>Name</b> *</label>
                </div>
                <div class="col-lg-10">
                    <input class="form_input" type="text" name="f_name"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_address"><b>Adress</b></label>
                </div>
                <div class="col-lg-10">
                    <input class="form_input" type="text" name="f_address"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_city"><b>City</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_city"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_country"><b>Country</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_country"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_phone"><b>Phone</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_phone"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_contact"><b>Contact</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_contact"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_mail"><b>Mail</b></label>
                </div>
                <div class="col-lg-10">
                    <input class="form_input" type="text" name="f_mail"/>
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
<script>
    // Get the modal
    var modal = document.getElementById('id01');
</script>