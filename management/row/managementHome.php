<?php
/**
* managementHome row for MANAGEMENT part
*
* row displaying
*
* @author Victor ROUQUETTE
* @copyright Hammock Helicopter Database by Victor ROUQUETTE
* @license GPL
* @license https://www.gnu.org/licenses/gpl-3.0.fr.html
* @category Part
* @package Management\Row
* @namespace Management
* @filesource
*/
?>

<input name="fe_rw_id" value="<?php echo $recorded_work['RW_ID'];?>" hidden/>
<span class="td"><?php echo $recorded_work['RW_ID'];?></span>
<span class="td">
    <?php
    $href = '<a href="/management/?page=workProjectCalendar&Y='.date('Y').'&M='.date('m').'&D='.date('d').'&id='.$recorded_work['P_ID'].'" class="link">';
    echo $href.(($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['P_NAME'].' &#9658 '.$recorded_work['GST_NUMBER'] : 'DAILY').'</a>';
    ?>
</span>
<span class="td">
    <div <?php echo ($lvl_access > 2) ? 'class="element"':'';?>>
        <?php echo ($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['GST_DESCRIPTION'] : $recorded_work['RW_DESCRIPTION'];?>
    </div>
    <?php if($lvl_access > 2) { ?>
    <div class="element" style="display: none;">
        <?php echo ($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['GST_DESCRIPTION'] : '<input class="form_input" type="text" value="'.$recorded_work['RW_DESCRIPTION'].'" name="fe_rw_description" />';?>
    </div>
    <?php } ?>
</span>
<span class="td">
    <div <?php echo ($lvl_access > 2) ? 'class="element"':'';?>>
        <?php echo min_to_hour($recorded_work['RW_TOTAL_MIN']);?>
    </div>
    <?php if($lvl_access > 2) { ?>
    <div class="element" style="display: none;">
            <input class="form_input" type="number" value="<?php echo floor($recorded_work['RW_TOTAL_MIN']/60);?>" style="width: 40%;" name="fe_rw_tt_h" required/>
            h
            <input class="form_input" type="number" value="<?php echo $recorded_work['RW_TOTAL_MIN']%60;?>" style="width: 40%;" name="fe_rw_tt_m" required/>
    </div>
    <?php } ?>
</span>
<span class="td">
    <div class="element">
        <?php foreach ($data_user_works as $user_work) {
            $time = strtotime($user_work['UW_DATETIME_BEGIN']);
            $href = '<a href="/management/?page=workUserCalendar&Y='.date('Y', $time).'&M='.date('m', $time).'&D='.date('d', $time).'&id='.$user_work['UW_ID_U'].'" class="link">';
            echo (!isset($user_work['UW_DATETIME_END']))? $href.$user_work['U_NAME'].' &#9658 '.$user_work['UW_DATETIME_BEGIN'].'</a><hr style="margin: 1px;"/>' : '';
        } ?>
    </div>
    <div class="element" style="display: none;">
        <?php if($lvl_access > 2) { ?>
        <span class="custom-dropdown custom-dropdown--white" style="margin-top: 1px">
            <?php $users = get_user_list($bdd); ?>
            <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="fe_uw_new_performer">
                <option value="0">Add Performer</option>
                <?php foreach($users as $user) { ?>
                    <option value="<?php echo $user['U_ID'];?>"><?php echo $user['U_NAME'];?></option>
                <?php } ?>
            </select>
        </span>
        <?php } else { ?>
            <input class="form_input" type="checkbox" value="<?php echo $_SESSION['user_id'];?>" style="width: 20px;" name="fe_uw_toggle_performer"/> Begin/Stop Job
        <?php } ?>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php foreach ($data_user_works as $user_work) {
            $time = strtotime($user_work['UW_DATETIME_BEGIN']);
            $href = '<a href="/management/?page=workUserCalendar&Y='.date('Y', $time).'&M='.date('m', $time).'&D='.date('d', $time).'&id='.$user_work['UW_ID_U'].'" class="link">';
            echo (isset($user_work['UW_DATETIME_END']))? $href.$user_work['U_NAME'].' &#9658 '.$user_work['UW_DATETIME_BEGIN']. ' &#9632 '. $user_work['UW_DATETIME_END'].' &#9658 '.min_to_hour($user_work['UW_TOTAL_MIN']).'</a><hr style="margin: 1px;"/>' : '';
        } ?>
    </div>
    <div class="element" style="display: none;">
        <?php $i = 0;
        foreach ($data_user_works as $user_work) {
            if(!isset($user_work['UW_DATETIME_END'])) {
                echo ($lvl_access > 2)? '<b>Close</b> <input type="checkbox" value="<?php echo $user_work[\'UW_ID\'];?>" style="border: none;" name="fe_uw_close_performer_<?php echo $i;?>"/>': '';
                echo $user_work['U_NAME'].' &#9658 '.$user_work['UW_DATETIME_BEGIN'].'<hr style="margin: 1px;"/>';
                $i++;
            }
        } ?>
        <input class="form_input" type="hidden" value="<?php echo $i;?>" name="fe_uw_performer_number" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $recorded_work['RW_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $recorded_work['RW_ID'];?>', 'row-<?php echo $recorded_work['RW_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <?php } ?>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $recorded_work['RW_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>