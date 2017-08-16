<?php
/**
 * Edit content for STOCK part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Stock\Content
 * @namespace Stock
 * @filesource
 */

if(isset($_SESSION['error'])) {
    display_modal($_SESSION['error']->getMessage());
}

/*Get $data_stock_parts var from database*/
$data_generic_parts = get_gp_list($bdd); ?>

<!-- Form Content -->
<?php $new_generic_part_form->display_Form();?>

<!-- Merge form -->
<?php if($lvl_access>3) {$merge_generic_parts->display_Form();}?>

<!-- Information Box -->
<div id="info_modal" class="modal">
    <div class="container modal-content animate form-style">
        <span onclick="document.getElementById('info_modal').style.display='none'" class="close" title="Close Modal">×</span>
        <h2><strong>References helper</strong></h2>
        <div class="row" style="margin-top: 15px;">
            <div class="col-lg-12">
                <p>
                    <b>Please use ;; to separate elements in :</b>
                </p>
                <p>- Location</p>
                <p>- Part Numbers</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 8%;">
        <div class="col-lg-10 col-lg-offset-1 text-center">
            <h2>Generic Parts Information</h2>
            <div class="row" style="margin-top: 1%;margin-bottom: 1%;">
                <div class="col-lg-2 col-lg-offset-3">
                    <a href="./"><button class="button">Back</button></a>
                </div>
                <div class="col-lg-2">
                    <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">New Part</button>
                </div>
                <?php if($lvl_access>3){;?>
                <div class="col-lg-2">
                    <button class="button" onclick="document.getElementById('merge').style.display='block'" type="submit">Merge</button>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-lg-11">
                    <input type="text" id="myFilterInput" onkeyup="filter(5, 'table')" placeholder="Search.." title="Type in a name" />
                </div>
                <div class="col-lg-1">
                    <button class="btn2 add" type="button" onclick="$('#info_modal').toggle();"><i class="fa fa-question" style="width: 50px;padding: 10px 20px 10px 20px;"></i></button>
                </div>
            </div>
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:3%;">ID</span>
                    <span class="th" style="width:25%;">Part Number</span>
                    <span class="th" style="width:20%;">Location</span>
                    <span class="th" style="width:20%;">Description</span>
                    <span class="th" style="width:30%;">Note</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_generic_parts as &$generic_part) { ?>
                    <form id="delete_row-<?php echo $generic_part['GP_ID'];?>" method="post" style="display: none;">
                        <input type="hidden" name="f_delete" value="<?php echo $generic_part['GP_ID'];?>" />
                    </form>
                    <form class="tr" id="row-<?php echo $generic_part['GP_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $generic_part['GP_ID'];?>', 'row-<?php echo $generic_part['GP_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" action="?page=edit" method="post">
                        <?php include('row/edit.php');?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>