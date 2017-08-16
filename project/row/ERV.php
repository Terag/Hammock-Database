<?php
/**
 * ERV row for PROJECT part
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
<input type="hidden" value="<?php echo $project_part['PP_ID'];?>" name="fe_pp_id"/>
<input type="hidden" value="<?php echo $project_part['PP_ID_GP'];?>" name="fe_pp_id_gp"/>
<span class="td" <?php echo ($project_part['PP_DEFECT']=='yes')? 'style="background-color: pink;"': '';?>><?php echo $project_part['PP_ID'];?></span>
<span class="td" <?php echo ($project_part['PP_DEFECT']=='yes')? 'style="background-color: pink;"': '';?>>
    <div class="element">
        <a href="/documentation/?page=documentationSubTask&id=<?php echo $project_part['GST_ID'];?>" class="link">
            <?php echo $project_part['GST_NUMBER'];?>
        </a>
    </div>
    <div class="element" style="display: none;">
        <select class="form_input" name="fe_pp_st_id">
            <option value="<?php echo $project_part['ST_ID']?>" title="<?php echo $project_part['GST_DESCRIPTION']?>"><?php echo $project_part['GST_NUMBER'].' - '.substr($project_part['GST_DESCRIPTION'],0,40).' ..';?></option>
            <?php foreach ($data_sub_tasks as $sub_task) {
                if(isset($sub_task['ST_APPROVED_DATE'])) { ?>
                    <option value="<?php echo $sub_task['ST_ID']?>" title="<?php echo $sub_task['GST_DESCRIPTION']?>"><?php echo $sub_task['GST_NUMBER'].' - '.substr($sub_task['GST_DESCRIPTION'],0,40).' ..';?></option>
                <?php }
            } ?>
            <br/>
            <input class="form_input" type="checkbox" value="yes" style="width: 20px;" name="fe_pp_is_defect" <?php echo ($project_part['PP_DEFECT']=='yes')? 'checked="checked"': '';?>/> Is Discrepency
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo str_replace(';;','<br/>',$project_part['GP_NUMBER']);?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $project_part['GP_NUMBER'];?>" name="fe_pp_gp_number"/>
    </div>
</span>
<span class="td">
    <?php echo str_replace(';;','<br/>',$project_part['GP_LOCATION']);?>
</span>
<span class="td">
    <?php echo $project_part['GP_NAME'];?>
</span>
<span class="td">
    <div class="element">
        <?php echo $project_part['PP_IPC'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $project_part['PP_IPC'];?>" name="fe_pp_ipc"/>
    </div>
</span>
<span class="td">
    <?php $data_user = get_user_info($bdd, $project_part['PP_ID_USER']);
    if(isset($data_user)) { ?>
        <div <?php if($lvl_access>2) { echo 'class="element"';}?>>
            <?php echo $data_user['U_NAME'];?>
        </div>
    <?php } ?>
    <div class="element" style="display: none;">
        <?php if($lvl_access>2) { ?>
            <select class="form_input" name="fe_pp_user">
                <?php if(isset($data_user)); { ?>
                    <option value="<?php echo $data_user['U_ID'];?>"><?php echo $data_user['U_NAME'];?></option>
                <?php }
                $data_users = get_user_list($bdd);
                foreach ($data_users as $user) { ?>
                    <option value="<?php echo $user['U_ID'];?>"><?php echo $user['U_NAME'];?></option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <input type="hidden" value="<?php echo $data_user['U_ID'];?>" name="fe_pp_user"/>
        <?php } ?>
    </div>
</span>
<span class="td">
    <div <?php if($lvl_access>2) { echo 'class="element"';}?>>
        <?php echo $project_part['PP_REQUESTED_DATE'];?>
    </div>
    <?php if($lvl_access>2) { ?>
        <div class="element" style="display:none;">
            <input class="form_input" type="date" placeholder="aaaa/mm/jj" value="<?php echo $project_part['PP_REQUESTED_DATE'];?>" name="fe_pp_requested_date"/>
        </div>
    <?php } else { ?>
        <input type="hidden" value="<?php echo $project_part['PP_REQUESTED_DATE'];?>" name="fe_pp_requested_date" />
    <?php } ?>
</span>
<span class="td">
    <div class="element">
        <?php echo $project_part['PP_QUANTITY_REQUESTED'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="number" min="1" max="1000" value="<?php echo $project_part['PP_QUANTITY_REQUESTED'];?>" name="fe_pp_quantity_requested"/>
    </div>
</span>
<span class="td"><?php echo $project_part['LPS_QUANTITY_USED'];?></span>
<span class="td">
    <?php $i = 0;
    $data_stock_available = get_stock_available_list_by_po_from_gp_id($bdd, $project_part['GP_ID']);
    $part_number_list = explode(';;', $project_part['GP_NUMBER']);
    $part_locations = explode(';;', $project_part['GP_LOCATION']);
    foreach ($data_stock_available as $available_part) {
        $index = ($available_part['S_INDEX_PN'] > 0 AND $available_part['S_INDEX_PN'] < count($part_number_list) AND $available_part['S_INDEX_PN'] < count($part_locations))? $available_part['S_INDEX_PN'] : 0;

        $link_stock_pp = get_lps_for_pp_s_id($bdd, $project_part['PP_ID'], $available_part['S_ID']);

        if (($available_part['S_QUANTITY_AVAILABLE'] > 0 OR $link_stock_pp['LPS_QUANTITY_NUMBER'] > 0) AND $available_part['S_RECEIVED'] == 'yes') {
            $i++;?>
            <p style="margin: 1px;">
                <?php echo $link_stock_pp['LPS_QUANTITY_NUMBER'].' ['.$available_part['S_QUANTITY_AVAILABLE'] . '] &#8592; <b>' . $available_part['S_PO_NAME'].'</b> ('.$part_number_list[$index].' <i>['.$part_locations[$index].'</i>])';
                if($lvl_access>2) { ?>
                    <input type="hidden" value="<?php echo $available_part['S_ID']?>" name="fe_pp_s_id_<?php echo $i;?>">
                    <input class="form_input element" type="number" min="0" max="<?php echo $project_part['PP_QUANTITY_REQUESTED'];?>" value="<?php echo max(0, $link_stock_pp['LPS_QUANTITY_NUMBER']);?>" style="display:none;" name="fe_pp_qty_dlv_<?php echo $i?>"/>
                <?php } ?>
            </p>
            <hr style="margin: 1px;"/>
        <?php } else if($available_part['S_QUANTITY_AVAILABLE'] > 0 AND $available_part['S_RECEIVED'] == 'no') { ?>
            <p style="margin: 1px;color: gray;">
                <?php echo $available_part['S_QUANTITY_AVAILABLE'] . ' &#8592; <b>' . $available_part['S_PO_NAME'].'</b> ('.$part_number_list[$index].')'; ?>
            </p>
            <hr style="margin: 1px;"/>
        <?php } ?>
    <?php } ?>
    <input type="hidden" value="<?php echo $i;?>" name="fe_pp_i">
</span>
<span class="td">
    <?php if($lvl_access>2) { ?>
        <div class="element">
            <button class="btn add" type="button" onclick="console.log('PUSH');$('#creator').show();creator.add_row_GP(<?php echo $project_part['GP_ID'].',\''.$project_part['PP_IPC'].'\','.$project_part['PP_QUANTITY_REQUESTED'].',\''.$project_part['GST_NUMBER'].'\'';?>);"><i class="fa fa-shopping-cart"></i></button>
        </div>
        <div class="element" style="display: none;">
            <input<?php if($project_part['PP_VALIDATED']=='yes'){echo ' checked="checked"';}?> class="form_input" style="width: 20%;" type="checkbox" value="yes" name="fe_pp_validated" /> Validated
        </div>
    <?php } else { ?>
        <input type="hidden" value="<?php $project_part['PP_VALIDATED'];?>" name="fe_pp_validated">
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access>2 OR $project_part['PP_VALIDATED'] != 'yes') { ?>
        <div class="element">
            <button class="btn edit" type="button" onclick="edit_row(<?php echo $project_part['PP_ID'];?>);"><i class="fa fa-pencil"></i></button>
        </div>
        <div class="element" style="display: none;">
            <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
        </div>
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access>2 OR $project_part['PP_VALIDATED'] != 'yes') { ?>
        <div class="element">
            <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $project_part['PP_ID'];?>', 'row-<?php echo $project_part['PP_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
        </div>
        <div class="element" style="display: none;">
            <button class="btn cancel" type="button" onclick="edit_row(<?php echo $project_part['PP_ID'];?>);"><i class="fa fa-arrow-circle-left"></i></button>
        </div>
    <?php } ?>
</span>