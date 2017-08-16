<?php
/**
 * PIF action for PROJECT part
 *
 * action file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Project\Action
 * @namespace Project
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/file_manager.php');
include($_SERVER['DOCUMENT_ROOT'].'/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/form.php');
/*Get $project_info var from database*/
$id_wo = (int) $_GET['id'];

$fields = array(
    'fe_lps_pp_id' => array('type' => 'hidden-int', 'required' => TRUE),
    'fe_lps_s_id' => array('type' => 'hidden-int', 'required' => TRUE),
    'fe_lps_qty_dlv' => array('type' => 'int', 'required' => TRUE)
);

$sql_request = 'CALL update_link_pp_s(:fe_lps_pp_id,:fe_lps_s_id,:fe_lps_qty_dlv);';
try {
    $modify_part_pif_form = new Form($bdd, $fields, $sql_request);

    if($modify_part_pif_form->validateForm()) {
        $modify_part_pif_form->sendForm();

        if((int)$_POST['fe_lps_qty_dlv'] > 0) {
            $req = $bdd->prepare('	SELECT * FROM T_LINK_PP_S
                                    LEFT JOIN T_PROJECT_PART ON LPS_ID_PP=PP_ID
                                    LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
                                    LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                                    LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
                                    LEFT JOIN T_STOCK ON LPS_ID_S=S_ID
                                    LEFT JOIN T_GENERIC_PART ON S_ID_GP=GP_ID
                                    LEFT JOIN T_ATA_REFERENCE ON GST_ID_AR=AR_ID
                                    WHERE LPS_ID_S=:s_id AND LPS_ID_PP=:pp_id;');
            $req->execute(array('s_id' => (int)$_POST['fe_lps_s_id'], 'pp_id' => (int)$_POST['fe_lps_pp_id']));
            $project_part = $req->fetch();
            include('row/PIF.php');
        }
        exit();
    }
} catch (Exception $error) {
    $_SESSION['error']=$error;
}