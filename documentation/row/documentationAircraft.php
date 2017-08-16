<?php
/**
 * documentationAircraft row for DOCUMENTATION part
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
<input name="fe_m_id" value="<?php echo $manual['M_ID']?>" hidden/>
<span class="td"><a href="?page=documentationManual&id=<?php echo $manual['M_ID'];?>"><?php echo $manual['M_ID'];?></a></span>
<span class="td">
    <div class="element">
        <a href="?page=documentationManual&id=<?php echo $manual['M_ID'];?>"><?php echo $manual['M_NAME'];?></a>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $manual['M_NAME'];?>" name="fe_m_name" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <a href="?page=documentationManual&id=<?php echo $manual['M_ID'];?>"><?php echo $manual['M_REFERENCE'];?></a>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $manual['M_REFERENCE'];?>" name="fe_m_reference" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <a href="?page=documentationManual&id=<?php echo $manual['M_ID'];?>"><?php echo $manual['M_DESCRIPTION'];?></a>
    </div>
    <div class="element" style="display: none;">
        <div class="col-lg-6">
            <input class="form_input" type="text" value="<?php echo $manual['M_DESCRIPTION'];?>" name="fe_m_description"/>
        </div>
        <div class="col-lg-6">
            <input class="form_input" type="file" name="fe_m_file"/>
            <input type="hidden" value="<?php echo $manual['M_ID_MANUAL'];?>" name="fe_m_delete_file">
        </div>
    </div>
</span>
<span class="td">
    <?php if($manual['M_ID_MANUAL'] != NULL) { ?>
        <a href="<?php echo get_file_path($manual['M_ID_MANUAL'],$bdd);?>" target="_blank">
            <button class="btn default" type="button"><i class="fa fa-download"></i></button>
        </a>
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $manual['M_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $manual['M_ID'];?>', 'row-<?php echo $manual['M_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $manual['M_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
    <?php } ?>
</span>