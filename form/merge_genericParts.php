<?php
/**
 * The Form file merge_genericParts
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
?>

<div id="merge" class="modal">
    <span onclick="document.getElementById('merge').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="merge-row" onsubmit="confirm('Merge : \n' + merge_alert('select_part1', 'select_part2'));" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>Merge Generic Parts</strong></h2>
            <div class="row">
                <div class="col-lg-2" style="text-align: right;">
                    <label><b>Search Part 1</b></label>
                </div>
                <div class="col-lg-4">
                    <input type="text" id="PartFilterInputMerge1" class="formFilterInput" onkeyup="filter_select('PartFilterInputMerge1','select_part1','select_part_element')" placeholder="Search for part.." title="Type in a part" />
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4" style="text-align: right;">
                            <label for="f_gp_id"><b>Select Part 1*</b></label>
                        </div>
                        <div class="col-lg-8">
                            <span class="custom-dropdown custom-dropdown--white">
                                <select class="form_input form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" id="select_part1" name="fm_pn_1" required>
                                    <option value="0">Part 1</option>
                                    <?php /* Get all generic parts */
                                    $req_parts = get_gp_list($bdd);
                                    foreach ($req_parts as $req_part) {
                                        ?>
                                        <option class="select_part_element" value="<?php echo $req_part['GP_ID'];?>"><?php echo $req_part['GP_NAME']." &#9658; ".str_replace(';;',' &#9632 ',$req_part['GP_NUMBER']);?></option>
                                    <?php } ?>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2" style="text-align: right;">
                    <label><b>Search Part 2</b></label>
                </div>
                <div class="col-lg-4">
                    <input type="text" id="PartFilterInputMerge2" class="formFilterInput" onkeyup="filter_select('PartFilterInputMerge2','select_part2','select_part_element')" placeholder="Search for part.." title="Type in a part" />
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4" style="text-align: right;">
                            <label for="f_gp_id"><b>Select Part 2*</b></label>
                        </div>
                        <div class="col-lg-8">
                            <span class="custom-dropdown custom-dropdown--white">
                                <select class="form_input form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" id="select_part2" name="fm_pn_2" required>
                                    <option value="0">Part 2</option>
                                    <?php /* Get all generic parts */
                                    $req_parts = get_gp_list($bdd);
                                    foreach ($req_parts as $req_part) {
                                        ?>
                                        <option class="select_part_element" value="<?php echo $req_part['GP_ID'];?>"><?php echo $req_part['GP_NAME']." &#9658; ".str_replace(';;',' &#9632 ',$req_part['GP_NUMBER']);?></option>
                                    <?php } ?>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 2%">
                <div class="col-lg-12 clearfix">
                    <button type="button" onclick="document.getElementById('merge').style.display='none'" class="button cancelbtn">Cancel</button>
                    <button type="submit" class="button signupbtn">Merge</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    // Get the modal
    var modal_merge = document.getElementById('merge');

    var merge_alert = function(id_select1, id_select2) {
        var select1 = document.getElementById(id_select1);
        var select2 = document.getElementById(id_select2);

        return select1.options[select1.selectedIndex].innerHTML + '\n With \n' + select2.options[select2.selectedIndex].innerHTML;
    }
</script>