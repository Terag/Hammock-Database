<?php
/**
 * Receive action for STOCK part
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
    /* ADD STOCK */
    'f_gp_id' => array('type' => 'number-int', 'required' => TRUE),
    'f_arc_name' => array('type' => 'text', 'required' => TRUE),
    'f_po_name' => array('type' => 'text', 'required' => TRUE),
    'f_arc' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/arc', 'file_name' => '%var%', 'var' => 'f_arc_name'),
    'f_po' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/po', 'file_name' => '%var%', 'var' => 'f_po_name'),
    'f_qty_number' => array('type' => 'number-int', 'required' => TRUE),
    'f_index_pn' => array('type' => 'number-int', 'required' => TRUE),
    'f_serial' => array('type' => 'text', 'required' => FALSE),
    'f_u_price' => array('type' => 'number-float', 'required' => FALSE),
    'f_currency' => array('type' => 'text', 'required' => FALSE),
    'f_vendor' => array('type' => 'text', 'required' => FALSE),
    /* CREATE GP */
    'f_name' => array('type' => 'text', 'required' => TRUE),
    'f_number' => array('type' => 'text', 'required' => FALSE),
    'f_location' => array('type' => 'text', 'required' => FALSE),
    'f_description' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL add_part_stock(:f_gp_id, :f_arc_name, :f_po_name, :f_arc, :f_po, :f_index_pn,
                                    :f_qty_number, :f_serial, :f_u_price, :f_currency, :f_vendor, NULL, NULL,
                                    :f_name, :f_number, NULL, :f_location, :f_description);';
$form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/stock_receive.php';
try {
    $new_received_part = new Form_modal($bdd, $fields, $sql_request, $form_path);

    if($new_received_part->validateForm()) {
        $new_received_part->sendForm();

        $last_insert = $bdd->query('SELECT MAX(S_ID) AS ID FROM T_STOCK;')->fetch()['ID'];
        $req = $bdd->prepare('	SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
										arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT
                                FROM T_STOCK s
                                LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
                                LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
                                LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
                                WHERE S_ID=:id;');
        $req->execute(array('id' => $last_insert));
        $ordered_part = $req->fetch();?>
        <form id="delete_row-<?php echo $ordered_part['S_ID'];?>" method="post" style="display: none;">
            <input type="hidden" value="<?php echo $ordered_part['S_ID_PURCHASE_ORDER'];?>" name="f_delete_po"/>
            <input type="hidden" value="<?php echo $ordered_part['S_ID_ARC'];?>" name="f_delete_arc"/>
            <input type="hidden" value="<?php echo $ordered_part['S_ID'];?>" name="f_delete"/>
        </form>
        <form class="tr" id="row-<?php echo $ordered_part['S_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $ordered_part['S_ID'];?>', 'row-<?php echo $ordered_part['S_ID'];?>', '<td><img src=\'/img/wait_rot.gif\'/></td>');return false;" enctype="multipart/form-data" method="post">
            <?php include('row/receive.php');?>
        </form>
        <?php exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$require = FALSE;
if(isset($_POST['fe_s_rcvd']) AND $_POST['fe_s_rcvd'] = 'yes') {
    $require = TRUE;
} else {
    $_POST['fe_s_rcvd'] = 'no';
}

$fields = array(
    /* MODIFY STOCK */
    'fe_s_id' => array('type' => 'hidden-int', 'required' => TRUE),
    'fe_s_serial' => array('type' => 'text', 'required' => FALSE),
    'fe_s_index_pn' => array('type' => 'number-int', 'required' => TRUE),
    'fe_s_qty' => array('type' => 'text', 'required' => TRUE),
    'fe_arc_name' => array('type' => 'text', 'required' => $require),
    'fe_f_arc' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/arc', 'file_name' => '%var%', 'var' => 'fe_arc_name'),
    'fe_po_name' => array('type' => 'text', 'required' => TRUE),
    'fe_f_po' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/po', 'file_name' => '%var%', 'var' => 'fe_po_name'),
    'fe_s_rcvd' => array('type' => 'text', 'required' => TRUE),
    'fe_s_rcvd_date' => array('type' => 'date', 'required' => $require),
    'fe_s_price' => array('type' => 'number-float', 'required' => FALSE),
    'fe_s_accurency' => array('type' => 'text', 'required' => FALSE),
    'fe_s_vendor' => array('type' => 'text', 'required' => FALSE),
    'fe_gp_location' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL update_stock_part(:fe_s_id, :fe_s_serial, :fe_arc_name, :fe_f_arc, :fe_po_name, :fe_f_po, :fe_s_index_pn,
                                   :fe_s_qty, :fe_s_price, :fe_s_accurency, :fe_s_vendor,
                                   :fe_s_rcvd, :fe_s_rcvd_date, NULL,
                                   :fe_gp_location);';
try {
    $receive_part = new Form($bdd, $fields, $sql_request);

    if($receive_part->validateForm()) {
        $receive_part->sendForm();

        if($_POST['fe_s_rcvd'] == 'no') {
            $req = $bdd->prepare('SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
                                            arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT
                                    FROM T_STOCK s
                                    LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
                                    LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
                                    LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
                                    WHERE S_ID=:id;');
            $req->execute(array('id' => (int)$_POST['fe_s_id']));
            $ordered_part = $req->fetch();
            include('row/receive.php');
        }
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$fields = array(
    'f_delete' => array('type' => 'number-int', 'required' => TRUE),
    'f_delete_po' => array('type' => 'hidden-delete-file', 'required' => FALSE),
    'f_delete_arc' => array('type' => 'hidden-delete-file', 'required' => FALSE)
);
$sql_request = 'DELETE FROM T_STOCK WHERE S_ID=:f_delete';
try {
    $delete_part = new Form($bdd, $fields, $sql_request);

    if($delete_part->validateForm()) {
        $delete_part->sendForm();
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}