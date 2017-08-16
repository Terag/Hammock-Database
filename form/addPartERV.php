<?php
/**
 * The Form file addPartERV
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
global $id_wo;
global $data_sub_tasks;
global $req_parts;
?>

<!-- Modal PN Cross References -->
<?php include $_SERVER['DOCUMENT_ROOT'].'/ui/modal_PNRef.php'; ?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <!-- Hidden Input -->
            <input type="hidden" value="<?php echo $id_wo;?>" name="f_id_wo"/>
            <!-- User Input -->
            <h2><strong>Add Part</strong></h2>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_sub_task">Sub Task *</label>
                </div>
                <div class="col-lg-4">
                    <select class="form_input" name="f_sub_task" required>
                        <?php foreach ($data_sub_tasks as $sub_task) { ?>
                            <option value="<?php echo $sub_task['ST_ID'];?>"><?php echo $sub_task['GST_NUMBER'].' - '.substr($sub_task['GST_DESCRIPTION'],0,30).' ..';?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_ipc">IPC *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_ipc" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-6" style="text-align: center;">
                    <input class="form_input" type="checkbox" value="yes" style="width: 20px;" name="f_is_defect" />Discrepency
                </div>
                <div class="col-lg-2" style="text-align: right">
                    <label for="f_quantity_rqd">QTY Rq'D *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="number" min="1" max="1000" value="1" name="f_quantity_rq" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label><b>Search Part</b></label>
                </div>
                <div class="col-lg-3">
                    <input type="text" id="PartFilterInput" class="formFilterInput" onkeyup="filter_select('PartFilterInput','select_part','select_part_element')" placeholder="Search for part.." title="Type in a part" />
                </div>
                <div class="col-lg-1">
                    <button class="btn2 add" id="PN-SEARCH" type="button" onclick="this.disabled = true;$('#pn_modal').toggle();searchPNRef(this);"><i class="fa fa-question" style="width: 50px;padding: 10px 20px 10px 20px;"></i></button>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4" style="text-align: right;">
                            <label for="f_gp_id"><b>Select Part *</b></label>
                        </div>
                        <div class="col-lg-8">
                            <select class="form_input" id="select_part" name="f_gp_id" required>
                                <option value="0">New</option>
                                <?php /* Get all generic parts */
                                $req_parts = get_gp_list($bdd);
                                foreach ($req_parts as $req_part) {
                                    ?>
                                    <option class="select_part_element" value="<?php echo $req_part['GP_ID'];?>"><?php echo $req_part['GP_NAME']." &#9658; ".str_replace(';;',' &#9632 ',$req_part['GP_NUMBER']);?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <p style="margin-top: 2%;">If New Generic Part</p>
            <div class="row" style="border:1px dotted black;padding: 5px;">
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_name">Description *</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_name"/>
                    <hr style="margin: 5px;"/>
                    <label for="f_number">Part Number</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_number"/>
                </div>
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_location">Location</label>
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