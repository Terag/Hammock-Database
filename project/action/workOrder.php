<?php
/**
 * WorkOrder action for PROJECT part
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

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/reference_helper.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');
$id_wo = (int) $_GET['id'];

/* Need to define the data for reference helper */

/*Get Data about Work Order*/
$data_wo = get_wo_info($bdd, $id_wo);
if(!$data_wo) {
    header('Location: ./index.php');
    exit();
}

$wo_name = str_replace('%DOC%','WO',$data_wo['WO_NAME']);
/*  Get Project information */
$data_project = get_project_info_from_helico($bdd, $data_wo['WO_ID_H']);

$data_engine1 = null;
if($data_project['H_ID_E1'] != null) {
    $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
}
$data_engine2 = null;
if($data_project['H_ID_E2'] != null) {
    $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
}

/* Prepare data for str_link_reference function */
$structure = array(
    'DOC' => array('VALUE' => $wo_name),
    'P' => array('VALUE' => $data_project['P_NAME']),
    'M' => array('VALUE' => NULL),
    'H' => array('N' => 'H_SERIAL', 'array_name' => 'project'),
    'E1' => array('N' => 'E_SERIAL', 'array_name' => 'engine1'),
    'E2' => array('N' => 'E_SERIAL', 'array_name' => 'engine2'),
    'R' => array('N' => 'number', 'array_name' => 'references'),
    'PI' => array('N' => 'number', 'array_name' => 'installed'),
    'PR' => array('N' => 'number', 'array_name' => 'removed')
);
$data = array(
    'project' => array( 'array' => $data_project),
    'engine1' => array( 'array' => $data_engine1),
    'engine2' => array( 'array' => $data_engine2),
    'references' => array( 'array' => NULL, 'separator' => ';;'),
    'installed' => array( 'array' => NULL, 'separator' => ';;'),
    'removed' => array( 'array' => NULL, 'separator' => ';;'),
);

/* Manage Forms */

if($lvl_access>2) {
    if (isset($_POST['f_id_wo'])) {
        $_POST['f_approved'] = date('Y-m-d');
        $_POST['f_id_user'] = $_SESSION['user_id'];
    }

    $fields = array(
        'f_id_wo' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id_wo),
        'f_manual' => array('type' => 'number-int', 'required' => TRUE),
        'f_id_gst' => array('type' => 'number-int', 'required' => TRUE),
        'f_approved' => array('type' => 'date', 'required' => TRUE),
        'f_reference' => array('type' => 'text', 'required' => FALSE),
        'f_required' => array('type' => 'text', 'required' => FALSE),
        'f_rectification' => array('type' => 'text', 'required' => FALSE),
        'f_new_number' => array('type' => 'text', 'required' => FALSE),
        'f_description' => array('type' => 'text', 'required' => FALSE),
        'f_s_references' => array('type' => 'text', 'required' => FALSE),
        'f_s_remove' => array('type' => 'text', 'required' => FALSE),
        'f_s_install' => array('type' => 'text', 'required' => FALSE),
        'f_id_user' => array('type' => 'number-int', 'required' => TRUE)
    );
    $sql_request = 'CALL new_subtask(:f_id_wo, :f_manual, :f_id_gst, :f_reference, :f_required, :f_rectification, :f_approved, "no", :f_id_user,
                                     :f_new_number, :f_description,
                                     :f_s_references, :f_s_remove, :f_s_install);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_subtask_WO.php';
    try {
        $new_subtask_wo_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

        if ($new_subtask_wo_form->validateForm()) {
            $new_subtask_wo_form->sendForm();

            $last_insert = $bdd->query('SELECT MAX(ST_ID) AS ID FROM T_SUB_TASK;')->fetch()['ID'];
            $req = $bdd->prepare('SELECT * FROM T_SUB_TASK
                              INNER JOIN T_GENERIC_SUB_TASK ON ST_ID_GST = GST_ID
                              LEFT JOIN T_MANUAL ON GST_ID_M = M_ID
                              WHERE ST_ID=:id;');
            $req->execute(array('id' => $last_insert));
            $sub_task = $req->fetch();
            $data_current_user_work = get_current_user_work_for_user($bdd, $_SESSION['user_id']);
            $current_work_st = ($data_current_user_work)? $data_current_user_work['ST_ID']: 0;
            if($lvl_access > 2) { ?>
                <form id="delete_row-<?php echo $sub_task['ST_ID'];?>" method="post" style="display: none;">
                    <input type="hidden" name="f_delete" value="<?php echo $sub_task['ST_ID'];?>" />
                </form>
            <?php } ?>
            <form id="toggle-work_row-<?php echo $sub_task['ST_ID'];?>" method="post" style="display: none;">
                <input type="hidden" name="fw_toggle_user" value="<?php echo $_SESSION['user_id'];?>" />
                <input type="hidden" name="fw_toggle_st_id" value="<?php echo $sub_task['ST_ID'];?>" />
            </form>
            <form class="tr" id="row-<?php echo $sub_task['ST_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $sub_task['ST_ID'];?>', 'row-<?php echo $sub_task['ST_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                <?php include('row/workOrder.php');?>
            </form>
            <?php exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    $fields = array(
        'f_delete' => array('type' => 'hidden-int', 'required' => TRUE),
    );
    $sql_request = 'DELETE FROM T_SUB_TASK WHERE ST_ID=:f_delete;';
    try {
        $delete_subtask_ow_form = new Form($bdd, $fields, $sql_request);

        if($delete_subtask_ow_form->validateForm()) {
            $delete_subtask_ow_form->sendForm();
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}

$fields = array(
    'fe_st_id' => array('type' => 'hidden-int', 'required' => TRUE),
    'fe_st_reference' => array('type' => 'text', 'required' => FALSE),
    'fe_st_required' => array('type' => 'text', 'required' => FALSE),
    'fe_st_rectification' => array('type' => 'text', 'required' => FALSE),
    'fe_st_performed' => array('type' => 'date', 'required' => FALSE),
    'fe_st_performer' => array('type' => 'int', 'required' => FALSE),
    'fe_st_released' => array('type' => 'date', 'required' => FALSE),
    'fe_st_releaser' => array('type' => 'int', 'required' => FALSE),
    'fe_st_s_references' => array('type' => 'text', 'required' => FALSE),
    'fe_st_s_remove' => array('type' => 'text', 'required' => FALSE),
    'fe_st_s_install' => array('type' => 'text', 'required' => FALSE)
);
$sql_request = 'CALL update_subtask(:fe_st_id, :fe_st_reference, NULL, :fe_st_required, :fe_st_rectification,
                :fe_st_performed, :fe_st_performer, :fe_st_released, :fe_st_releaser, NULL,
                :fe_st_s_references, :fe_st_s_remove, :fe_st_s_install);';
try {
    $modify_subtask_wo_form = new Form($bdd, $fields, $sql_request);

    if($modify_subtask_wo_form->validateForm()) {
        $modify_subtask_wo_form->sendForm();

        $req = $bdd->prepare('SELECT * FROM T_SUB_TASK
                              INNER JOIN T_GENERIC_SUB_TASK ON ST_ID_GST = GST_ID
                              LEFT JOIN T_MANUAL ON GST_ID_M = M_ID
                              WHERE ST_ID=:id;');
        $req->execute(array('id' => (int)$_POST['fe_st_id']));
        $sub_task = $req->fetch();
        $data_current_user_work = get_current_user_work_for_user($bdd, $_SESSION['user_id']);
        $current_work_st = ($data_current_user_work)? $data_current_user_work['ST_ID']: 0;
        include('row/workOrder.php');
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}

$fields = array(
    'fw_toggle_user' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => (int)$_SESSION['user_id']),
    'fw_toggle_st_id' => array('type' => 'hidden-int', 'required' => TRUE)
);
$sql_request = 'CALL toggle_user_work_on_st(:fw_toggle_user, :fw_toggle_st_id);';
try {
    $modify_subtask_wo_form = new Form($bdd, $fields, $sql_request);

    if($modify_subtask_wo_form->validateForm()) {
        $modify_subtask_wo_form->sendForm();

        $req = $bdd->prepare('SELECT * FROM T_SUB_TASK
                              INNER JOIN T_GENERIC_SUB_TASK ON ST_ID_GST = GST_ID
                              LEFT JOIN T_MANUAL ON GST_ID_M = M_ID
                              WHERE ST_ID=:id;');
        $req->execute(array('id' => (int)$_POST['fw_toggle_st_id']));
        $sub_task = $req->fetch();
        $data_current_user_work = get_current_user_work_for_user($bdd, $_SESSION['user_id']);
        $current_work_st = ($data_current_user_work)? $data_current_user_work['ST_ID']: 0;
        include('row/workOrder.php');
        exit();
    }
} catch (Exception $error) {
    display_modal($error->getMessage());
    exit();
}