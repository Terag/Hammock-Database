<?php
/**
 * documentationHome content for DOCUMENTATION part
 *
 * content file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Documentation\Content
 * @namespace Documentation
 * @filesource
 */

if(isset($_SESSION['error'])) {
    display_modal($_SESSION['error']->getMessage());
}

/*Get $projectList var from database*/
$data_aircrafts = $bdd->query('SELECT * FROM T_GENERIC_AIRCRAFT;')->fetchAll();
?>

<!-- Add Form Content -->
<?php if($lvl_access > 2) { $new_aircraft_form->display_Form(); }?>

<!-- Main Content -->
<div class="container content">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 text-center center">
            <h2>Aircrafts</h2>
            <input type="text" id="myFilterInput" onkeyup="filter(4, 'table')" placeholder="Search for names.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:10%;">ID</span>
                    <span class="th" style="width:30%;">Name</span>
                    <span class="th" style="width:29%;">Constructor</span>
                    <span class="th" style="width:29%;">Type</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach($data_aircrafts as &$aircraft) { ?>
                    <form id="delete_row-<?php echo $aircraft['GA_ID']?>" method="post" style="display: none;">
                        <input type="hidden" name="f_delete" value="<?php echo $aircraft['GA_ID'];?>" />
                    </form>
                    <form class="tr" id="row-<?php echo $aircraft['GA_ID']?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $aircraft['GA_ID'];?>', 'row-<?php echo $aircraft['GA_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                        <?php include('row/documentationHome.php');?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php if($lvl_access > 2) { ?>
    <div class="row" style="margin-top: 3%;">
        <div class="col-lg-2 col-lg-offset-5">
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">New Aircraft</button>
        </div>
    </div>
    <?php } ?>
</div>
