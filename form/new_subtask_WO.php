<?php
/**
 * The Form file new_subtask_WO
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
global $data_project;
global $data_engine1;
global $data_engine2;
global $data_manuals_e1;
global $data_manuals_e2;
global $id_wo;
?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>New Task</strong></h2>
            <!-- Hidden Input -->
            <input type="hidden" value="<?php echo $id_wo;?>" name="f_id_wo" required/>
            <!-- User Input -->
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <!-- Var Manual -->
                    <label><b>Manual</b></label>
                </div>
                <div class="col-lg-4">
                    <select class="form_input" id="select_manual" name="f_manual" style="width: 100%;" onclick="filter_select_from_select('select_manual','select_number','select_div')" required>
                        <optgroup label="Helicopter">
                            /* Manuals for H */
                            <?php $data_manuals_h = get_manual_list_for_aircraft($bdd, $data_project['H_ID_GA']);
                            foreach ($data_manuals_h as &$manual) { ?>
                                <option value="<?php echo $manual['M_ID'];?>"><?php echo $manual['M_NAME'];?> - <?php echo $manual['GA_NAME'];?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="Engine1">
                            /* Manuals for E1 */
                            <?php if(isset($data_engine1)) {
                                $data_manuals_e1 = get_manual_list_for_aircraft($bdd, $data_engine1['E_ID_GA']);
                                foreach ($data_manuals_e1 as &$manual) { ?>
                                    <option value="<?php echo $manual['M_ID'];?>"><?php echo $manual['M_NAME'];?> - <?php echo $manual['GA_NAME']; ?></option>
                                <?php }
                            } ?>
                        </optgroup>
                        <optgroup label="Engine2">
                            <?php if(isset($data_engine2)) {
                                /* Manuals for E2 */
                                $data_manuals_e2 = get_manual_list_for_aircraft($bdd, $data_engine2['E_ID_GA']);
                                foreach ($data_manuals_e2 as &$manual) { ?>
                                    <option value="<?php echo $manual['M_ID'];?>"><?php echo $manual['M_NAME'];?> - <?php echo $manual['GA_NAME'];?></option>
                                <?php }
                            } ?>
                        </optgroup>
                    </select>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_number var -->
                    <label for="f_id_gst"><b>Sub Task Index</b></label>
                </div>
                <div class="col-lg-4">
                    <select id="select_number" class="form_input" name="f_id_gst" style="width: 100%;" required>
                        <option value="0">New</option>
                        <optgroup label="Helicopter">
                            <?php foreach ($data_manuals_h as &$manual) {
                                $data_manual_gst = get_gst_list_for_manual($bdd, $manual['M_ID']);
                                foreach ($data_manual_gst as $gst) { ?>
                                    <option class="select_div <?php echo $manual['M_ID'].'-Manual';?>" value="<?php echo $gst['GST_ID'];?>"><?php echo $gst['GST_NUMBER']?> - <?php echo substr($gst['GST_DESCRIPTION'],0,50).' ...';?></option>
                                <?php }
                            } ?>
                        </optgroup>
                        <optgroup label="Engine1">
                            <?php if(isset($data_engine1)) {
                                foreach ($data_manuals_e1 as &$manual) {
                                    $data_manual_gst = get_gst_list_for_manual($bdd, $manual['M_ID']);
                                    foreach ($data_manual_gst as $gst) { ?>
                                        <option class="select_div <?php echo $manual['M_ID'] . '-Manual'; ?>"
                                                value="<?php echo $gst['GST_ID']; ?>"><?php echo $gst['GST_NUMBER'] ?></option>
                                    <?php }
                                }
                            }?>
                        </optgroup>
                        <optgroup label="Engine 2">
                            <?php if(isset($data_engine2)) {
                                foreach ($data_manuals_e2 as &$manual) {
                                    $data_manual_gst = get_gst_list_for_manual($bdd, $manual['M_ID']);
                                    foreach ($data_manual_gst as $gst) { ?>
                                        <option class="select_div <?php echo $manual['M_ID'] . '-Manual'; ?>"
                                                value="<?php echo $gst['GST_ID']; ?>"><?php echo $gst['GST_NUMBER'] ?></option>
                                    <?php }
                                }
                            } ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_reference var -->
                    <label for="f_reference"><b>Reference</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_reference" id="f_reference"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_s_references var -->
                    <label for="f_s_references"><b>Secondary References</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_s_reference" id="f_s_references"/>
                </div>
            </div>
            <!-- To Create a new Sub Task -->
            <p style="margin-top: 2%;">If New Sub Task</p>
            <div class="row" style="border:1px dotted black;padding: 5px;">
                <div class="col-lg-6">
                    <div class="row" style="margin-top:10px;">
                        <div class="col-lg-4" style="text-align: right;">
                            <!-- !!! f_new_number var - text - required -->
                            <label for="f_new_number"><b>Sub Task Index</b> *</label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form_input" type="text" name="f_new_number" id="f_new_number"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_description var - text -->
                    <label for="f_description"><b>Description</b></label>
                </div>
                <div class="col-lg-4">
                    <textarea class="form_input" rows="4" cols="20" name="f_description" id="f_description"></textarea>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_defected var -->
                    <label for="f_required"><b>Defect/Required Work</b></label>
                </div>
                <div class="col-lg-4">
                    <textarea class="form_input" name="f_required" rows="5" cols="25"></textarea>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_rectification var -->
                    <label for="f_rectification"><b>Rectification</b></label>
                </div>
                <div class="col-lg-4">
                    <textarea class="form_input" name="f_rectification" rows="5" cols="25"></textarea>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_s_remove -->
                    <label for="f_s_remove">Removed</label>
                </div>
                <div class="col-lg-4">
                    <textarea class="form_input" name="f_s_remove" rows="5" cols="25"></textarea>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <!-- !!! f_s_install -->
                    <label for="f_s_install">Installed</label>
                </div>
                <div class="col-lg-4">
                    <textarea class="form_input" name="f_s_install" rows="5" cols="25"></textarea>
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