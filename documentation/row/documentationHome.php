<?php
/**
 * documentationHome row for DOCUMENTATION part
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
<input name="fe_ga_id" value="<?php echo $aircraft['GA_ID']?>" hidden/>
<span class="td"><a href="?page=documentationAircraft&id=<?php echo $aircraft['GA_ID']?>"><?php echo $aircraft['GA_ID'];?></a></span>
<span class="td">
    <div class="element">
        <a href="?page=documentationAircraft&id=<?php echo $aircraft['GA_ID']?>"><?php echo $aircraft['GA_NAME'];?></a>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $aircraft['GA_NAME']?>" name="fe_ga_name" required/>
    </div>
</span>
<span class="td">
    <div class="element">
        <a href="?page=documentationAircraft&id=<?php echo $aircraft['GA_ID']?>"><?php echo $aircraft['GA_CONSTRUCTOR'];?></a>
    </div>
    <div class="element" style="display: none;">
        <input class="form_input" type="text" value="<?php echo $aircraft['GA_CONSTRUCTOR'];?>" name="fe_ga_constructor" required/>
    </div>
</span>
<span class="td"><a href="?page=documentationAircraft&id=<?php echo $aircraft['GA_ID']?>"><?php echo $aircraft['GA_TYPE'];?></a></span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn edit" type="button" onclick="edit_row(<?php echo $aircraft['GA_ID'];?>)"><i class="fa fa-pencil"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn add" type="submit"><i class='fa fa-check-square-o'></i></button>
    </div>
    <?php } ?>
</span>
<span class="td">
    <?php if($lvl_access > 2) { ?>
    <div class="element">
        <button class="btn danger" onclick="xmlhttpPost(document.location.href, 'delete_row-<?php echo $aircraft['GA_ID'];?>', 'row-<?php echo $aircraft['GA_ID'];?>', '');return false;" type="button"><i class="fa fa-close"></i></button>
    </div>
    <div class="element" style="display: none;">
        <button class="btn cancel" type="button" onclick="edit_row(<?php echo $aircraft['GA_ID'];?>)"><i class="fa fa-arrow-circle-left"></i></button>
    </div>
    <?php } ?>
</span>