<?php
/**
 * Edit row for STOCK part
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
<input type="hidden" value="<?php echo $generic_part['GP_ID'];?>" name="fe_gp_id"/>
<span class="td"><?php echo $generic_part['GP_ID'];?></span>
<span class="td">
    <div class="element">
        <?php
            $pn_list = explode(';;',$generic_part['GP_NUMBER']);
            foreach ($pn_list as $pn) {
                echo '<span class="link" onclick="this.disabled = true;$(\'#pn_modal\').toggle();searchPNRef(this, \''.$pn.'\');">'.$pn.'</span><br/>';
            }
        ?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $generic_part['GP_NUMBER'];?>" name="fe_gp_number"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo str_replace(';;','<br/>',$generic_part['GP_LOCATION']);?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $generic_part['GP_LOCATION'];?>" name="fe_gp_location"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $generic_part['GP_NAME'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $generic_part['GP_NAME'];?>" name="fe_gp_name" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $generic_part['GP_DESCRIPTION'];?>
    </div>
    <div class="element" style="display:none;">
        <input class="form_input" type="text" value="<?php echo $generic_part['GP_DESCRIPTION'];?>" name="fe_gp_description">
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $generic_part['GP_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $generic_part['GP_ID'];?>', 'row-<?php echo $generic_part['GP_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $generic_part['GP_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>
