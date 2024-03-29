<?php
/**
 * documentationManual action for DOCUMENTATION part
 *
 * action file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Documentation\Action
 * @namespace Documentation
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');
$id_m = (int) $_GET['id'];

if($lvl_access > 2) {
    /* If received New Sub Task form */
    $fields = array(
        'f_id_m' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id_m),
        'f_number' => array('type' => 'text', 'required' => TRUE),
        'f_description' => array('type' => 'text', 'required' => FALSE)
    );
    $sql_request = 'CALL new_generic_sub_task(:f_id_m,:f_ata_ref,:f_number,:f_description);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_genericSubTask.php';
    try {
        $new_generic_subtask_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

        if ($new_generic_subtask_form->validateForm()) {
            $new_generic_subtask_form->sendForm();

            $last_insert = $bdd->query('SELECT MAX(GST_ID) AS ID FROM T_GENERIC_SUB_TASK;')->fetch()['ID'];
            $req = $bdd->prepare('SELECT * FROM T_GENERIC_SUB_TASK LEFT JOIN T_ATA_REFERENCE ON GST_ID_AR=AR_ID WHERE GST_ID=:id;');
            $req->execute(array('id' => $last_insert));
            $sub_task = $req->fetch(); ?>
            <form id="delete_row-<?php echo $sub_task['GST_ID']; ?>" method="post" style="display: none;">
                <input type="hidden" name="f_delete" value="<?php echo $sub_tasks['GST_ID']; ?>"/>
            </form>
            <form class="tr" id="row-<?php echo $sub_task['GST_ID']; ?>"
                  onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $sub_task['GST_ID']; ?>', 'row-<?php echo $sub_task['GST_ID']; ?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;"
                  method="post">
                <?php include('row/documentationManual.php'); ?>
            </form>
            <?php exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    /* If received Update Sub Task form */
    $fields = array(
        'fe_gst_id' => array('type' => 'number-int', 'required' => TRUE),
        'fe_gst_number' => array('type' => 'text', 'required' => TRUE),
        'fe_gst_description' => array('type' => 'text', 'required' => FALSE)
    );
    $sql_request = 'CALL update_generic_sub_task(:fe_gst_id,:fe_gst_ata_ref,:fe_gst_number,:fe_gst_description);';
    try {
        $update_generic_subtask_form = new Form($bdd, $fields, $sql_request, $form_path);

        if ($update_generic_subtask_form->validateForm()) {
            $update_generic_subtask_form->sendForm();

            $req = $bdd->prepare('SELECT * FROM T_GENERIC_SUB_TASK LEFT JOIN T_ATA_REFERENCE ON GST_ID_AR=AR_ID WHERE GST_ID=:id;');
            $req->execute(array('id' => (int)$_POST['fe_gst_id']));
            $sub_task = $req->fetch();
            include('row/documentationManual.php');
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    /* If received Delete Sub Task form */
    $fields = array('f_delete' => array('type' => 'hidden-int', 'required' => TRUE));
    $sql_request = 'DELETE FROM T_GENERIC_SUB_TASK WHERE GST_ID=:f_delete;';
    try {
        $delete_generic_subtask_form = new Form($bdd, $fields, $sql_request, $form_path);

        if ($delete_generic_subtask_form->validateForm()) {
            $delete_generic_subtask_form->sendForm();
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}