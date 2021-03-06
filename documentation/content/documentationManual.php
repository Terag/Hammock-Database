<?php
/**
 * documentationManual content for DOCUMENTATION part
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

/* Get Data about Aircraft */
$req = $bdd->prepare('SELECT * FROM T_MANUAL INNER JOIN T_GENERIC_AIRCRAFT ON M_ID_GA=GA_ID WHERE M_ID=:id_m');
$req->execute(array('id_m' => $id_m));
$data_manual = $req->fetch();
$req->closeCursor();

/* Get Data about sub tasks */
$req = $bdd->prepare('SELECT * FROM T_GENERIC_SUB_TASK WHERE GST_ID_M=:id_m;');
$req->execute(array('id_m' => $id_m));
$data_sub_tasks = $req->fetchAll();
$req->closeCursor();

if($data_manual) { ?>

    <!-- Add Form Content -->
    <?php if($lvl_access > 2) { $new_generic_subtask_form->display_Form(); }?>

    <!-- Main Content -->
    <div class="container content">
        <div class="row" style="margin-top: 5%">
            <div class="col-lg-10 col-lg-offset-1 header-resume">
                <div class="col-lg-6">
                    <h2><strong>Manual :</strong> <?php echo $data_manual['M_NAME'];?> - <?php echo $data_manual['M_REFERENCE'];?></h2>
                    <h3><strong>Aircraft :</strong> <?php echo $data_manual['GA_NAME'];?></h3>
                    <h3><strong>Constructor :</strong> <?php echo $data_manual['GA_CONSTRUCTOR'];?></h3>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-2 col-lg-offset-7">
                        <div style="max-width: 150px;">
                            <?php if($data_manual['GA_TYPE'] == "engine") { ?>
                                <img src="../../img/motorIcon.png" style="max-width: 100%;max-height: 100%;" />
                            <?php } else { ?>
                                <img src="../../img/helicoIcon.png" style="max-width: 100%;max-height: 100%;" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-3" style="text-align: right;">
                        <h4><strong>Type :</strong> <?php echo $data_manual['GA_TYPE'];?></h4>
                        <?php if($data_manual['M_ID_MANUAL'] != NULL) { ?>
                        <h4>
                            <strong><a href="<?php echo get_file_path($data_manual['M_ID_MANUAL'],$bdd);?>" target="_blank">download</a></strong>
                            <i class="fa fa-download"></i>
                        </h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1" style="margin-top: 3%;">
                <input type="text" id="myFilterInput" onkeyup="filter(4, 'table')" placeholder="Search for names.." title="Type in a name" />
                <div class="table myTable" id="table">
                    <div class="tr header">
                        <span class="th" style="width:5%;">ID</span>
                        <span class="th" style="width:37%;">Number</span>
                        <span class="th" style="width:56%;">Description</span>
                        <span class="th" style="width:1%;"></span>
                        <span class="th" style="width:1%;"></span>
                    </div>
                    <?php foreach ($data_sub_tasks as $sub_task) { ?>
                        <form id="delete_row-<?php echo $sub_task['GST_ID'];?>" method="post" style="display: none;">
                            <input type="hidden" name="f_delete" value="<?php echo $sub_task['GST_ID'];?>" />
                        </form>
                        <form class="tr" id="row-<?php echo $sub_task['GST_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $sub_task['GST_ID'];?>', 'row-<?php echo $sub_task['GST_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                            <?php include('row/documentationManual.php');?>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 3%;">
            <div class="col-lg-2 col-lg-offset-4">
                <a href="./?page=documentationAircraft&id=<?php echo $data_manual['GA_ID'];?>"><button class="button" type="button">Back</button> </a>
            </div>
            <?php if($lvl_access > 2) { ?>
            <div class="col-lg-2">
                <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">New Sub Task</button>
            </div>
            <?php } ?>
        </div>
    </div>
<?php } else {
    $req->closeCursor();
    header('Location: ../');
    exit();
}