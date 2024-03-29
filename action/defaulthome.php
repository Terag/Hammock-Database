<?php
/**
 * defaultHome action for HOME part
 *
 * action file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Home\Action
 * @namespace Home
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'].'/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');

$fields = array(
    'f_uw_id' => array('type' => 'hidden-int', 'required' => TRUE),
);
$sql_request = 'CALL close_user_work(:f_uw_id);';
try {
    $toggle_user_work = new Form($bdd, $fields, $sql_request);

    if($toggle_user_work->validateForm()) {

        $toggle_user_work->sendForm();
        exit();
    }
} catch (Exception $error) {    display_modal($error->getMessage());
    exit();
}