<?php
/**
 * scopeOfWork content for PROJECT part
 *
 * content file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Project\Content
 * @namespace Project
 * @filesource
 */

/*Get Data about Work Order*/
$data_wo = get_wo_info($bdd, $id_wo);

if($data_wo) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /*  Get other data from database */
    /*  Get Project information */
    $data_project = get_project_info_from_helico($bdd, $data_wo['WO_ID_H']);
    /*Get Sub Task List */
    $data_sub_tasks = get_st_list_for_wo($bdd, $id_wo);

    $data_engine1 = null;
    if($data_project['H_ID_E1'] != null) {
        $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
    }
    $data_engine2 = null;
    if($data_project['H_ID_E2'] != null) {
        $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
    }

    $excel_name = str_replace('/','.',$id_wo.'-'.str_replace('%DOC%','SOW',$data_wo['WO_NAME']).'.xlsx');
    $excel_path = '/files/sow/'.$excel_name;
    ?>

    <!-- Form Content -->
    <?php $new_subtask_sow_form->display_Form();?>

<!-- Main Content -->
<div class="container content">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1 header-resume" style="margin-top: 8%; margin-bottom: 2%;">
            <div class="col-lg-6">
                <h2><strong>Scope Of Work :</strong> <br/><?php echo str_replace('%DOC%','SOW',$data_wo['WO_NAME']);?></h2>
                <h3><strong>Project :</strong> <?php echo $data_project['P_NAME'];?></h3>
            </div>
            <div class="col-lg-6">
                <h4><strong>Customer :</strong> <?php echo $data_project['C_NAME'];?></h4>
                <h4><strong>Aircraft :</strong> <?php echo $data_project['GA_NAME'];?></h4>
                <h4><strong>Serial :</strong> <?php echo $data_project['H_SERIAL'];?></h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <input type="text" id="myFilterInput" onkeyup="filter(5, 'table')" placeholder="Search.." title="Type in a name" />
            <div class="table myTable" id="table">
                <div class="tr header">
                    <span class="td" style="width:10%;">ID</span>
                    <span class="td" style="width:10%;">Sub Task Index</span>
                    <span class="td" style="width:23%;">Reference</span>
                    <span class="td" style="width:35%;">Description</span>
                    <span class="td" style="width:10%;">Date Approved</span>
                    <span class="td" style="width:10%;">Date Completed</span>
                    <span class="td" style="width: 1%;"></span>
                    <span class="td" style="width: 1%;"></span>
                </div>
                <?php foreach($data_sub_tasks as &$sub_task) {
                    if($sub_task['ST_IN_SOW']=='yes') { ?>
                        <form id="delete_row-<?php echo $sub_task['ST_ID'];?>" method="post">
                            <input type="hidden" name="f_delete" value="<?php echo $sub_task['ST_ID'];?>" />
                        </form>
                        <form class="tr" id="row-<?php echo $sub_task['ST_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $sub_task['ST_ID'];?>', 'row-<?php echo $sub_task['ST_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                            <?php include('row/scopeOfWork.php');?>
                        </form>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 3%;">
        <div class="col-lg-2 col-lg-offset-3">
            <a href="./?page=specificProject&id=<?php echo $data_project['P_ID'];?>"><button class="button">Back</button></a>
        </div>
        <div class="col-lg-2" id="excel_file">
            <button class="button" onclick="call_excel('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/project/?page=scopeOfWork&excel='.$id_wo;?>');">Excel</button>
        </div>
        <div class="col-lg-2">
            <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">New Task</button>
        </div>
    </div>
</div>

<?php } else {
    $req->closeCursor();
    display_modal('Error to load WO information, are you on the good page ?');
}
