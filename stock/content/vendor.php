<?php
/**
 * Vendor content for STOCK part
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

$data_vendors = get_vendor_list($bdd); ?>

<!-- Form Content -->
<?php $new_vendor_form->display_Form();?>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 8%;">
        <div class="col-lg-10 col-lg-offset-1 text-center">
            <h2>Vendors Information</h2>
            <input type="text" id="myFilterInput" onkeyup="filter(8, 'table')" placeholder="Search.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:3%;">ID</span>
                    <span class="th" style="width:15%;">Name</span>
                    <span class="th" style="width:20%;">Address</span>
                    <span class="th" style="width:15%;">City</span>
                    <span class="th" style="width:10%;">Country</span>
                    <span class="th" style="width:10%;">Phone</span>
                    <span class="th" style="width:15%;">Mail</span>
                    <span class="th" style="width:10%;">Contact</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_vendors as &$vendor) { ?>
                    <form id="delete_row-<?php echo $vendor['V_ID'];?>" method="post" style="display: none;">
                        <input type="hidden" name="f_delete" value="<?php echo $vendor['V_ID'];?>" />
                    </form>
                    <form class="tr" id="row-<?php echo $vendor['V_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $vendor['V_ID'];?>', 'row-<?php echo $vendor['V_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                        <?php include('row/vendor.php');?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 3%;">
        <div class="col-lg-2 col-lg-offset-4">
            <a href="./"><button class="button">Back</button></a>
        </div>
        <div class="col-lg-2">
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="button">New Vendor</button>
        </div>
    </div>
</div>
