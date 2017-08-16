<?php
/**
 * Receive content for STOCK part
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

/*Get $data_ordered_parts var from database*/
$data_ordered_parts = get_ordered_parts($bdd);
?>


<!-- Form Content -->
<?php $new_received_part->display_Form();?>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 8%;">
        <div class="col-lg-12 text-center">
            <h2>In Delivery</h2>
            <div class="row" style="margin-top: 1%;margin-bottom: 1%;">
                <div class="col-lg-2 col-lg-offset-4">
                    <a href="./"><button class="button">Back</button></a>
                </div>
                <div class="col-lg-2">
                    <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">Add Part</button>
                </div>
            </div>
            <input type="text" id="myFilterInput" onkeyup="filter(9, 'table')" placeholder="Search.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:3%;">ID</span>
                    <span class="th" style="width:10%;">Part Number</span>
                    <span class="th" style="width:5%;">Serial</span>
                    <span class="th" style="width:7%;">Description</span>
                    <span class="th" style="width:4%;">Location</span>
                    <span class="th" style="width:7%;">Quantity</span>
                    <span class="th" style="width:14%;">ARC</span>
                    <span class="th" style="width:15%;">PO</span>
                    <span class="th" style="width:8%;">Unit<br/>Price</span>
                    <span class="th" style="width:5%;">Currency</span>
                    <span class="th" style="width:8%;">Vendor</span>
                    <span class="th" style="width:8%;"></span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_ordered_parts as &$ordered_part) { ?>
                    <form id="delete_row-<?php echo $ordered_part['S_ID'];?>" method="post" style="display: none;">
                        <input type="hidden" value="<?php echo $ordered_part['S_ID_PURCHASE_ORDER'];?>" name="f_delete_po"/>
                        <input type="hidden" value="<?php echo $ordered_part['S_ID_ARC'];?>" name="f_delete_arc"/>
                        <input type="hidden" value="<?php echo $ordered_part['S_ID'];?>" name="f_delete"/>
                    </form>
                    <form class="tr" id="row-<?php echo $ordered_part['S_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $ordered_part['S_ID'];?>', 'row-<?php echo $ordered_part['S_ID'];?>', '<td><img src=\'/img/wait_rot.gif\'/></td>');return false;" enctype="multipart/form-data" method="post">
                        <?php include('row/receive.php');?>
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
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">Add Part</button>
        </div>
    </div>
</div>