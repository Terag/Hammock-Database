<?php
/**
 * workUserCalendar content for MANAGEMENT part
 *
 * content file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Management\Content
 * @namespace Management
 * @filesource
 */

$user_name = get_user_info($bdd, $id);

if(isset($_SESSION['error'])) {
    display_modal($_SESSION['error']->getMessage());
}

$previous_month = (($month-1)%12==0)? 'Y='.($year-1).'&M=12': 'Y='.$year.'&M='.($month-1);
$next_month = (($month+1)%12==1)? 'Y='.($year+1).'&M=1': 'Y='.$year.'&M='.($month+1)

?>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 5%;">
        <h2 style="text-align: center;"><?php echo $user_name['U_NAME'];?></h2>
        <h2 style="text-align: center;">
            <a href="?page=workUserCalendar&<?php echo $previous_month;?>&D=15&id=<?php echo $id;?>">&#9668;</a>
            <?php echo _MONTH::toString($month).' - '.$year;?>
            <a href="?page=workUserCalendar&<?php echo $next_month;?>&D=15&id=<?php echo $id;?>">&#9658;</a>
        </h2>
        <?php if(isset($calendar)) $calendar->display();?>
    </div>
</div>