<?php
/**
 * The Form file new_genericAircraft
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
?>
<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>New Aircraft</strong></h2>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_name"><b>Name</b> *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_name" id="f_name" required/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_constructor"><b>Constructor</b> *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_constructor" id="f_constructor" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_type"><b>Type</b> *</label>
                </div>
                <div class="col-lg-4">
                    <select class="form_input" name="f_type"  style="width: 100%;" required>
                        <option value="helico">Helicopter</option>
                        <option value="engine">Engine</option>
                    </select>
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