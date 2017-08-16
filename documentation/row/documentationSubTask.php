<?php
/**
 * documentationSubTask row for DOCUMENTATION part
 *
 * row displaying
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Documentation\Row
 * @namespace Documentation
 * @filesource
 */
?>
<input type="hidden" value="<?php echo $sub_task_part['LGG_ID']?>" name="fe_lgg_id"/>
<span class="td"><?php echo $sub_task_part['LGG_ID'];?></span>
<span class="td"><?php echo str_replace(';;','<br/>',$sub_task_part['GP_NUMBER']);?></span>
<span class="td"><?php echo str_replace(';;','<br/>',$sub_task_part['GP_LOCATION']);?></span>
<span class="td"><?php echo $sub_task_part['GP_NAME'];?></span>
<span class="td">
    <div class="element">
        <?php echo $sub_task_part['LGG_IPC'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $sub_task_part['LGG_IPC'];?>" name="fe_lgg_ipc" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $sub_task_part['LGG_QTY'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="number" min="1" value="<?php echo $sub_task_part['LGG_QTY'];?>" name="fe_lgg_qty" required/>
    </div>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $sub_task_part['LGG_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $sub_task_part['LGG_ID'];?>', 'row-<?php echo $sub_task_part['LGG_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $sub_task_part['LGG_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
    <?php } ?>
</span>