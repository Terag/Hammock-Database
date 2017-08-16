<?php
/**
 * The Form file new_manual
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

$id_ga = &$GLOBALS['id_ga'];
$data_aircraft = &$GLOBALS['data_aircraft'];
?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post" enctype="multipart/form-data">
        <div class="container modal-content animate form-style">
            <!-- Hidden Input -->
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $_SERVER['MAX_FILE_SIZE'];?>"/> <!-- 10Mo -->
            <input type="hidden" name="f_id_ga" value="<?php echo $id_ga;?>"/>
            <!-- User Input -->
            <h2><strong>New Manual for <?php echo $data_aircraft['GA_NAME'];?></strong></h2>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_name"><b>Name</b> *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_name" id="f_name" required/>
                </div>
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_description"><b>Description</b></label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" rows="4" cols="30" name="f_description"/>
                </div>
            </div>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-2" style="text-align: right;">
                    <label for="f_reference"><b>Reference</b> *</label>
                </div>
                <div class="col-lg-4">
                    <input class="form_input" type="text" name="f_reference" id="f_reference" required/>
                </div>
                <div class="col-lg-1" style="text-align: right;">
                    <label for="f_file"><b>File</b></label>
                </div>
                <div class="col-lg-5" style="padding-right: 15px;">
                    <input type="file" name="f_file" id="f_file"/>
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