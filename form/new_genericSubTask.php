<?php
/**
 * The Form file new_genericSubTask
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
global $data_manual;
global $id_m;
?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post" enctype="multipart/form-data">
        <div class="container modal-content animate form-style">
            <!-- Hidden Input -->
            <input type="hidden" name="f_id_m" value="<?php echo $id_m;?>"/>
            <!-- User Input -->
            <h2><strong>New Sub Task for <?php echo $data_manual['M_NAME'];?></strong></h2>
            <div class="row" style="margin-top: 2%;">
                <div class="col-lg-6">
                    <div class="row">
                        <br/>
                        <div class="col-lg-4" style="text-align: right;">
                            <!-- !!! f_number var - text - required -->
                            <label for="f_number"><b>Sub Task Index</b> *</label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form_input" type="text" name="f_number" id="f_number" required/>
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