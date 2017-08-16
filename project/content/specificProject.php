<?php
/**
 * specificProject content for PROJECT part
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

if($UserConnected AND isset($_GET['page']) AND isset($_GET['id'])) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /*Get Work Order name List */
    $data_wo_names = get_wo_name_list_for_project($bdd, $id_project);
;?>

        <!-- Form Content -->
    <?php if($lvl_access>2) {$new_document_form->display_Form();}?>

        <!-- Main Content -->
    <div class="container content">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 text-center center">
                <h2>Project</h2>
                <table class="myTable">
                    <tr class="header">
                        <th style="width:10%;">ID</th>
                        <th style="width:30%;">Name</th>
                        <th style="width:30%;">Customer</th>
                        <th style="width:30%;">Helicopter</th>
                    </tr>
                    <tr class="fixTab">
                        <td><?php echo $data_project['id'];?></td>
                        <td><?php echo $data_project['p_name'];?></td>
                        <td><?php echo $data_project['c_name'];?></td>
                        <td><?php echo $data_project['ga_name'];?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1" style="margin-top: 5%;">
                <div class="col-lg-2">
                    <a href="?page=recapitulate&id=<?php echo $id_project?>"><button class="button">Summary</button> </a>
                </div>
                <?php if($lvl_access>2) { ?>
                <div class="col-lg-2">
                    <div class="dropdown">
                        <button onclick="dropdown('SOW')" class="dropbtn button">Scope of Work</button>
                        <div id="myDropdown-SOW" class="dropdown-content">
                                <?php foreach($data_wo_names as $data_dropdown) {?>
                                    <a href="index.php?page=scopeOfWork&id=<?php echo $data_dropdown['WO_ID']?>"><?php echo str_replace('%DOC%','SOW', $data_dropdown['WO_NAME']);?></a>
                                <?php }
                                if($lvl_access>2) { ?>
                                    <a onclick="document.getElementById('id01').style.display='block'" ">New</a>
                                <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="col-lg-2">
                    <div class="dropdown">
                        <button onclick="dropdown('WO')" class="dropbtn button">Work Order</button>
                        <div id="myDropdown-WO" class="dropdown-content">
                            <?php foreach($data_wo_names as $data_dropdown) {?>
                                <a href="index.php?page=workOrder&id=<?php echo $data_dropdown['WO_ID']?>"><?php echo str_replace('%DOC%','WO', $data_dropdown['WO_NAME'])?></a>
                            <?php }
                            if($lvl_access>2) { ?>
                                <a onclick="document.getElementById('id01').style.display='block'" ">New</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="dropdown">
                        <button onclick="dropdown('ERV')" class="dropbtn button">ERV</button>
                        <div id="myDropdown-ERV" class="dropdown-content">
                            <?php foreach($data_wo_names as $data_dropdown) {?>
                                <a href="index.php?page=ERV&id=<?php echo $data_dropdown['WO_ID']?>"><?php echo str_replace('%DOC%','ERV', $data_dropdown['WO_NAME'])?></a>
                            <?php }
                            if($lvl_access>2) { ?>
                                <a onclick="document.getElementById('id01').style.display='block'">New</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php if($lvl_access>2) { ?>
                <div class="col-lg-2">
                    <div class="dropdown">
                        <button onclick="dropdown('PIF')" class="dropbtn button">PIF</button>
                        <div id="myDropdown-PIF" class="dropdown-content">
                            <?php foreach($data_wo_names as $data_dropdown) {?>
                                <a href="index.php?page=PIF&id=<?php echo $data_dropdown['WO_ID']?>"><?php echo str_replace('%DOC%','PIF', $data_dropdown['WO_NAME'])?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if($lvl_access>3) { ?>
                <div class="col-lg-2">
                    <a href="?page=close&id=<?php echo $id_project?>"><button class="button">Archive </button> </a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else {
    header('Location: /index.php');
    exit();
}

?>