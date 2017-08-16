<?php
/**
 * recapitulate content for PROJECT part
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

//user identification
if($UserConnected AND isset($_GET['page']) AND isset($_GET['id'])) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /*Get datas from database*/
    $req = $bdd->prepare('CALL project_recapitulate_from_project_ID(:id);');
    $req->execute(array('id' => $id));
    if($data = $req->fetch()) {

        /* New query for engines */
        $req->closeCursor();
        $e_req = $bdd->prepare('CALL engine_recapitulate(:e_id);');
        /* Engine 1 information */
        $e_req->execute(array('e_id' => $data['H_ID_E1']));
        $data_e1 = $e_req->fetch();
        $e_req->closeCursor();
        /* Engine 2 information */
        $e_req->execute(array('e_id' => $data['H_ID_E2']));
        $data_e2 = $e_req->fetch();
        $e_req->closeCursor();

        ?>
        <div class="container content">
            <div class="row" id="row-1">
                <div class="col-lg-10 col-lg-offset-1 header-resume" style="margin-top: 5%;">
                    <h2>Project</h2>
                    <form action="" method="post" name="fe_p_form">
                    <!-- Hidden Input -->
                    <input type="hidden" value="<?php echo $id?>" name="fe_p_id">
                    <!-- User Input -->
                    <div class="table myTable">
                        <div class="tr header">
                            <span class="th" style="width:9%;">ID</span>
                            <span class="th" style="width:30%;">Name</span>
                            <span class="th" style="width:20%;">Progress</span>
                            <span class="th" style="width:20%;">Opened Date</span>
                            <span class="th" style="width:20%;">Closed Date</span>
                            <span class="th" style="width:1%;"></span>
                        </div>
                        <div class="tr fixTab">
                            <span class="td"><?php echo $data['P_ID'];?></span>
                            <span class="td"><?php echo $data['P_NAME'];?></span>
                            <span class="td"></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['P_OPENED_DATE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="date" placeholder="yyyy/mm/dd" value="<?php echo $data['P_OPENED_DATE'];?>" name="fe_p_opened_date" required/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['P_CLOSED_DATE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="date" placeholder="yyyy/mm/dd" value="<?php echo $data['P_CLOSED_DATE'];?>" name="fe_p_closed_date"/>
                                </div>
                            </span>
                            <span class="td">
                                <?php if($lvl_access>2) { ?>
                                <div class="element">
                                    <button class="btn edit" type="button" onclick="edit_row(1)"><i class="fa fa-pencil"></i></button>
                                </div>
                                <?php } ?>
                                <div class="element" style="display: none;">
                                    <button class="btn add" type="submit" value="1" name="fe_p_form"><i class='fa fa-check-square-o'></i></button>
                                </div>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row" id="row-2">
                <div class="col-lg-10 col-lg-offset-1 header-resume">
                    <h2>Customer</h2>
                    <form action="" method="post">
                    <input type="hidden" value="<?php echo $data['C_ID'];?>" name="fe_c_id">
                    <div class="table myTable">
                        <div class="tr header">
                            <span class="th" style="width:9%;">ID</span>
                            <span class="th" style="width:30%;">Name</span>
                            <span class="th" style="width:30%;">Phone</span>
                            <span class="th" style="width:30%;">Mail</span>
                            <span class="th" style="width:1%;"></span>
                        </div>
                        <div class="tr fixTab">
                            <span class="td"><?php echo $data['C_ID'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['C_NAME'];?>
                                </div>
                                <div class="element" style="display: none">
                                    <input class="form_input" type="text" value="<?php echo $data['C_NAME'];?>" name="fe_c_name" required/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['C_PHONE'];?>
                                </div>
                                <div class="element" style="display:none;">
                                    <input class="form_input" type="phone" value="<?php echo $data['C_PHONE'];?>" name="fe_c_phone"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['C_MAIL'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="mail" value="<?php echo $data['C_MAIL'];?>" name="fe_c_mail"/>
                                </div>
                            </span>
                            <span class="td">
                                <?php if($lvl_access>2) { ?>
                                <div class="element">
                                    <button class="btn edit" type="button" onclick="edit_row(2)"><i class="fa fa-pencil"></i></button>
                                </div>
                                <?php } ?>
                                <div class="element" style="display: none;">
                                    <button class="btn add" type="submit" value="1" name="fe_c_form"><i class='fa fa-check-square-o'></i></button>
                                </div>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row" id="row-3">
                <div class="col-lg-10 col-lg-offset-1 header-resume">
                    <h2>Helicopter</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $data['H_ID'];?>" name="fe_h_id"/>
                    <input type="hidden" value="<?php echo $data['P_NAME'];?>" name="fe_h_p_name"/>
                    <div class="table myTable">
                        <div class="tr header">
                            <span class="th" style="width:10%;">ID</span>
                            <span class="th" style="width:20%;">Serial</span>
                            <span class="th" style="width:18%;">Type</span>
                            <span class="th" style="width:15%;">Total Time</span>
                            <span class="th" style="width:15%;">Landing</span>
                            <span class="th" style="width:21%;">Status/Log</span>
                            <span class="th" style="width:1%;"></span>
                        </div>
                        <div class="tr fixTab">
                            <span class="td"><?php echo $data['H_ID'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['H_SERIAL'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="text" value="<?php echo $data['H_SERIAL'];?>" name="fe_h_serial"/>
                                </div>
                            </span>
                            <span class="td"><?php echo $data['GA_NAME'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['H_TIME'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" min="0" step="0.01" value="<?php echo $data['H_TIME']?>" name="fe_h_time"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data['H_LANDING'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" value="<?php echo $data['H_LANDING'];?>" name="fe_h_landing"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php if($data['H_ID_STATUS_SHEET'] != null) { ?>
                                    <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($data['H_ID_STATUS_SHEET'], $bdd)?>');return false;" title="Status Sheet"><i class="fa fa-file"></i></button>
                                    <?php }
                                    if($data['H_ID_LOG_BOOK'] != null) { ?>
                                    <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($data['H_ID_LOG_BOOK'], $bdd)?>');return false;" title="Log Book"><i class="fa fa-file-text"></i></button>
                                    <?php } ?>
                                </div>
                                <div class="element" style="display: none;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            Status Sheet
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form_input" type="file" name="fe_h_status_sheet"/>
                                            <input type="hidden" value="<?php echo $data['H_ID_STATUS_SHEET'];?>" name="fe_h_delete_status_sheet">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            Log Book
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form_input" type="file" name="fe_h_log_book"/>
                                            <input type="hidden" value="<?php echo $data['H_ID_LOG_BOOK'];?>" name="fe_h_delete_log_book">
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <span class="td">
                                <?php if($lvl_access>2) { ?>
                                <div class="element">
                                    <button class="btn edit" type="button" onclick="edit_row(3)"><i class="fa fa-pencil"></i></button>
                                </div>
                                <?php } ?>
                                <div class="element" style="display: none;">
                                    <button class="btn add" type="submit" value="1" name="fe_h_form"><i class='fa fa-check-square-o'></i></button>
                                </div>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <?php if(isset($data_e1) AND $data_e1){ ?><div class="row" id="row-4">
                <div class="col-lg-10 col-lg-offset-1 header-resume">
                    <h2>Engine 1</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $data['H_ID_E1'];?>" name="fe_e1_id">
                    <input type="hidden" value="<?php echo $data['P_NAME'];?>" name="fe_e1_p_name"/>
                    <div class="table myTable">
                        <div class="tr header">
                            <span class="th" style="width:10%;">ID</span>
                            <span class="th" style="width:25%;">Serial</span>
                            <span class="th" style="width:17%;">Type</span>
                            <span class="th" style="width:12%;">Total Time</span>
                            <span class="th" style="width:11%;">NG Cycle</span>
                            <span class="th" style="width:11%;">NF Cycle</span>
                            <span class="th" style="width:21%;">Log</span>
                            <span class="th" style="width:1%;"></span>
                        </div>
                        <div class="tr fixTab">
                            <span class="td"><?php echo $data_e1['E_ID'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e1['E_SERIAL'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="text" value="<?php echo $data_e1['E_SERIAL'];?>" name="fe_e1_serial"/>
                                </div>
                            </span>
                            <span class="td"><?php echo $data_e1['GA_NAME'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e1['E_TIME'];?>
                                </div>
                                <div class="element" style="display:none;">
                                    <input class="form_input" type="number" min="0" step="0.01" value="<?php echo $data_e1['E_TIME'];?>" name="fe_e1_time"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e1['E_NG_CYCLE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" step="0.1" value="<?php echo $data_e1['E_NG_CYCLE'];?>" name="fe_e1_ng_cycle"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e1['E_NF_CYCLE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" step="0.1" value="<?php echo $data_e1['E_NF_CYCLE'];?>" name="fe_e1_nf_cycle"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php if($data_e1['E_ID_LOG_BOOK'] != null) { ?>
                                    <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($data_e1['E_ID_LOG_BOOK'], $bdd)?>');return false;" title="Log Book"><i class="fa fa-file-text"></i></button>
                                    <?php } ?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="file" name="fe_e1_log_book"/>
                                    <input type="hidden" value="<?php echo $data_e1['E_ID_LOG_BOOK'];?>" name="fe_e1_delete_log_book"/>
                                </div>
                            </span>
                            <span class="td">
                                <?php if($lvl_access>2) { ?>
                                <div class="element">
                                    <button class="btn edit" type="button" onclick="edit_row(4)"><i class="fa fa-pencil"></i></button>
                                </div>
                                <?php } ?>
                                <div class="element" style="display: none;">
                                    <button class="btn add" type="submit" value="1" name="fe_e1_form"><i class='fa fa-check-square-o'></i></button>
                                </div>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
            </div><?php } ?>
            <?php if(isset($data_e2) AND $data_e2){ ?><div class="row" id="row-5">
                <div class="col-lg-10 col-lg-offset-1 header-resume">
                    <h2>Engine 2</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $data['H_ID_E2'];?>" name="fe_e2_id">
                    <input type="hidden" value="<?php echo $data['P_NAME'];?>" name="fe_e2_p_name"/>
                    <div class="table myTable">
                        <div class="tr header">
                            <span class="td" style="width:10%;">ID</span>
                            <span class="td" style="width:25%;">Serial</span>
                            <span class="td" style="width:17%;">Type</span>
                            <span class="td" style="width:12%;">Total Time</span>
                            <span class="td" style="width:11%;">NG Cycle</span>
                            <span class="td" style="width:11%;">NF Cycle</span>
                            <span class="td" style="width:21%;">Log</span>
                            <span class="td" style="width:1%;"></span>
                        </div>
                        <div class="tr fixTab">
                            <span class="td"><?php echo $data_e2['E_ID'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e2['E_SERIAL'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="text" value="<?php echo $data_e2['E_SERIAL'];?>" name="fe_e2_serial"/>
                                </div>
                            </span>
                            <span class="td"><?php echo $data_e2['GA_NAME'];?></span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e2['E_TIME'];?>
                                </div>
                                <div class="element" style="display:none;">
                                    <input class="form_input" type="number" min="0" step="0.01" value="<?php echo $data_e2['E_TIME'];?>" name="fe_e2_time"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e2['E_NG_CYCLE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" step="0.1" value="<?php echo $data_e2['E_NG_CYCLE'];?>" name="fe_e2_ng_cycle"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php echo $data_e2['E_NF_CYCLE'];?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="number" step="0.1" value="<?php echo $data_e2['E_NF_CYCLE'];?>" name="fe_e2_nf_cycle"/>
                                </div>
                            </span>
                            <span class="td">
                                <div class="element">
                                    <?php if($data_e2['E_ID_LOG_BOOK'] != null) { ?>
                                        <button class="btn default" onclick="openInNewTab('<?php echo get_file_path($data_e2['E_ID_LOG_BOOK'], $bdd)?>'); return false;" title="Log Book"><i class="fa fa-file-text"></i></button>
                                    <?php } ?>
                                </div>
                                <div class="element" style="display: none;">
                                    <input class="form_input" type="file" name="fe_e2_log_book"/>
                                    <input type="hidden" value="<?php echo $data_e2['E_ID_LOG_BOOK'];?>" name="fe_e2_delete_log_book"/>
                                </div>
                            </span>
                            <span class="td">
                                <?php if($lvl_access>2) { ?>
                                <div class="element">
                                    <button class="btn edit" type="button" onclick="edit_row(5)"><i class="fa fa-pencil"></i></button>
                                </div>
                                <?php } ?>
                                <div class="element" style="display: none;">
                                    <button class="btn add" type="submit" value="1" name="fe_e2_form"><i class='fa fa-check-square-o'></i></button>
                                </div>
                            </span>
                        </div>
                    </div>
                    </form>
                </div>
            </div><?php } ?>
            <div class="row">
                <div class="col-lg-2 col-lg-offset-5" style="margin-bottom: 8%; margin-top: 3%;">
                    <form action="?page=specificProject&id=<?php echo $id?>" method="post">
                        <input class="button" type="submit" value="Back" />
                    </form>
                </div>
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

?>