<?php
/**
 * Content row for STOCK part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Stock\Row
 * @namespace Stock
 * @filesource
 */
?>
<input name="fe_s_id" value="<?php echo $stock_part['S_ID'];?>" hidden/>
<span class="td"><?php echo $stock_part['S_ID'];?></span>
<span class="td">
    <?php
        $part_numbers = explode(';;', $stock_part['GP_NUMBER']);
    $index = ($stock_part['S_INDEX_PN'] > 0 AND $stock_part['S_INDEX_PN'] < count($part_numbers))? $stock_part['S_INDEX_PN'] : 0;
    $part_numbers[$index] = '<b>'.$part_numbers[$index].'</b>';
    echo $part_numbers[0];
    for($i=1;$i<count($part_numbers);$i++) {
        echo '<br/>'.$part_numbers[$i];
    }
    ?>
    <div class="element" style="display: none;">
        <div class="col-lg-4">
            Index
        </div>
        <div class="col-lg-8">
            <input class="form_input" type="number" min="0" value="<?php echo $stock_part['S_INDEX_PN'];?>" name="fe_s_index_pn" required/>
        </div>
    </div>
</span>
<span class="td"><?php echo str_replace(';;','<br/>',$stock_part['GP_LOCATION']);?></span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_SERIAL'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $stock_part['S_SERIAL'];?>" name="fe_s_serial"/>
    </div>
</span>
<span class="td"><?php echo $stock_part['GP_NAME'];?></span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_QUANTITY_NUMBER'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="number" min="<?php echo max($stock_part['S_QUANTITY_USED'],1);?>" max="1000" value="<?php echo $stock_part['S_QUANTITY_NUMBER'];?>" name="fe_s_qty"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php if($stock_part['S_ID_ARC'] != NULL) { ?>
            <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($stock_part['S_ID_ARC'],$bdd);?>')" type="button">
                <i class="fa fa-download"></i>
            </button>
        <?php } ?>
        <?php echo $stock_part['S_ARC_NAME'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $stock_part['S_ARC_NAME'];?>" name="fe_arc_name" required/>
        <input class="form_input" type="file" size="10000000" name="fe_f_arc"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php if($stock_part['S_ID_PURCHASE_ORDER'] != NULL) { ?>
            <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($stock_part['S_ID_PURCHASE_ORDER'],$bdd);?>')" type="button">
                <i class="fa fa-download"></i>
            </button>
        <?php } ?>
        <?php echo $stock_part['S_PO_NAME'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $stock_part['S_PO_NAME'];?>" name="fe_po_name" required/>
        <input class="form_input" type="file" size="10000000" name="fe_f_po"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_RECEIVED_DATE'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="date" value="<?php echo $stock_part['S_RECEIVED_DATE'];?>" name="fe_s_rcvd_date" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_EXPIRATION_DATE'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="date" value="<?php echo $stock_part['S_EXPIRATION_DATE'];?>" name="fe_s_expi_date"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_PRICE'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="number" step="0.01" min="0" value="<?php echo $stock_part['S_PRICE'];?>" name="fe_s_price"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $stock_part['S_ACCURENCY'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $stock_part['S_ACCURENCY'];?>" name="fe_s_accurency"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $stock_part['S_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $stock_part['S_ID'];?>', 'row-<?php echo $stock_part['S_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $stock_part['S_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>