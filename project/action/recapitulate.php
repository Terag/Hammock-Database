<?php
/**
 * recapitulate action for PROJECT part
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

include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'].'/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/form.php');
$id = (int) $_GET['id'];

if($lvl_access > 2) {
    /* If received Update Project form */
    $fields = array(
        'fe_p_id' => array('type' => 'hidden-int', 'required' => TRUE, 'value' => $id),
        'fe_p_opened_date' => array('type' => 'date', 'required' => TRUE),
        'fe_p_closed_date' => array('type' => 'date', 'required' => FALSE),
    );
    $sql_request = 'CALL update_project(:fe_p_id, :fe_p_opened_date, :fe_p_closed_date);';
    try {
        $modify_customer_form = new Form($bdd, $fields, $sql_request);

        if ($modify_customer_form->validateForm()) {
            $modify_customer_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    /* If received Update Customer form */
    $fields = array(
        'fe_c_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_c_name' => array('type' => 'text', 'required' => TRUE),
        'fe_c_phone' => array('type' => 'phone', 'required' => FALSE),
        'fe_c_mail' => array('type' => 'mail', 'required' => FALSE)
    );
    $sql_request = 'CALL update_customer(:fe_c_id, :fe_c_name, :fe_c_phone, :fe_c_mail);';
    try {
        $modify_customer_form = new Form($bdd, $fields, $sql_request);

        if ($modify_customer_form->validateForm()) {
            $modify_customer_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    /* If received Update Helicopter form */
    $fields = array(
        'fe_h_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_h_p_name' => array('type' => 'var', 'required' => TRUE),
        'fe_h_serial' => array('type' => 'text', 'required' => FALSE),
        'fe_h_time' => array('type' => 'number-float', 'required' => FALSE),
        'fe_h_landing' => array('type' => 'number-int', 'required' => FALSE),
        'fe_h_status_sheet' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/status_sheet', 'file_name' => '%var%-status_sheet', 'var' => 'fe_h_p_name'),
        'fe_h_delete_status_sheet' => array('type' => 'hidden-delete-file', 'required' => FALSE, 'cond' => 'fe_h_status_sheet'),
        'fe_h_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-helicopter-log_book', 'var' => 'fe_h_p_name'),
        'fe_h_delete_log_book' => array('type' => 'hidden-delete-file', 'required' => FALSE, 'cond' => 'fe_h_log_book')
    );
    $sql_request = 'CALL update_helicopter(:fe_h_id, :fe_h_serial, :fe_h_time, :fe_h_landing, :fe_h_status_sheet, :fe_h_log_book);';
    try {
        $modify_helicopter_form = new Form($bdd, $fields, $sql_request);

        if ($modify_helicopter_form->validateForm()) {
            $modify_helicopter_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    /* If received Update Engine 1 form */
    $fields = array(
        'fe_e1_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_e1_p_name' => array('type' => 'var', 'required' => TRUE),
        'fe_e1_serial' => array('type' => 'text', 'required' => FALSE),
        'fe_e1_time' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e1_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-engine1-log_book', 'var' => 'fe_e1_p_name'),
        'fe_e1_ng_cycle' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e1_nf_cycle' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e1_delete_log_book' => array('type' => 'hidden-delete-file', 'required' => FALSE, 'cond' => 'fe_e1_log_book')
    );
    $sql_request = 'CALL update_engine( :fe_e1_id, :fe_e1_serial, :fe_e1_time, :fe_e1_ng_cycle, :fe_e1_nf_cycle, :fe_e1_log_book);';
    try {
        $modify_engine1_form = new Form($bdd, $fields, $sql_request);

        if ($modify_engine1_form->validateForm()) {
            $modify_engine1_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    /* If received Update Engine 2 form */
    $fields = array(
        'fe_e2_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_e2_p_name' => array('type' => 'var', 'required' => TRUE),
        'fe_e2_serial' => array('type' => 'text', 'required' => FALSE),
        'fe_e2_time' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e2_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-engine2-log_book', 'var' => 'fe_e2_p_name'),
        'fe_e2_ng_cycle' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e2_nf_cycle' => array('type' => 'number-float', 'required' => FALSE),
        'fe_e2_delete_log_book' => array('type' => 'hidden-delete-file', 'required' => FALSE, 'cond' => 'fe_e2_log_book')
    );
    $sql_request = 'CALL update_engine( :fe_e2_id, :fe_e2_serial, :fe_e2_time, :fe_e2_ng_cycle, :fe_e2_nf_cycle, :fe_e2_log_book);';
    try {
        $modify_engine2_form = new Form($bdd, $fields, $sql_request);

        if ($modify_engine2_form->validateForm()) {
            $modify_engine2_form->sendForm();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '');
            exit();
        }
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }
}