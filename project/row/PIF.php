<?php
/**
 * PIF row for PROJECT part
 *
 * row displaying
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Project\Row
 * @namespace Project
 * @filesource
 */
?>

<!-- Hidden Input -->
<input type="hidden" value="<?php echo $project_part['LPS_ID_PP'];?>"name="fe_lps_pp_id"/>
<input type="hidden" value="<?php echo $project_part['LPS_ID_S'];?>" name="fe_lps_s_id"/>
<!-- User Input -->
<span class="td"><?php echo $project_part['LPS_ID'];?></span>
<span class="td">
    <a href="/documentation/?page=documentationSubTask&id=<?php echo $project_part['GST_ID'];?>" class="link">
        <?php echo $project_part['GST_NUMBER'];?>
    </a>
</span>
<span class="td"><?php echo $project_part['GP_NAME'];?></span>
<span class="td">
    <?php
        $part_numbers = explode(';;', $project_part['GP_NUMBER']);
        $index = ($project_part['S_INDEX_PN'] > 0 AND $project_part['S_INDEX_PN'] < count($part_numbers))? $project_part['S_INDEX_PN'] : 0;
        echo $part_numbers[$index];
    ?>
</span>
<span class="td"><?php echo $project_part['PP_IPC'];?></span>
<span class="td">
    <?php if(isset($project_part['S_ID_ARC'])) {
        $data_arc_file = get_file_info($bdd, $project_part['S_ID_ARC']); ?>
        <button class="btn default" onclick="openInNewTab('<?php echo $data_arc_file['F_DIRECTORY'].'/'.$data_arc_file['F_NAME'].'.'.$data_arc_file['F_FORMAT'];?>')" formtarget="_blank" type="button">
            <i class="fa fa-download"></i>
        </button>
    <?php }
    echo $project_part['S_ARC_NAME']; ?>
</span>
<span class="td">
    <?php if(isset($project_part['S_ID_PURCHASE_ORDER'])) {
        $data_po_file = get_file_info($bdd, $project_part['S_ID_PURCHASE_ORDER']); ?>
        <button class="btn default" onclick="openInNewTab('<?php echo $data_po_file['F_DIRECTORY'].'/'.$data_po_file['F_NAME'].'.'.$data_po_file['F_FORMAT'];?>')" formtarget="_blank" type="button">
            <i class="fa fa-download"></i>
        </button>
    <?php }
    echo $project_part['S_PO_NAME']; ?>
</span>
<span class="td"><?php echo $project_part['PP_QUANTITY_REQUESTED'];?></span>
<span class="td">
    <div class="element">
        <?php echo $project_part['LPS_QUANTITY_NUMBER'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="number" min="0" max="<?php echo $project_part['LPS_QUANTITY_NUMBER'];?>" value="<?php echo $project_part['LPS_QUANTITY_NUMBER'];?>" name="fe_lps_qty_dlv"/>
    </div>
</span>
<span class="td"><?php echo $project_part['S_PRICE'];?></span>
<span class="td"><?php echo $project_part['S_ACCURENCY'];?></span>
<span class="td">
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $project_part['LPS_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $project_part['LPS_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>