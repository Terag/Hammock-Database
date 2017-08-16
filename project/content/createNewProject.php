<?php
/**
 * createNewProject content for PROJECT part
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

if($UserConnected) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /*Get $projectList var from database*/
    include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/data_getters.php');

    ?>
        <div class="container content">
            <form action="./?page=createNewProject" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 form-style" style="margin-top: 5%;">
                        <h2>Project</h2>
                        <!-- Hidden Input -->
                        <input type="hidden" value="<?php echo date('Y-m-d');?>" name="f_p_date">
                        <!-- User Input -->
                        <div class="table myTable">
                            <div class="tr header">
                                <span class="th" style="width:30%;">Name<strong> *</strong></span>
                                <span class="th" style="width:30%;">Log Book</span>
                                <span class="th" style="width:30%;">Status Sheet</span>
                            </div>
                            <div class="tr fixTab">
                                <span class="td"><input class="form_input" type="text" name="f_p_name" placeholder="Name.." required/></span>
                                <span class="td"><input class="form_input" type="file"  name="f_h_log_book"/></span>
                                <span class="td"><input class="form_input" type="file" name="f_h_status_sheet"/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 form-style">
                        <h2>Customer</h2>
                        <div class="table myTable">
                            <div class="tr header">
                                <span class="th" style="width:30%;">Name<strong> *</strong></span>
                                <span class="th" style="width:30%;">Phone</span>
                                <span class="th" style="width:30%;">Mail</span>
                            </div>
                            <div class="tr fixTab">
                                <span class="td"><input class="form_input" type="text" name="f_c_name" placeholder="Name.." required/></span>
                                <span class="td"><input class="form_input" type="tel" name="f_c_phone" placeholder="+60 1 00 00 00 00"/></span>
                                <span class="td"><input class="form_input" type="email" name="f_c_mail" placeholder="name@mail.com"/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 form-style">
                        <h2>Helicopter</h2>
                        <div class="table myTable">
                            <div class="tr header">
                                <span class="th" style="width:25%;">Serial</span>
                                <span class="th" style="width:25%;">Aircraft<strong> *</strong></span>
                                <span class="th" style="width:25%;">Total Time</span>
                                <span class="th" style="width:25%;">Landing</span>
                            </div>
                            <div class="tr fixTab">
                                <span class="td"><input class="form_input" type="text" name="f_h_serial" placeholder="Serial.."/></span>
                                <span class="td">
                                    <select class="form_input" name="f_h_id_ga" required>
                                        <?php $data_generic_aircraft = get_generic_aircraft_list_by_type($bdd, 'helico');
                                        foreach ($data_generic_aircraft as $generic_aircraft) { ?>
                                            <option value="<?php echo $generic_aircraft['GA_ID'];?>"><?php echo $generic_aircraft['GA_NAME'];?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                                <span class="td"><input class="form_input" type="number" min="0" step="0.01" name="f_h_total_time" placeholder="Hours.."/></span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" name="f_h_landing" placeholder="Landing.."/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 form-style">
                        <h2>Engine 1</h2>
                        <div class="table myTable">
                            <div class="tr header">
                                <span class="th" style="width:25%;">Serial</span>
                                <span class="th" style="width:15%;">Type</span>
                                <span class="th" style="width:15%;">Total Time</span>
                                <span class="th" style="width:10%;">NG Cycle</span>
                                <span class="th" style="width:10%;">NF Cycle</span>
                                <span class="th" style="width:25%;">Log Book</span>
                            </div>
                            <div class="tr fixTab">
                                <span class="td"><input class="form_input" type="text" name="f_e1_serial" placeholder="Serial.."/></span>
                                <span class="td">
                                    <select class="form_input" name="f_e1_id_ga" required>
                                        <?php $data_generic_aircraft = get_generic_aircraft_list_by_type($bdd, 'engine');
                                        foreach ($data_generic_aircraft as $generic_aircraft) { ?>
                                            <option value="<?php echo $generic_aircraft['GA_ID'];?>"><?php echo $generic_aircraft['GA_NAME'];?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.01" name="f_e1_total_time" placeholder="Hours.."/></span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.1" name="f_e1_ng_cycle"/></span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.1" name="f_e1_nf_cycle"/></span>
                                <span class="td"><input class="form_input" type="file" size="10000000" name="f_e1_log_book"/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 form-style">
                        <h2>Engine 2</h2>
                        <div class="table myTable">
                            <div class="tr header">
                                <span class="th" style="width:25%;">Serial</span>
                                <span class="th" style="width:15%;">Type</span>
                                <span class="th" style="width:15%;">Total Time</span>
                                <span class="th" style="width:10%;">NG Cycle</span>
                                <span class="th" style="width:10%;">NF Cycle</span>
                                <span class="th" style="width:25%;">Log Book</span>
                            </div>
                            <div class="tr fixTab">
                                <span class="td"><input class="form_input" type="text" name="f_e2_serial" placeholder="Serial.."/></span>
                                <span class="td">
                                    <select class="form_input" name="f_e2_id_ga">
                                        <option value="0">None</option>
                                        <?php $data_generic_aircraft = get_generic_aircraft_list_by_type($bdd, 'engine');
                                        foreach ($data_generic_aircraft as $generic_aircraft) { ?>
                                            <option value="<?php echo $generic_aircraft['GA_ID'];?>"><?php echo $generic_aircraft['GA_NAME'];?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.01" name="f_e2_total_time" placeholder="Hours.."/></span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.1" name="f_e2_ng_cycle"/></span>
                                <span class="td"><input class="form_input" type="number" min="0" value="0" step="0.1" name="f_e2_nf_cycle"/></span>
                                <span class="td"><input class="form_input" type="file" name="f_e2_log_book"/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 8%; margin-top: 3%;">
                    <div class="col-lg-2 col-lg-offset-5">
                        <input class="button" type="submit" value="Submit" />
                    </div>
                </div>
            </form>
        </div>
<?php } else {
    header('Location: ../');
    exit();
}