<?php
/**
 * PO-RFQ_getter file for PO/RFQ Creator
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Modules
 * @package Creator
 * @namespace Creator
 * @filesource
 */

/* Get Vendor Information */

if(isset($_GET['VENDOR'])) {
    $id_vendor = (int)$_GET['VENDOR'];

    $req = $bdd->prepare('SELECT * FROM T_VENDOR WHERE V_ID=:id;');
    $req->execute(array('id' => $id_vendor));
    if($vendor_data = $req->fetch()) {
        $vendor_json = array(
            'name' => $vendor_data['V_NAME'],
            'address' => $vendor_data['V_ADDRESS'],
            'city' => $vendor_data['V_CITY'],
            'country' => $vendor_data['V_COUNTRY'],
            'phone' => $vendor_data['V_PHONE'],
            'mail' => $vendor_data['V_MAIL'],
            'contact' => $vendor_data['V_CONTACT']
        );
    } else {
        $vendor_json = array(
            'name' => null,
            'address' => null,
            'city' => null,
            'country' => null,
            'phone' => null,
            'mail' => null,
            'contact' => null
        );
    }
    echo json_encode($vendor_json, JSON_FORCE_OBJECT);
    exit();
}

/* Get ROW */

if(isset($_GET['GP']) AND isset($_GET['ROW'])) {
    $id_gp = (int)$_GET['GP'];
    $id_row = (int)$_GET['ROW'];

    if(isset($_GET['IPC'])) {
        $ipc = htmlspecialchars(urldecode($_GET['IPC']));
    }
    if(isset($_GET['QTY'])) {
        $qty = (int)$_GET['QTY'];
    }

    /* HTML */ ?>
    <div class="row window_white window_padding" id="row-%ROW%">
        <!-- User Input -->
        <?php if($id_gp < 1) { ?>
            <div class="col-lg-2">
                <input type="text" id="%ROW%-PartFilterInput" class="formFilterInput" onkeyup="filter_select('%ROW%-PartFilterInput','%ROW%-select_part','%ROW%-select_part_element')" placeholder="Search for part.." title="Type in a part" />
            </div>
            <div class="col-lg-1">
                <input type="number" class="form_input" placeholder="Index PN" value="0" name="%ROW%-f_gp_index" required>
            </div>
            <div class="col-lg-3">
                <span class="custom-dropdown custom-dropdown--white">
                    <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" onclick="creator.switch_new_gp(%ROW%);" id="%ROW%-select_part" name="%ROW%-f_gp_id" required>
                        <option value="0">New</option>
                        <?php /* Get all generic parts */
                        $req_parts = $bdd->query('SELECT GP_ID, GP_NAME, GP_NUMBER FROM T_GENERIC_PART');
                        while ($data_parts = $req_parts->fetch()) {
                            ?>
                            <option class="%ROW%-select_part_element" value="<?php echo $data_parts['GP_ID'];?>"><?php echo $data_parts['GP_NAME']." - ".str_replace(';;',' - ',$data_parts['GP_NUMBER']);?></option>
                        <?php } ?>
                    </select>
                </span>
            </div>
        <?php } else { ?>
            <div class="col-lg-2">
                <?php /* Get Specific generic part */
                $req_part = $bdd->prepare('SELECT GP_ID, GP_NAME, GP_NUMBER FROM T_GENERIC_PART WHERE GP_ID=:id_gp');
                $req_part->execute(array('id_gp' => $id_gp));
                $part = $req_part->fetch();
                $STI = (isset($_GET['STI']))? htmlspecialchars(urldecode($_GET['STI'])): '';
                echo $part['GP_NAME'].'<br/>'.$STI;

                $part_numbers = explode(';;', $part['GP_NUMBER']);
                ?>
                <input type="hidden" value="<?php echo $part['GP_ID'];?>" name="%ROW%-f_gp_id"/>
                <?php if(isset($_GET['STI'])) { ?>
                    <input type="hidden" value="<?php echo $STI;?>" name="%ROW%-f_sti"/>
                <?php } ?>
            </div>
            <div class="col-lg-4">
                <span class="custom-dropdown custom-dropdown--white">
                    <select class="form_input custom-dropdown__select custom-dropdown--large custom-dropdown__select--white" name="%ROW%-f_gp_number" required>
                        <?php foreach ($part_numbers as $key => $pn) { ?>
                            <option value="<?php echo $pn;?>|<?php echo $key;?>"><?php echo $pn;?></option>
                        <?php } ?>
                    </select>
                </span>
            </div>
        <?php } ?>
        <div class="col-lg-2">
            <input class="form_input" type="text" placeholder="IPC" <?php if(isset($ipc)){echo 'value="'.$ipc.'"';}?> name="%ROW%-f_p_ipc"/>
        </div>
        <div class="col-lg-2">
            <input class="form_input" type="number" min="1" placeholder="Qty" <?php if(isset($qty)){echo 'value="'.$qty.'"';}?> name="%ROW%-f_p_qty" required/>
        </div>
        <div class="col-lg-2">
            <input class="form_input" type="number" min="0" step="0.01" placeholder="Price" name="%ROW%-f_p_price"/>
        </div>
    </div>
    <?php if($id_gp < 1) { ?>
        <div class="row window_white window_padding" id="row-%ROW%-bis" style="margin: 0px 20px 10px 20px;">
            <div class="col-lg-3">
                <input class="form_input" type="text" placeholder="Description" name="%ROW%-f_new_gp_name" required/>
            </div>
            <div class="col-lg-3">
                <input class="form_input" type="text" placeholder="Part Number" name="%ROW%-f_new_gp_number" required/>
            </div>
            <div class="col-lg-3">
                <input class="form_input" type="text" placeholder="Location" name="%ROW%-f_new_gp_location"/>
            </div>
        </div>
    <?php } ?>
<?php exit(); }

/* Get Editor */

if(isset($_GET['EDITOR'])) { ?>
    <div class="row window_grey window_padding number_editor">
        <div class="col-lg-1 col-lg-offset-5">
            <button class="button add" onclick="creator.add_row_blank();" type="button"><i class='fa fa-plus-square-o'></i></button>
        </div>
        <div class="col-lg-1">
            <button class="button danger" onclick="creator.delete_row();" type="button"><i class='fa fa-minus-square-o'></i></button>
        </div>
    </div>
    <input id="f_count" type="hidden" value="0" name="f_count" />
<?php exit(); }