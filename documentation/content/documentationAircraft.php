<?php
/**
 * documentationAircraft content for DOCUMENTATION part
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

if($UserConnected) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /* Get Data about Aircraft */
    $req = $bdd->prepare('SELECT * FROM T_GENERIC_AIRCRAFT WHERE GA_ID=:id_ga');
    $req->execute(array('id_ga' => $id_ga));
    $data_aircraft = $req->fetch();
    $req->closeCursor();

    /* Get Data about Manuals */
    $req = $bdd->prepare('SELECT * FROM T_MANUAL LEFT JOIN T_FILE ON M_ID_MANUAL=F_ID WHERE M_ID_GA=:id_ga;');
    $req->execute(array('id_ga' => $id_ga));
    $data_manuals = $req->fetchAll();
    $req->closeCursor();

    if($data_aircraft) { ?>

        <!-- Add Form Content -->
        <?php if($lvl_access > 2) { $new_manual_form->display_Form(); }?>

        <!-- Main Content -->
    <div class="container content">
        <div class="row" style="margin-top: 5%">
            <div class="col-lg-10 col-lg-offset-1 header-resume">
                <div class="col-lg-6">
                    <h2><strong>Aircraft :</strong> <?php echo $data_aircraft['GA_NAME'];?></h2>
                    <h3><strong>Constructor :</strong> <?php echo $data_aircraft['GA_CONSTRUCTOR'];?></h3>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-2 col-lg-offset-7">
                        <div style="max-width: 150px;">
                        <?php if($data_aircraft['GA_TYPE'] == "engine") { ?>
                            <img src="../../img/motorIcon.png" style="max-width: 100%;max-height: 100%;" />
                        <?php } else { ?>
                            <img src="../../img/helicoIcon.png" style="max-width: 100%;max-height: 100%;" />
                        <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-3" style="text-align: right;">
                        <h4><strong>Type :</strong> <?php echo $data_aircraft['GA_TYPE'];?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1" style="margin-top: 3%;">
                <input type="text" id="myFilterInput" onkeyup="filter(4, 'table')" placeholder="Search for names.." title="Type in a name" />
                <div class="table myTable" id="table">
                    <div class="tr header">
                        <span class="th" style="width:10%;">ID</span>
                        <span class="th" style="width:20%;">Name</span>
                        <span class="th" style="width:20%;">Reference</span>
                        <span class="th" style="width:47%;">Description</span>
                        <span class="th" style="width:1%;"></span>
                        <span class="th" style="width:1%;"></span>
                        <span class="th" style="width:1%;"></span>
                    </div>
                    <?php foreach($data_manuals as &$manual) { ?>
                        <form id="delete_row-<?php echo $manual['M_ID'];?>" method="post" style="display: none;">
                            <input type="hidden" name="f_delete" value="<?php echo $manual['M_ID'];?>" />
                            <input type="hidden" name="f_delete_file" value="<?php echo $manual['M_ID_MANUAL'];?>">
                        </form>
                        <form class="tr" id="row-<?php echo $manual['M_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $manual['M_ID'];?>', 'row-<?php echo $manual['M_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" enctype="multipart/form-data" method="post">
                            <?php include('row/documentationAircraft.php');?>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 3%;">
            <div class="col-lg-2 col-lg-offset-4">
                <a href="./"><button class="button">Back</button> </a>
            </div>
            <?php if($lvl_access > 2) { ?>
            <div class="col-lg-2">
                <button class="button" onclick="document.getElementById('id01').style.display='block'" type="submit">New Manual</button>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } else {
        $req->closeCursor();
        header('Location: ../');
        exit();
    }
} else {
    header('Location: ../');
    exit();
}