<?php
/**
 * documentationSubTask action for DOCUMENTATION part
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
$id_gst = (int) $_GET['id'];

if($lvl_access > 2) {
    $fields = array(
        /* GP_GST Part */
        'f_id_gst' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id_gst),
        'f_id_gp' => array('type' => 'number-int', 'required' => TRUE),
        'f_qty' => array('type' => 'number-int', 'required' => TRUE),
        'f_ipc' => array('type' => 'number-int', 'required' => TRUE),
        /* GP Part */
        'f_number' => array('type' => 'text', 'required' => FALSE),
        'f_name' => array('type' => 'text', 'required' => FALSE),
        'f_type' => array('type' => 'text', 'required' => FALSE),
        'f_location' => array('type' => 'test', 'required' => FALSE),
        'f_description' => array('type' => 'text', 'required' => FALSE)
    );
    $sql_request = 'CALL add_link_gp_gst(:f_id_gp, :f_id_gst, :f_qty, :f_name, :f_number, :f_type, :f_location, :f_description);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_GPinGST.php';
    try {
        $add_link_gp_gst_form = new Form_modal($bdd, $fields, $sql_request, $form_path);
        if ($add_link_gp_gst_form->validateForm()) {
            $add_link_gp_gst_form->sendForm();

            $last_insert = $bdd->query('SELECT MAX(LGG_ID) AS ID FROM T_LINK_GP_GST;')->fetch()['ID'];
            $req = $bdd->prepare('SELECT * FROM T_LINK_GP_GST
                                  LEFT JOIN T_GENERIC_SUB_TASK ON LGG_ID_GST=GST_ID
                                  LEFT JOIN T_GENERIC_PART ON LGG_ID_GP=GP_ID
                                  WHERE LGG_ID=:id;');
            $req->execute(array('id' => $last_insert));
            $sub_task_part = $req->fetch(); ?>
            <form id="delete_row-<?php echo $sub_task_part['LGG_ID']; ?>" method="post" style="display: none;">
                <input type="hidden" name="f_delete" value="<?php echo $sub_task_part['LGG_ID']; ?>"/>
            </form>
            <form class="tr" id="row-<?php echo $sub_task_part['LGG_ID']; ?>"
                  onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $sub_task_part['LGG_ID']; ?>', 'row-<?php echo $sub_task_part['LGG_ID']; ?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;"
                  method="post">
                <?php include('row/documentationSubTask.php'); ?>
            </form>
            <?php exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    /* If received Update Sub Task Part form */
    $fields = array(
        'fe_lgg_id' => array('type' => 'number-int', 'required' => TRUE),
        'fe_lgg_qty' => array('type' => 'number-int', 'required' => TRUE),
        'fe_lgg_ipc' => array('type' => 'text', 'required' => TRUE)
    );
    $sql_request = 'CALL update_link_gp_gst(:fe_lgg_id, :fe_lgg_qty, :fe_lgg_ipc);';
    try {
        $update_link_gp_gst_form = new Form($bdd, $fields, $sql_request, $form_path);

        if ($update_link_gp_gst_form->validateForm()) {
            $update_link_gp_gst_form->sendForm();

            $req = $bdd->prepare('SELECT * FROM T_LINK_GP_GST
                                  LEFT JOIN T_GENERIC_SUB_TASK ON LGG_ID_GST=GST_ID
                                  LEFT JOIN T_GENERIC_PART ON LGG_ID_GP=GP_ID
                                  WHERE LGG_ID=:id;');
            $req->execute(array('id' => (int)$_POST['fe_lgg_id']));
            $sub_task_part = $req->fetch();
            include('row/documentationSubTask.php');
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    $fields = array('f_delete' => array('type' => 'hidden-int', 'required' => TRUE));
    $sql_request = 'DELETE FROM T_LINK_GP_GST WHERE LGG_ID=:f_delete;';
    try {
        $delete_link_gp_gst_form = new Form($bdd, $fields, $sql_request);
        if ($delete_link_gp_gst_form->validateForm()) {
            $delete_link_gp_gst_form->sendForm();

            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}