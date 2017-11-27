<?php
/**
 * Available action for STOCK part
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

if($lvl_access > 2) {

    $fields = array(
        /* MODIFY STOCK */
        'fe_s_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_s_serial' => array('type' => 'text', 'required' => FALSE),
        'fe_s_qty' => array('type' => 'text', 'required' => TRUE),
        'fe_arc_name' => array('type' => 'text', 'required' => TRUE),
        'fe_f_arc' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/arc', 'file_name' => '%var%', 'var' => 'fe_arc_name'),
        'fe_po_name' => array('type' => 'text', 'required' => TRUE),
        'fe_f_po' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/po', 'file_name' => '%var%', 'var' => 'fe_po_name'),
        'fe_s_index_pn' => array('type' => 'number-int', 'required' => TRUE),
        'fe_s_rcvd_date' => array('type' => 'date', 'required' => TRUE),
        'fe_s_expi_date' => array('type' => 'date', 'required' => FALSE),
        'fe_s_price' => array('type' => 'number-float', 'required' => FALSE),
        'fe_s_accurency' => array('type' => 'text', 'required' => FALSE),
        'fe_s_vendor' => array('type' => 'text', 'required' => FALSE),
    );
    $sql_request = 'CALL update_stock_part(:fe_s_id, :fe_s_serial, :fe_arc_name, :fe_f_arc, :fe_po_name, :fe_f_po, :fe_s_index_pn,
                                   :fe_s_qty, :fe_s_price, :fe_s_accurency, :fe_s_vendor,
                                   \'yes\', :fe_s_rcvd_date, :fe_s_expi_date,
                                   NULL);';
    try {
        $receive_part = new Form($bdd, $fields, $sql_request);

        if ($receive_part->validateForm()) {
            $receive_part->sendForm();

            $req = $bdd->prepare('  SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
                                        arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT, S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) AS S_QUANTITY_AVAILABLE
                                FROM T_STOCK s
                                LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
                                LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
                                LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
                                LEFT JOIN T_LINK_PP_S ON (LPS_ID_S=S_ID)
                                WHERE S_ID=:id');
            $req->execute(array('id' => (int)$_POST['fe_s_id']));
            $stock_part = $req->fetch();
            include('row/available.php');
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

        if ($delete_part->validateForm()) {
            $delete_part->sendForm();
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}