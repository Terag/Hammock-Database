<?php
/**
 * ERV action for PROJECT part
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

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');
/*Get $project_info var from database*/
$id_wo = (int) $_GET['id'];

if(isset($_GET['sticker_data'])) {
    include ('excel/sticker.php');
    exit();
}

/*Get Sub Task List */
$data_sub_tasks = get_st_list_for_wo($bdd, $id_wo);
/*Get Project Part List */
$data_project_parts = get_pp_list_for_wo($bdd, $id_wo);
/*Get Generic Part List */
$data_generic_parts = get_gp_list($bdd);

$_POST['f_user_id'] = $_SESSION['user_id'];
if(!isset($_POST['f_is_defect']) OR $_POST['f_is_defect'] != 'yes') {
    $_POST['f_is_defect'] = 'no';
}

$fields = array(
    'f_user_id' => array('type' => 'hidden', 'required' => TRUE, 'value' => $_SESSION['user_id']),
    'f_sub_task' => array('type' => 'number-int', 'required' => TRUE),
    'f_quantity_rq' => array('type' => 'number-int', 'required' => TRUE),
    'f_gp_id' => array('type' => 'number-int', 'required' => TRUE),
    'f_ipc' => array('type' => 'text', 'required' => TRUE),
    'f_is_defect' => array('type' => 'text', 'required' => TRUE),
    /* NEW GP */
    'f_name' => array('type' => 'text', 'required' => FALSE),
    'f_number' => array('type' => 'text', 'required' => FALSE),
    'f_location' => array('type' => 'text', 'required' => FALSE),
    'f_description' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL new_project_part(:f_sub_task,:f_quantity_rq,:f_gp_id,:f_user_id,:f_ipc,:f_is_defect,
                                          :f_name,:f_number,NULL,:f_location,:f_description);';
$form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/addPartERV.php';
try {
    $new_add_part_erv_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

    if($new_add_part_erv_form->validateForm()) {

        $new_add_part_erv_form->sendForm();

        $last_insert = $bdd->query('SELECT MAX(PP_ID) AS ID FROM T_PROJECT_PART;')->fetch()['ID'];
        $req = $bdd->prepare('	SELECT T_PROJECT_PART.*, T_GENERIC_PART.*, T_SUB_TASK.*, T_GENERIC_SUB_TASK.*, SUM(LPS_QUANTITY_NUMBER) AS LPS_QUANTITY_USED 
	                            FROM T_PROJECT_PART
                                LEFT JOIN T_GENERIC_PART ON PP_ID_GP=GP_ID
                                LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
                                LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                                LEFT JOIN T_LINK_PP_S ON LPS_ID_PP=PP_ID
                                WHERE PP_ID=:id;');
        $req->execute(array('id' => $last_insert));
        $project_part = $req->fetch();?>
        <form id="delete_row-<?php echo $project_part['PP_ID'];?>" method="post" style="display: none;">
            <input type="hidden" name="f_delete" value="<?php echo $project_part['PP_ID'];?>" />
        </form>
        <form class="tr <?php if($project_part['PP_QUANTITY_REQUESTED'] != $project_part['LPS_QUANTITY_USED']) { echo 'highlight';}?>" id="row-<?php echo $project_part['PP_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $project_part['PP_ID'];?>', 'row-<?php echo $project_part['PP_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
            <?php include('row/ERV.php');?>
        </form>
        <?php exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

if(isset($_POST['fe_pp_i']) AND (int)$_POST['fe_pp_i'] > 0) {

    $i = (int)$_POST['fe_pp_i'];

    for($j = 1; $j < $i+1; $j++) {

        $fields = array(
            'fe_pp_id' => array('type' => 'hidden-int', 'required' => TRUE),
            'fe_pp_s_id_'.$j => array('type' => 'hidden-int', 'required' => TRUE),
            'fe_pp_qty_dlv_'.$j => array('type' => 'hidden-int', 'required' => TRUE)
        );

        $sql_request = 'CALL update_link_pp_s(:fe_pp_id,:fe_pp_s_id_'.$j.',:fe_pp_qty_dlv_'.$j.');';
        try {
            $modify_part_erv_form = new Form($bdd, $fields, $sql_request);

            if($modify_part_erv_form->validateForm()) {

                $modify_part_erv_form->sendForm();
            }
        } catch (Exception $error) {
            display_modal($error->getMessage());
            exit();
        }
    }
}

if(!isset($_POST['fe_pp_is_defect']) OR $_POST['fe_pp_is_defect'] != 'yes') {
    $_POST['fe_pp_is_defect'] = 'no';
}

$fields = array(
    'fe_pp_id' => array('type' => 'hidden-int', 'required' => TRUE),
    'fe_pp_st_id' => array('type' => 'number-int', 'required' => TRUE),
    'fe_pp_gp_number' => array('type' => 'text', 'required' => TRUE),
    'fe_pp_ipc' => array('type' => 'text', 'required' => TRUE),
    'fe_pp_is_defect' => array('type' => 'text', 'required' => TRUE),
    'fe_pp_user' => array('type' => 'number-int', 'required' => TRUE),
    'fe_pp_requested_date' => array('type' => 'date', 'required' => TRUE),
    'fe_pp_quantity_requested' => array('type' => 'number-int', 'required' => TRUE),
    'fe_pp_validated' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL update_project_part(:fe_pp_id,:fe_pp_st_id,:fe_pp_gp_number,:fe_pp_ipc, :fe_pp_is_defect,
                                         :fe_pp_user,:fe_pp_requested_date,:fe_pp_quantity_requested, :fe_pp_validated);';
try {
    $modify_part_erv_form = new Form($bdd, $fields, $sql_request);

    if($modify_part_erv_form->validateForm()) {

        $part_numbers = explode(';;', htmlspecialchars($_POST['fe_pp_gp_number']));
        foreach ($part_numbers as $number) {
            if(!is_pn_available($bdd, $number, (int)$_POST['fe_pp_id_gp']))
                throw new Exception('This Part Number is already used');
        }

        $modify_part_erv_form->sendForm();

        $req = $bdd->prepare('SELECT T_PROJECT_PART.*, T_GENERIC_PART.*, T_SUB_TASK.*, T_GENERIC_SUB_TASK.*, SUM(LPS_QUANTITY_NUMBER) AS LPS_QUANTITY_USED 
                              FROM T_PROJECT_PART
                              LEFT JOIN T_GENERIC_PART ON PP_ID_GP=GP_ID
                              LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
                              LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                              LEFT JOIN T_LINK_PP_S ON LPS_ID_PP=PP_ID
                              WHERE PP_ID=:id;');
        $req->execute(array('id' => (int)$_POST['fe_pp_id']));
        $project_part = $req->fetch();
        include('row/ERV.php');
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$fields = array(
    'f_delete' => array('type' => 'hidden-int', 'required' => TRUE)
);
$sql_request = 'CALL delete_project_part(:f_delete);';
try {
    $modify_part_erv_form = new Form($bdd, $fields, $sql_request);

    if($modify_part_erv_form->validateForm()) {

        $modify_part_erv_form->sendForm();
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}