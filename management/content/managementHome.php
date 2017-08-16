<?php
/**
 * managementHome content for MANAGEMENT part
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

$data_recorded_works = get_recorded_work_list($bdd);

?>

<!-- Form Content -->
<?php if($lvl_access > 2) { $new_daily_task->display_Form(); }?>

<!-- Main Content -->
<div class="container content">
    <div class="row" style="margin-top: 8%;">
        <div class="col-lg-12 text-center">
            <h2>Tasks</h2>
            <script src="/js/functions.js"></script>       <!-- WebSite JavaScript -->
            <script>
                var buttons = ['FILTER-1','FILTER-2','FILTER-3','FILTER-4'];
                var tab_filter = new Filter(6, 'table', buttons);
                tab_filter.set_class_filter(0);
            </script>
            <input type="text" id="myFilterInput" onkeyup="tab_filter.set_content_filter('myFilterInput');tab_filter.execute_filter();" placeholder="Search.." title="Type in a name" style="margin-bottom: 0px;"/>
            <div class="row" style="margin-bottom: 8px;text-align: left;">
                <div class="col-lg-8" style="margin-top: 1px;">
                    <button id="FILTER-1" value="ALL" onclick="tab_filter.set_class_filter(0);tab_filter.execute_filter();" type="button" class="btn2 default" style="width: 25%">
                        ALL
                    </button><button id="FILTER-2" value="CURRENT" onclick="tab_filter.set_class_filter(1);tab_filter.execute_filter();" type="button" class="btn2 default" style="width: 25%">
                        CURRENT
                    </button><button id="FILTER-3" value="DAILY" onclick="tab_filter.set_class_filter(2);tab_filter.execute_filter();" type="button" class="btn2 default" style="width: 25%">
                        DAILY
                    </button><button id="FILTER-4" value="PROJECT" onclick="tab_filter.set_class_filter(3, 'h-'+document.getElementById('FILTER-5').options[document.getElementById('FILTER-5').selectedIndex].value);tab_filter.execute_filter();" type="button" class="btn2 default" style="width: 25%">
                        PROJECT
                    </button>
                </div>
                <script>
                    tab_filter.set_class_filter(0);
                </script>
                <div class="col-lg-2">
                    <span class="custom-dropdown custom-dropdown--white" style="margin-top: 1px">
                        <?php $data_projects = $bdd->query('CALL project_list();')->fetchAll(); ?>
                        <select id="FILTER-5" class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white"
                                onchange="tab_filter.set_class_filter(3, 'h-'+document.getElementById('FILTER-5').options[document.getElementById('FILTER-5').selectedIndex].value);tab_filter.execute_filter();"
                                name="p_filter" style="box-shadow: 2px 2px #999;padding: 8px;font-size: 16px;">
                            <option value="0">PROJECTS</option>
                            <?php foreach($data_projects as $project) { ?>
                                <option value="<?php echo $project['h_id'];?>"><?php echo $project['p_name'];?></option>
                            <?php } ?>
                        </select>
                    </span>
                </div>
            </div>
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="th" style="width:3%;">ID</span>
                    <span class="th" style="width:10%;">SubTask Number</span>
                    <span class="th" style="width:25%;">Description</span>
                    <span class="th" style="width:10%;">Total Hours</span>
                    <span class="th" style="width:20%;">Current Performers</span>
                    <span class="th" style="width:30%;">Performers</span>
                    <span class="th" style="width:1%;"></span>
                    <span class="th" style="width:1%;"></span>
                </div>
                <?php foreach ($data_recorded_works as $recorded_work) {
                    if($lvl_access > 2) {
                        $data_user_works = get_user_work_for_rw($bdd, $recorded_work['RW_ID']);
                    } else {
                        $data_user_works = get_user_work_for_rw_and_user($bdd, $recorded_work['RW_ID'], $_SESSION['user_id']);
                    }
                    $isCurrent = false;
                    foreach($data_user_works as $user_work){$isCurrent = ($isCurrent || !isset($user_work['UW_DATETIME_END']));}
                    ?>
                    <form id="delete_row-<?php echo $recorded_work['RW_ID'];?>" method="post" style="display: none;">
                        <input type="hidden" name="f_delete" value="<?php echo $recorded_work['RW_ID'];?>" />
                    </form>
                    <form class="tr ALL <?php echo (($recorded_work['RW_ID_ST']!=NULL)? 'PROJECT h-0 h-'.$recorded_work['WO_ID_H'] : 'DAILY').(($isCurrent)? ' CURRENT' : '');?>" id="row-<?php echo $recorded_work['RW_ID'];?>" name="row-<?php echo $recorded_work['RW_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $recorded_work['RW_ID'];?>', 'row-<?php echo $recorded_work['RW_ID'];?>', '<td><img src=\'/img/wait_rot.gif\'/></td>');return false;" enctype="multipart/form-data" method="post">
                        <?php include('row/managementHome.php')?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php if($lvl_access > 2) { ?>
    <div class="row" style="margin-top: 1%;">
        <div class="col-lg-2 col-lg-offset-4" id="excel_file">
            <button class="button" onclick="call_excel('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/management/?page=managementHome&excel=1';?>', 'excel_file');">Excel</button>
        </div>
        <div class="col-lg-2">
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="button">Add Record</button>
        </div>
    </div>
    <?php } ?>
</div>