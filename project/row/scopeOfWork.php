<?php
/**
 * scopeOfWork row for PROJECT part
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

<input name="fe_st_id" value="<?php echo $sub_task['ST_ID'];?>" hidden/>
<span class="td"><?php echo $sub_task['ST_ID'];?></span>
<span class="td">
    <a href="/documentation/?page=documentationSubTask&id=<?php echo $sub_task['GST_ID'];?>" class="link">
        <?php echo $sub_task['GST_NUMBER'];?>
    </a>
</span>
<span class="td">
    <div class="element">
        <a href="/documentation/?page=documentationManual&id=<?php echo $sub_task['M_ID'];?>" class="link">
            <?php echo $sub_task['M_REFERENCE']?>
        </a>
        <?php echo ', '.$sub_task['ST_REFERENCE'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $sub_task['ST_REFERENCE'];?>" name="fe_st_reference"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $sub_task['GST_DESCRIPTION'];?>
    </div>
    <div class="element" style="display: none;">
        <input type="hidden" value="<?php echo $sub_task['GST_ID'];?>" name="fe_gst_id">
        <input class="form_input" type="text" value="<?php echo $sub_task['GST_DESCRIPTION'];?>" name="fe_st_description" />
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $sub_task['ST_APPROVED_DATE'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="date" placeholder="yyyy/mm/jj" value="<?php if($sub_task['ST_APPROVED_DATE']) {echo $sub_task['ST_APPROVED_DATE'];} else {echo date('Y-m-d');};?>" name="fe_st_approved"/>
        <input type="hidden" value="<?php echo $sub_task['ST_APPROVED_DATE'];?>" name="fe_st_old_approved" />
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $sub_task['ST_RELEASE_DATE'];?>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $sub_task['ST_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $sub_task['ST_ID'];?>', 'row-<?php echo $sub_task['ST_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $sub_task['ST_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>