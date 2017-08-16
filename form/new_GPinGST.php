<?php
/**
 * The Form file new_GPinGST
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

global $bdd;
global $data_sub_task;
global $id_gst;
?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <!-- Hidden Input -->
            <input type="hidden" name="f_id_gst" value="<?php echo $id_gst;?>"/>
            <!-- User Input -->
            <h2><strong>Add Part</strong></h2>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label><b>Search Part</b></label>
                </div>
                <div class="col-lg-4">
                    <input type="text" id="PartFilterInput" class="formFilterInput" onkeyup="filter_select('PartFilterInput','select_part','select_part_element')" placeholder="Search for part.." title="Type in a part" />
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4" style="text-align: right;">
                            <label for="f_gp_id"><b>Select Part</b></label>
                        </div>
                        <div class="col-lg-8">
                            <select class="form_input" id="select_part" name="f_id_gp" style="width: 100%;" required>
                                <option value="0">New</option>
                                <?php /* Get all generic parts */
                                $req_parts = $bdd->query('SELECT GP_ID, GP_NAME, GP_NUMBER FROM T_GENERIC_PART');
                                while ($data_parts = $req_parts->fetch()) { ?>
                                    <option class="select_part_element" value="<?php echo $data_parts['GP_ID'];?>"><?php echo "<b>".$data_parts['GP_NAME']."</b> - ".str_replace(';;',' - ',$data_parts['GP_NUMBER']);?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 2%">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_qty">Quantity *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="number" min="1" name="f_qty" required/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_ipc">IPC *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_ipc" required/>
                </div>
            </div>
            <p style="margin-top: 2%;">If New Generic Part</p>
            <div class="row" style="border:1px dotted black;padding: 5px;">
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_name">Description *</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_name"/>
                    <hr style="margin: 5px;"/>
                    <label for="f_number">Number</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_number"/>
                </div>
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_type">Type</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_type"/>
                    <hr style="margin: 5px;"/>
                    <label for="f_type">Location</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_location"/>
                    <hr style="margin: 5px;"/>
                    <label for="f_description"><b>Note</b></label>
                    <hr style="margin: 1px;"/>
                    <textarea class="form_input" name="f_description" rows="5" cols="25"></textarea>
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