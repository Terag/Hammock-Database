<?php
/**
 * The Form file stock_receive
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

<!-- Modal PN Cross References -->
<?php include $_SERVER['DOCUMENT_ROOT'].'/ui/modal_PNRef.php'; ?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form id="new_row" name="new_row" class="modal-form" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                                   count++;modal.style.display = 'none';return false;" enctype="multipart/form-data" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>Add Part</strong></h2>
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
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-4" style="text-align: right;">
                            <label for="f_gp_id"><b>Select Part *</b></label>
                        </div>
                        <div class="col-lg-8">
                            <select class="form_input" id="select_part" name="f_gp_id" required>
                                <option value="0">New</option>
                                <?php /* Get all generic parts */
                                $req_parts = $bdd->query('SELECT GP_ID, GP_NAME, GP_NUMBER FROM T_GENERIC_PART ORDER BY GP_NAME, GP_NUMBER;');
                                while ($data_parts = $req_parts->fetch()) {
                                    ?>
                                    <option class="select_part_element" value="<?php echo $data_parts['GP_ID'];?>"><?php echo '<b>'.$data_parts['GP_NAME']."</b> - ".str_replace(';;',' - ',$data_parts['GP_NUMBER']);?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1" style="text-align: right;">
                    <label for="f_index_pn">Index</label>
                </div>
                <div class="col-lg-1">
                    <input class="form_input" type="number" min="0" value="0" name="f_index_pn" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_arc_name">ARC (name)</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_arc_name"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_po_name">PO (name) *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_po_name" required/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_arc">ARC</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="file" size="10000000" name="f_arc"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_po">PO</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="file" size="10000000" name="f_po"/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_qty_number">Quantity *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="number" min="1" value="1" name="f_qty_number" required/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_serial">Serial</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_serial"/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_u_price">Price/U</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="number" min="0" step="0.01" value="0" name="f_u_price"/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_currency">Currency</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_currency"/>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-2 col-lg-offset-6" style="text-align: right">
                    <label for="f_vendor">Vendor</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_vendor"/>
                </div>
            </div>
            <p style="margin-top: 25px;">If New Generic Part</p>
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