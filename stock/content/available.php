<?php
/**
 * Available content for STOCK part
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
$data_available_stock_parts = get_available_stock_list($bdd);

?>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 8%;">
        <div class="col-lg-12 text-center">
            <h2>Stock Available</h2>
            <input type="text" id="myFilterInput" onkeyup="filter(10, 'table')" placeholder="Search.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:3%;">ID</span>
                    <span class="th" style="width:14%;">part NUMBER</span>
                    <span class="th" style="width:10%;">LOCATION</span>
                    <span class="th" style="width:10%;">SERIAL</span>
                    <span class="th" style="width:15%;">DESCRIPTION</span>
                    <span class="th" style="width:5%;">POSSESSED</span>
                    <span class="th" style="width:5%;">AVAILABLE</span>
                    <span class="th" style="width:13%;">ARC</span>
                    <span class="th" style="width:13%;">PO</span>
                    <span class="th" style="width:5%;">UNIT<br/>price</span>
                    <span class="th" style="width:5%;">CURRENCY</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_available_stock_parts as &$stock_part) { ?>
                <form id="delete_row-<?php echo $stock_part['S_ID'];?>" method="post">
                    <input type="hidden" value="<?php echo $stock_part['S_ID'];?>" name="f_delete"/>
                    <input type="hidden" value="<?php echo $stock_part['S_ID_PURCHASE_ORDER'];?>" name="f_delete_po"/>
                    <input type="hidden" value="<?php echo $stock_part['S_ID_ARC'];?>" name="f_delete_arc"/>
                </form>
                <form class="tr" id="row-<?php echo $stock_part['S_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $stock_part['S_ID'];?>', 'row-<?php echo $stock_part['S_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" enctype="multipart/form-data" method="post">
                    <?php include('row/available.php');?>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 3%;">
        <div class="col-lg-2 col-lg-offset-5">
            <a href="./"><button class="button">Back</button></a>
        </div>
    </div>
</div>