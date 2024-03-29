<?php
/**
 * accountsAdmin row for HOME part
 *
 * row displaying
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Home\Row
 * @namespace Home
 * @filesource
 */
?>
<input type="hidden" value="<?php echo $account['U_ID'];?>" name="fe_u_id" required/>
<span class="td"><?php echo $account['U_NAME'];?></span>
<span class="td">
    <div class="element">
        <?php echo $account['U_STATUS'];?>
    </div>
    <div class="element" style="display: none;">
        <span class="custom-dropdown custom-dropdown--white">
            <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="fe_u_status">
                <?php
                    $access_array = array('', '', '', '');
                    $access_array[$account['U_STATUS']-1] = 'selected="selected"';
                ?>
                <option value="1" <?php echo $access_array[0];?>>View</option>
                <option value="2" <?php echo $access_array[1];?>>Hangar</option>
                <option value="3" <?php echo $access_array[2];?>>Office</option>
                <option value="4" <?php echo $access_array[3];?>>Admin</option>
            </select>
        </span>
    </div>
</span>
<span class="td">
    <div class="element">
        *****
    </div>
    <div class="element" style="display: none;">
        New Password
        <input class="form_input" type="password" name="fe_u_password"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <?php echo $account['U_CODE'];?>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $account['U_CODE'];?>" name="fe_u_code"/>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $account['U_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
</span>
<span class="td">
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $account['U_ID'];?>', 'row-<?php echo $account['U_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $account['U_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
</span>