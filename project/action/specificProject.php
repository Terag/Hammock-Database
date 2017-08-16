<?php
/**
 * specificProject action for PROJECT part
 *
 * content file
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
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');
$id_project = (int) $_GET['id'];

$data_project = get_project_info($bdd, $id_project);
if(!$data_project) {
    header('Location: ./index.php');
    exit();
}

if($lvl_access > 2) {
    if (isset($_POST['f_date'])) {
        $_POST['f_name'] = $data_project['p_name'] . '-%DOC%-' . str_replace('-', '/', $_POST['f_date']) . '-' . $data_project['c_name'] . '-' . $data_project['h_serial'];
    }

    /* If received New Manual form */
    $fields = array(
        'f_id_p' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id_project),
        'f_name' => array('type' => 'text', 'required' => TRUE),
        'f_date' => array('type' => 'text', 'required' => TRUE)
    );
    $sql_request = 'CALL new_work_order(:f_id_p, :f_name, :f_date);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_document.php';
    try {
        $new_document_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

        if ($new_document_form->validateForm()) {
            $new_document_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }
}