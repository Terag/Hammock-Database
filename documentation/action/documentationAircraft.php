<?php
/**
 * documentationAircraft action for DOCUMENTATION part
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

$id_ga = (int) $_GET['id'];

if($lvl_access > 2) {
    /* If received New Manual form */
    $fields = array(
        'f_id_ga' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id_ga),
        'f_name' => array('type' => 'text', 'required' => TRUE),
        'f_description' => array('type' => 'text', 'required' => FALSE),
        'f_reference' => array('type' => 'text', 'required' => TRUE),
        'f_file' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/manual', 'file_name' => $id_ga . '-%var%', 'var' => 'f_name'),
    );
    $sql_request = 'CALL new_manual(:f_id_ga,:f_file,:f_name, :f_reference,:f_description);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_manual.php';
    try {
        $new_manual_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

        if ($new_manual_form->validateForm()) {
            $new_manual_form->sendForm();

            $last_insert = $bdd->query('SELECT MAX(M_ID) AS ID FROM T_MANUAL;')->fetch()['ID'];
            $req = $bdd->prepare('SELECT * FROM T_MANUAL LEFT JOIN T_FILE ON M_ID_MANUAL=F_ID WHERE M_ID=:id;');
            $req->execute(array('id' => $last_insert));
            $manual = $req->fetch(); ?>
            <form id="delete_row-<?php echo $manual['M_ID']; ?>" method="post" style="display: none;">
                <input type="hidden" name="f_delete" value="<?php echo $manual['M_ID']; ?>"/>
                <input type="hidden" name="f_delete_file" value="<?php echo $manual['M_ID_MANUAL']; ?>">
            </form>
            <form class="tr" id="row-<?php echo $manual['M_ID']; ?>"
                  onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $manual['M_ID']; ?>', 'row-<?php echo $manual['M_ID']; ?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;"
                  enctype="multipart/form-data" method="post">
                <?php include('row/documentationAircraft.php'); ?>
            </form>
            <?php exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    /* If received Update Manual form */
    $fields = array(
        'fe_m_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_m_name' => array('type' => 'text', 'required' => TRUE),
        'fe_m_reference' => array('type' => 'text', 'required' => TRUE),
        'fe_m_description' => array('type' => 'text', 'required' => FALSE),
        'fe_m_file' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/manual', 'file_name' => $id_ga . '-%var%', 'var' => 'fe_m_name'),
        'fe_m_delete_file' => array('type' => 'hidden-delete-file', 'required' => FALSE, 'cond' => 'fe_m_file')
    );
    $sql_request = 'CALL update_manual(:fe_m_id, :fe_m_file, :fe_m_name, :fe_m_reference, :fe_m_description);';
    try {
        $modify_manual_form = new Form($bdd, $fields, $sql_request);

        if ($modify_manual_form->validateForm()) {
            $modify_manual_form->sendForm();

            $req = $bdd->prepare('SELECT * FROM T_MANUAL LEFT JOIN T_FILE ON M_ID_MANUAL=F_ID WHERE M_ID=:id;');
            $req->execute(array('id' => (int)$_POST['fe_m_id']));
            $manual = $req->fetch();
            include('row/documentationAircraft.php');
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    /* If received Delete Manual form */
    $fields = array(
        'f_delete' => array('type' => 'number-int', 'required' => TRUE),
        'f_delete_file' => array('type' => 'hidden-delete-file', 'required' => FALSE)
    );
    $sql_request = 'DELETE FROM T_MANUAL WHERE M_ID = :f_delete;';
    try {
        $delete_manual_form = new Form($bdd, $fields, $sql_request);

        if ($delete_manual_form->validateForm()) {
            $delete_manual_form->sendForm();
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}