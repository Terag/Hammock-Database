<?php
/**
 * ERV content for PROJECT part
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
/*Get $project_info var from database*/

/*Get Data about Work Order*/
$data_wo = get_wo_info($bdd, $id_wo);

if($data_wo) {

    if(isset($_SESSION['error'])) {
        display_modal($_SESSION['error']->getMessage());
    }

    /*  Get other data from database */
    /*  Get Project information */
    $data_project = get_project_info_from_helico($bdd, $data_wo['WO_ID_H']);

    ?>

    <!-- Form Content -->
    <?php $new_add_part_erv_form->display_Form();?>


    <div class="container content">
        <?php if($lvl_access > 2) {

            $data_vendors = $bdd->query('SELECT V_ID, V_NAME FROM T_VENDOR ORDER BY V_NAME;')->fetchAll(); ?>


            <!------------------------------- PO/RFQ Creator Content ------------------------------->
            <span id="creator" style="display: none;">
                <script src="/js/file_creator/Creator.js"></script>
                <script>
                    // Creating prototype to use in creator
                    var creator = new Creator('ListCreator_area', '/stock/?page=order');
                </script>
                <form id="form_creator" onsubmit="xmlhttpPost(document.location.origin + '/stock/?page=order&excel=1', 'form_creator', 'download', '<button class=\'button\' type=\'button\' disabled><img src=\'/img/wait_rot.gif\'/></button>');$('input[name=\'f_g_add_receive\']').prop('checked', false);return false;" method="post">
                    <div class="row">
                        <div class="col-lg-10 col-lg-offset-1 center text-center window_grey window_font nopadding">
                            <h2 id="title">RFQ/PO creator</h2>
                            <div class="col-md-6 window_white">
                                <div class="row window_grey">
                                    <h3>Global Information</h3>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-4">
                                        <span class="custom-dropdown custom-dropdown--white">
                                            <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="f_g_doctype">
                                                <option value="0">Purchase Order</option>
                                                <option value="1">Request For Quotation</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_g_name">
                                            Name
                                        </label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input class="form_input" type="text" name="f_g_name" required/>
                                    </div>
                                </div>
                                <div class="row window_padding" id="add_receive">
                                    <div class="col-lg-12" style="text-align: left;">
                                        <input class="form_input" type="checkbox" value="1" style="width: 20px;" name="f_g_add_receive"/> Add Parts in Receive (PO Online)
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-6">
                                        <?php echo $data_project['P_NAME'];?>
                                        <input type="hidden" value="<?php echo $data_project['P_NAME'];?>" name="f_g_project">
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_rep">Rep</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" value="Mark D McGregor" name="f_g_rep">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_date">Date</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="date" name="f_g_date">
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_phone">Phone</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="tel" value="+60122869186" name="f_g_phone">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_location">Location</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" value="Skypark RAC, Block C" name="f_g_location">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 window_white">
                                <div class="row window_grey">
                                    <h3>Vendor Information</h3>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-6">
                                        <span class="custom-dropdown custom-dropdown--white">
                                            <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white"
                                                    onclick="select_edit_inputs('vendor_select', document.location.origin + '/stock/?page=order&VENDOR=', {
                                                'address' : 'f_v_address',
                                                'city' : 'f_v_city',
                                                'country' : 'f_v_country',
                                                'phone' : 'f_v_phone',
                                                'mail' : 'f_v_mail',
                                                'contact' : 'f_v_contact',
                                                'name' : 'f_v_name'
                                            });" id="vendor_select" name="f_v_vendor">
                                                <option value="0">Vendor</option>
                                                <option value="-1">New</option>
                                                <?php foreach($data_vendors as $vendor) { ?>
                                                    <option value="<?php echo $vendor['V_ID'];?>"><?php echo $vendor['V_NAME']?></option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_v_name">Name</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" name="f_v_name" required/>
                                    </div>
                                </div>
                                <div class="row window_padding" id="vendor_info">
                                    <div class="col-lg-6" style="text-align: left;">
                                        <input class="form_input" type="checkbox" value="1" style="width: 20px;" name="f_v_vendor_info"/> Edit Vendor info
                                    </div>
                                    <div class="col-lg-offset-2 col-lg-4">
                                        <span class="custom-dropdown custom-dropdown--white">
                                            <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="f_g_currency">
                                                <option value="MYR">MYR</option>
                                                <option value="EUR">EUR</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_v_address">Address</label>
                                    </div>
                                    <div class="col-lg-10">
                                        <input class="form_input" type="text" name="f_v_address">
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_v_city">City</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" name="f_v_city">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_v_country">Country</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" name="f_v_country">
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_v_phone">Phone</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="tel" name="f_v_phone">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="f_v_contact">Contact</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input class="form_input" type="text" name="f_v_contact">
                                    </div>
                                </div>
                                <div class="row window_padding">
                                    <div class="col-lg-2">
                                        <label for="f_v_mail">Mail</label>
                                    </div>
                                    <div class="col-lg-10">
                                        <input class="form_input" type="text" name="f_v_mail">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-10 col-lg-offset-1 text-center window_grey window_font nopadding" style="margin-top: 3%;">
                                <h2 id="title">Part List</h2>
                                <div class="col-lg-12" id="ListCreator_area">
                                    <!-- LIST PART Engine -->
                                </div>
                                <script>
                                    creator.start();
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 3%;">
                        <div class="col-lg-2 col-lg-offset-3">
                            <button class="button" onclick="$('#creator').toggle();" type="button">Close</button>
                        </div>
                        <div class="col-lg-2" id="download">

                        </div>
                        <div class="col-lg-2">
                            <button id="submit-form" class="button" type="submit">Create</button>
                        </div>
                    </div>
                </form>
            </span>
        <?php } ?>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1 header-resume" style="margin-top: 8%; margin-bottom: 2%;">
                <div class="col-lg-6">
                    <h2><strong>ERV :</strong> <br/><?php echo str_replace('%DOC%', 'ERV', $data_wo['WO_NAME']);?></h2>
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
            <div class="col-lg-12 col-lg-offset">
                <input type="text" id="myFilterInput" onkeyup="filter(10, 'table')" placeholder="Search.." title="Type in a name" />
                <div class="table myTable" id="table">
                    <div class="tr header">
                        <span class="th" style="width:5%;">ID</span>
                        <span class="th" style="width:7%;">Sub Task<br/>Number</span>
                        <span class="th" style="width:10%;">Part Number</span>
                        <span class="th" style="width:5%;">Location</span>
                        <span class="th" style="width:10%;">Description</span>
                        <span class="th" style="width:10%;">IPC</span>
                        <span class="th" style="width:10%;">Ask By</span>
                        <span class="th" style="width:8%;">Date</span>
                        <span class="th" style="width:3%;">QTY<br/>Req'D</span>
                        <span class="th" style="width:3%;">QTY<br/>Dlv'D</span>
                        <span class="th" style="width:26%;">Stock<br/>Available<br/><span style="font-size: small;">Used[Available]</span></span>
                        <span class="th" style="width:1%;"></span>
                        <span class="th" style="width:1%;"></span>
                        <span class="th" style="width:1%;"></span>
                    </div>
                    <?php foreach($data_project_parts as &$project_part) { ?>
                        <form id="delete_row-<?php echo $project_part['PP_ID'];?>" method="post" style="display: none;">
                            <input type="hidden" name="f_delete" value="<?php echo $project_part['PP_ID'];?>" />
                        </form>
                        <form class="tr <?php if($project_part['PP_QUANTITY_REQUESTED'] != $project_part['LPS_QUANTITY_USED']) { echo 'highlight';}?>" id="row-<?php echo $project_part['PP_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $project_part['PP_ID'];?>', 'row-<?php echo $project_part['PP_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                            <?php include('row/ERV.php');?>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 3%;">
            <div class="col-lg-2 col-lg-offset-2">
                <a href="./?page=specificProject&id=<?php echo $data_project['P_ID'];?>"><button class="button">Back</button></a>
            </div>
            <?php if($lvl_access > 2) { ?>
                <div class="col-lg-2" id="excel_file">
                    <button class="button" onclick="call_excel('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/project/?page=ERV&excel='.$id_wo;?>');">Excel</button>
                </div>
                <div class="col-lg-2">
                    <button class="button" onclick="$('#creator').toggle();" type="button">Creator PO/RFQ</button>
                </div>
            <?php } ?>
            <div class="col-lg-2">
                <button class="button" onclick="document.getElementById('id01').style.display='block'" type="button">Add Part</button>
            </div>
        </div>
        <?php if($lvl_access > 2) { ?>
        <div class="row" style="margin-top: 15px;">
            <div class="col-lg-2 col-lg-offset-4" id="excel_sticker">
                <button class="button" onclick="call_excel('<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/project/?page=ERV&sticker_data='.$id_wo;?>', 'excel_sticker');">Excel Sticker</button>
            </div>
            <div class="col-lg-2" id="doc_sticker">
                <a href="/files/sticker.docx"><button class="button">Doc Sticker</button></a>
            </div>
        </div>
        <?php } ?>
    </div>
<?php } else {
    header('Location: /index.php');
    exit();
}