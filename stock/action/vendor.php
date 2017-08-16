<?php
/**
 * Vendor action for STOCK part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Stock\Action
 * @namespace Stock
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/file_manager.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/form.php');
include($_SERVER['DOCUMENT_ROOT'].'/ui/modal_warning.php');

$fields = array(
    /* CREATE VENDOR */
    'f_name' => array('type' => 'text', 'required' => TRUE),
    'f_address' => array('type' => 'text', 'required' => FALSE),
    'f_city' => array('type' => 'text', 'required' => FALSE),
    'f_country' => array('type' => 'text', 'required' => FALSE),
    'f_phone' => array('type' => 'text', 'required' => FALSE),
    'f_mail' => array('type' => 'text', 'required' => FALSE),
    'f_contact' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL new_vendor(:f_name, :f_address, :f_city, :f_country, :f_phone, :f_mail, :f_contact);';
$form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_vendor.php';
try {
    $new_vendor_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

    if($new_vendor_form->validateForm()) {
        $new_vendor_form->sendForm();

        $last_insert = $bdd->query('SELECT MAX(V_ID) AS ID FROM T_VENDOR;')->fetch()['ID'];
        $req = $bdd->prepare('SELECT * FROM T_VENDOR WHERE V_ID=:id;');
        $req->execute(array('id' => $last_insert));
        $vendor = $req->fetch();?>
        <form id="delete_row-<?php echo $vendor['V_ID'];?>" method="post" style="display: none;">
            <input type="hidden" name="f_delete" value="<?php echo $vendor['V_ID'];?>" />
        </form>
        <form class="tr" id="row-<?php echo $vendor['V_ID'];?>" id="row-<?php echo $vendor['V_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $vendor['V_ID'];?>', 'row-<?php echo $vendor['V_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
            <?php include('row/vendor.php');?>
        </form>
        <?php exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$fields = array(
    /* UPDATE VENDOR */
    'fe_v_id' => array('type' => 'number-int', 'required' => TRUE),
    'fe_v_name' => array('type' => 'text', 'required' => TRUE),
    'fe_v_address' => array('type' => 'text', 'required' => FALSE),
    'fe_v_city' => array('type' => 'text', 'required' => FALSE),
    'fe_v_country' => array('type' => 'text', 'required' => FALSE),
    'fe_v_phone' => array('type' => 'text', 'required' => FALSE),
    'fe_v_mail' => array('type' => 'text', 'required' => FALSE),
    'fe_v_contact' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL update_vendor(:fe_v_id, :fe_v_name, :fe_v_address, :fe_v_city, :fe_v_country, :fe_v_phone, :fe_v_mail, :fe_v_contact);';
try {
    $update_vendor_form = new Form($bdd, $fields, $sql_request);

    if($update_vendor_form->validateForm()) {
        $update_vendor_form->sendForm();

        $req = $bdd->prepare('SELECT * FROM T_VENDOR WHERE V_ID=:id');
        $req->execute(array('id' => (int)$_POST['fe_v_id']));
        $vendor = $req->fetch();
        include('row/vendor.php');
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$fields = array(
    'f_delete' => array('type' => 'hidden-int', 'required' => TRUE)
);
$sql_request = 'DELETE FROM T_VENDOR WHERE V_ID=:f_delete;';
try {
    $delete_vendor_form = new Form($bdd, $fields, $sql_request);

    if($delete_vendor_form->validateForm()) {
        $delete_vendor_form->sendForm();
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}