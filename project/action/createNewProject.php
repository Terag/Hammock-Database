<?php
/**
 * createNewProject action for PROJECT part
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
include($_SERVER['DOCUMENT_ROOT'].'/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/form.php');

$fields = array(
    /* Project Global */
    'f_p_name' => array('type' => 'text', 'required' => TRUE),
    'f_p_date' => array('type' => 'date', 'required' => TRUE, 'value' => date('Y-m-d')),
    /* Customer */
    'f_c_name' => array('type' => 'text', 'required' => TRUE),
    'f_c_phone' => array('type' => 'text', 'required' => FALSE),
    'f_c_mail' => array('type' => 'text', 'required' => FALSE),
    /* Helicopter */
    'f_h_id_ga' => array('type' => 'number-int', 'required' => TRUE),
    'f_h_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-helicopter-log_book', 'var' => 'f_p_name'),
    'f_h_status_sheet' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/status_sheet', 'file_name' => '%var%-status_sheet', 'var' => 'f_p_name'),
    'f_h_serial' => array('type' => 'text', 'required' => FALSE),
    'f_h_total_time' => array('type' => 'number-float', 'required' => FALSE),
    'f_h_landing' => array('type' => 'number-int', 'required' => FALSE),
    /* Engine 1 */
    'f_e1_id_ga' => array('type' => 'number-int', 'required' => TRUE),
    'f_e1_serial' => array('type' => 'text', 'required' => FALSE),
    'f_e1_total_time' =>  array('type' => 'number-float', 'required' => FALSE),
    'f_e1_ng_cycle' => array('type' => 'float', 'required' => FALSE),
    'f_e1_nf_cycle' => array('type' => 'float', 'required' => FALSE),
    'f_e1_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-engine1-log_book', 'var' => 'f_p_name'),
    /* Engine 2 */
    'f_e2_id_ga' => array('type' => 'number-int', 'required' => FALSE),
    'f_e2_serial' => array('type' => 'text', 'required' => FALSE),
    'f_e2_total_time' => array('type' => 'number-float', 'required' => FALSE),
    'f_e2_ng_cycle' => array('type' => 'float', 'required' => FALSE),
    'f_e2_nf_cycle' => array('type' => 'float', 'required' => FALSE),
    'f_e2_log_book' => array('type' => 'file', 'required' => FALSE, 'file_destination' => '/files/log_book', 'file_name' => '%var%-engine2-log_book', 'var' => 'f_p_name')
);
$sql_request = 'CALL create_project(
        :f_p_name, :f_p_date,
        :f_c_name, :f_c_phone, :f_c_mail,
        :f_h_id_ga, :f_h_log_book, :f_h_status_sheet, :f_h_serial, :f_h_total_time, :f_h_landing,
        :f_e1_id_ga, :f_e1_serial, :f_e1_total_time, :f_e1_ng_cycle, :f_e1_nf_cycle, :f_e1_log_book,
        :f_e2_id_ga, :f_e2_serial, :f_e2_total_time, :f_e2_ng_cycle, :f_e2_nf_cycle, :f_e2_log_book);';
$form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_genericAircraft.php';
try {
    $new_project_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

    if($new_project_form->validateForm()) {
        $new_project_form->sendForm();
        header('Location: ./?page=projectHome');
        exit();
    }
} catch (Exception $error) {
    $_SESSION['error'] = $error;
}