<?php
/**
 * The Form file new_genericPart
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

<!-- Modal PN Cross References -->
<?php include $_SERVER['DOCUMENT_ROOT'].'/ui/modal_PNRef.php'; ?>

<div id="id01" class="modal">
    <script>var count = 0;</script>
    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-form" id="new_row" onsubmit="xmlhttpPost(document.location.href, 'new_row', 'table', '', true);
                                                    count++;modal.style.display = 'none';return false;" method="post">
        <div class="container modal-content animate form-style">
            <h2><strong>New Generic Part</strong></h2>
            <div class="row" style="border:1px dotted black;padding: 5px;">
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_name">Description *</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_name" />
                    <hr style="margin: 5px;"/>
                    <label for="f_number">Part Number</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" id="PartFilterInput" type="text" name="f_number" />
                    <hr style="margin: 1px;"/>
                    <button class="btn2 add" id="PN-SEARCH" type="button" onclick="this.disabled = true;$('#pn_modal').toggle();searchPNRef(this);"><i class="fa fa-question" style="width: 50px;padding: 10px 20px 10px 20px;"></i></button>
                    Check Cross PN References
                </div>
                <div class="col-lg-6" style="text-align: left;">
                    <label for="f_location">Location</label>
                    <hr style="margin: 1px;"/>
                    <input class="form_input" type="text" name="f_location" />
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