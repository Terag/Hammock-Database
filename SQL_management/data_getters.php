<?php
/**
 * This file provides a set of function to manage files sent by form
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package SQL
 * @namespaces SQL
 * @filesource
 */

/** ------------------------------- Project Getters ------------------------------- */

/**
 * get info from database about a specific project
 *
 * SQL: `CALL project_specific(:id);`
 *
 * ARRAY:
 * - P_ID id
 * - P_NAME p_name
 * - C_NAME c_name
 * - GA_NAME ga_name
 * - H_SERIAL h_serial
 *
 * @param PDO $database
 * @param int $id_project
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_project_info(PDO &$database, $id_project) {
    $req = $database->prepare('CALL project_specific(:id);');
    $req->execute(array('id' => $id_project));
    return $req->fetch();
}

/**
 * get info from database about a specific project from helico id
 *
 * SQL: `CALL project_recapitulate_from_helicopter_ID(:id);`
 *
 * ARRAY:
 * - P_ID
 * - P_ID_C
 * - P_ID_H
 * - P_NAME
 * - P_OPENED_DATE
 * - P_CLOSED_DATE
 *
 * @param PDO $database
 * @param int $id_helico
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_project_info_from_helico(PDO &$database, $id_helico) {
    $req = $database->prepare('CALL project_recapitulate_from_helicopter_ID(:id);');
    $req->execute(array('id' => $id_helico));
    return $req->fetch();
}

/**
 * get info from database about a all projects
 *
 * SQL: `CALL project_list();`
 *
 * ARRAY OF ARRAY:
 * - P_ID id
 * - P_NAME p_name
 * - C_NAME c_name
 * - GA_NAME ga_name
 * - H_SERIAL h_serial
 * - H_ID h_id
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_project_list(PDO &$database) {
    $req = $database->query('CALL project_list();');
    return $req->fetchAll();
}

/** ------------------------------- Work Order Getters ------------------------------- */

/**
 * get info from database about a specific work order
 *
 * SQL: `SELECT * FROM T_WORK_ORDER WHERE WO_ID=:id_wo;`
 *
 * ARRAY:
 * - WO_ID
 * - WO_ID_H
 * - WO_NAME
 * - WO_OPENED_DATE
 * - WO_CLOSED_DATE
 *
 * @param PDO $database
 * @param int $id_wo
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_wo_info(PDO &$database, $id_wo) {
    $req = $database->prepare('SELECT * FROM T_WORK_ORDER WHERE WO_ID=:id_wo;');
    $req->execute(array('id_wo' => $id_wo));
    return $req->fetch();
}

/**
 * get info from database about all work order for a project
 *
 * SQL: `CALL work_order_name_list_for_specific_project(:id);`
 *
 * ARRAY OF ARRAY:
 * - WO_ID
 * - WO_NAME
 *
 * @param PDO $database
 * @param int $id_project
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_wo_name_list_for_project(PDO &$database, $id_project) {
    $req_wo = $database->prepare('CALL work_order_name_list_for_specific_project(:id);');
    $req_wo->execute(array('id' => $id_project));
    return $req_wo->fetchAll();
}

/** ------------------------------- Sub Task Getters ------------------------------- */

/**
 * get info from database about all sub tasks for a work order
 *
 * SQL: `CALL sub_task_list_for_specific_work_order(:id_wo);`
 *
 * ARRAY OF ARRAY:
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_MANUAL.*
 *
 * @param PDO $database
 * @param int $id_wo
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_st_list_for_wo(PDO &$database, $id_wo) {
    $req = $database->prepare('CALL sub_task_list_for_specific_work_order(:id_wo);');
    $req->execute(array('id_wo' => $id_wo));
    return $req->fetchAll();
}

/**
 * get info from database about all generic sub tasks for a manual
 *
 * SQL: `SELECT * FROM T_GENERIC_SUB_TASK WHERE GST_ID_M=:id ORDER BY GST_NUMBER;`
 *
 * ARRAY OF ARRAY:
 * - GST_ID
 * - GST_ID_M
 * - GST_NUMBER
 * - GST_DESCRIPTION
 *
 * @param PDO $database
 * @param int $id_manual
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_gst_list_for_manual(PDO &$database, $id_manual) {
    $req = $database->prepare('SELECT * FROM T_GENERIC_SUB_TASK WHERE GST_ID_M=:id ORDER BY GST_NUMBER;');
    $req->execute(array('id' => $id_manual));
    return $req->fetchAll();
}

/**
 * get info from database about all not performed sub tasks
 *
 * SQL: `CALL not_performed_sub_task_list();`
 *
 * ARRAY OF ARRAY:
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_WORK_ORDER.*
 * - T_PROJECT.*
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_not_performed_sub_task_list(PDO &$database) {
    $req = $database->query('CALL not_performed_sub_task_list();');
    return $req->fetchAll();
}

/** ------------------------------- User Getter ------------------------------- */

/**
 * get info from database about an user
 *
 * SQL: `SELECT * FROM T_USER WHERE U_ID=:id;`
 *
 * ARRAY:
 * - U_ID
 * - U_NAME
 * - U_PASSWORD
 * - U_STATUS
 * - U_CODE
 *
 * @param PDO $database
 * @param int $id_user
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_user_info(PDO &$database, $id_user) {
    $req = $database->prepare('SELECT * FROM T_USER WHERE U_ID=:id;');
    $req->execute(array('id' => $id_user));
    return $req->fetch();
}

/**
 * get info from database about all users
 *
 * SQL: `SELECT U_ID, U_NAME, U_CODE FROM T_USER;`
 *
 * ARRAY OF ARRAY
 * - U_ID
 * - U_NAME
 * - U_CODE
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_user_list(PDO &$database) {
    $req = $database->query('SELECT U_ID, U_NAME, U_CODE FROM T_USER;');
    return $req->fetchAll();
}

/** ------------------------------- Manual Getters ------------------------------- */

/**
 * get info from database about all manuals for an aircraft
 *
 * SQL: `SELECT M_ID, M_NAME, GA_NAME FROM T_MANUAL INNER JOIN T_GENERIC_AIRCRAFT ON M_ID_GA=GA_ID WHERE M_ID_GA=:id ORDER BY M_NAME;`
 *
 * ARRAY OF ARRAY:
 * - M_ID
 * - M_NAME
 * - GA_NAME
 *
 * @param PDO $database
 * @param int $id_aircraft
 * @return array|null array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_manual_list_for_aircraft(PDO &$database, $id_aircraft) {
    if($id_aircraft != NULL) {
        $req = $database->prepare('SELECT M_ID, M_NAME, GA_NAME FROM T_MANUAL INNER JOIN T_GENERIC_AIRCRAFT ON M_ID_GA=GA_ID WHERE M_ID_GA=:id ORDER BY M_NAME;');
        $req->execute(array('id' => $id_aircraft));
        return $req->fetchAll();
    } else {
        return null;
    }
}

/** ------------------------------- Engine Getters ------------------------------- */

/**
 * get info from database about an engine
 *
 * SQL: `SELECT * FROM T_ENGINE INNER JOIN T_GENERIC_AIRCRAFT ON E_ID_GA=GA_ID WHERE E_ID=:id`
 *
 * ARRAY:
 * - E_ID
 * - E_ID_GA
 * - E_ID_LOG_BOOK
 * - E_SERIAL
 * - E_NAME
 * - E_TYPE
 * - E_TIME
 * - E_NG_CYCLE
 * - E_NF_CYCLE
 *
 * @param PDO $database
 * @param int $id_engine
 * @return mixed array data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_engine_info(PDO &$database, $id_engine) {
    $req = $database->prepare('SELECT * FROM T_ENGINE INNER JOIN T_GENERIC_AIRCRAFT ON E_ID_GA=GA_ID WHERE E_ID=:id');
    $req->execute(array('id' => $id_engine));
    return $req->fetch();
}

/** ------------------------------- Project Part Getters ------------------------------ */

/**
 * get info from database about all project parts for a WO
 *
 * Used for ERV
 *
 * SQL: `CALL project_part_list_for_specific_work_order(:id);`
 *
 * ARRAY OF ARRAY:
 * - T_PROJECT_PART.*
 * - T_GENERIC_PART.*
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 *
 * @param PDO $database
 * @param int $wo_id
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_pp_list_for_wo(PDO &$database, $wo_id) {
    $req = $database->prepare('CALL project_part_list_for_specific_work_order(:id);');
    $req->execute(array('id' => $wo_id));
    return $req->fetchAll();
}

/**
 * get info from database about all project parts by po for work order
 *
 * SQL: `CALL project_part_list_by_po_for_specific_work_order(:id);`
 *
 * ARRAY OF ARRAY:
 * - T_LINK_PP_S.*
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_WORK_ORDER.*
 * - T_STOCK.*
 * - T_GENERIC_PART.*
 *
 * @param PDO $database
 * @param int $wo_id
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_pp_list_by_po_for_wo(PDO &$database, $wo_id) {
    $req = $database->prepare('CALL project_part_list_by_po_for_specific_work_order(:id);');
    $req->execute(array('id' => $wo_id));
    return $req->fetchAll();
}

/**
 * get info from database about link_pp_s for project part and stock
 *
 * SQL: `CALL lps_for_pp_s_id(:pp_id, :s_id);`
 *
 * ARRAY OF ARRAY:
 * - LPS_ID
 * - LPS_ID_PP
 * - LPS_ID_S
 * - LPS_QUANTITY_NUMBER
 * - LPS_DATE_OUT
 *
 * @param PDO $database
 * @param int $pp_id
 * @param int $s_id
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_lps_for_pp_s_id(PDO &$database, $pp_id, $s_id) {
    $req = $database->prepare('CALL lps_for_pp_s_id(:pp_id, :s_id);');
    $req->execute(array('pp_id' => $pp_id, 's_id' => $s_id));
    return $req->fetch();
}

/** ------------------------------- Generic Part Getters --------------------------------- */

/**
 * get info from database about all generic parts
 *
 * SQL: `CALL generic_part_list();`
 *
 * ARRAY OF ARRAY:
 * - GP_ID
 * - GP_NUMBER
 * - GP_NAME
 * - GP_TYPE
 * - GP_LOCATION
 * - GP_DESCRIPTION
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_gp_list(PDO &$database) {
    $req = $database->query('CALL generic_part_list();');
    return $req->fetchAll();
}

/**
 * get info from database about if a part is available or not
 *
 * SQL: `CALL generic_part_by_part_number(:number, :id);`
 *
 * ARRAY OF ARRAY:
 * - GP_ID
 *
 * @param PDO $database
 * @param int $number
 * @param int $id
 * @return bool
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function is_pn_available(PDO &$database, $number, $id=0) {
    $req = $database->prepare('CALL generic_part_by_part_number(:number, :id);');
    $req->execute(array('number' => $number, 'id' => $id));
    return ($req->fetch())? false : true;
}

/** ------------------------------ Stock Part ------------------------------------------- */

/**
 * get info from database about all stock parts
 *
 * SQL: `CALL stock_list();`
 *
 * ARRAY OF ARRAY:
 * - T_STOCK.*
 * - T_GENERIC_PART.*
 * - T_FILE po.F_ID                         AS PO_ID
 * - T_FILE po.F_DIRECTORY                  AS PO_DIRECTORY
 * - T_FILE po.F_NAME                       AS PO_NAME
 * - T_FILE po.F_FORMAT                     AS PO_FORMAT
 * - T_FILE arc.F_ID                        AS ARC_ID
 * - T_FILE arc.F_DIRECTORY                 AS ARC_DIRECTORY
 * - T_FILE arc.F_NAME                      AS ARC_NAME
 * - T_FILE arc.F_FORMAT                    AS ARC_FORMAT
 * - SUM(COALESCE(LPS_QUANTITY_NUMBER,0))   AS S_QUANTITY_USED
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_stock_list(PDO &$database) {
    $req = $database->query('CALL stock_list();');
    return $req->fetchAll();
}

/**
 * get info from database about all available stock parts
 *
 * SQL: `CALL available_stock_list();`
 *
 * ARRAY OF ARRAY:
 * - T_STOCK.*
 * - T_GENERIC_PART.*
 * - T_FILE po.F_ID                                             AS PO_ID
 * - T_FILE po.F_DIRECTORY                                      AS PO_DIRECTORY
 * - T_FILE po.F_NAME                                           AS PO_NAME
 * - T_FILE po.F_FORMAT                                         AS PO_FORMAT
 * - T_FILE arc.F_ID                                            AS ARC_ID
 * - T_FILE arc.F_DIRECTORY                                     AS ARC_DIRECTORY
 * - T_FILE arc.F_NAME                                          AS ARC_NAME
 * - T_FILE arc.F_FORMAT                                        AS ARC_FORMAT
 * - S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0))   AS S_QUANTITY_AVAILABLE
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_available_stock_list(PDO &$database) {
    $req = $database->query('CALL available_stock_list();');
    return $req->fetchAll();
}

/**
 * get info from database about all available stock parts by po from gp
 *
 * SQL: `CALL available_stock_list_by_po_from_gp_id(:id);`
 *
 * ARRAY OF ARRAY:
 * - T_STOCK.*
 * - SUM(COALESCE(LPS_QUANTITY_NUMBER,0))                       AS S_QUANTITY_USED
 * - S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0))   AS S_QUANTITY_AVAILABLE
 *
 * @param PDO $database
 * @param int $gp_id
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_stock_available_list_by_po_from_gp_id(PDO &$database, $gp_id) {
    $req = $database->prepare('CALL available_stock_list_by_po_from_gp_id(:id);');
    $req->execute(array('id' => $gp_id));
    return $req->fetchAll();
}

/**
 * get info from database about all used stock parts by po for pp
 *
 * SQL: `CALL used_stock_list_by_po_from_pp(:id);`
 *
 * ARRAY OF ARRAY:
 * - LPS_ID
 * - S_ID_GP
 * - S_RECEIVED_DATE
 * - S_RECEIVED
 * - S_ID_PURCHASE_ORDER
 * - S_PO_NAME
 * - LPS_QUANTITY_NUMBER
 *
 * @param PDO $database
 * @param int $pp_id
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_stock_used_list_by_po_from_pp_id(PDO &$database, $pp_id) {
    $req = $database->prepare('CALL used_stock_list_by_po_from_pp(:id);');
    $req ->execute(array('id' => $pp_id));
    return $req->fetchAll();
}

/**
 * get info from database about all ordered parts
 *
 * SQL: `CALL ordered_part_list();`
 *
 * ARRAY OF ARRAY:
 * - T_STOCK AS s.*
 * - T_GP AS gp.*
 * - T_FILE po.F_ID             AS PO_ID
 * - T_FILE po.F_DIRECTORY      AS PO_DIRECTORY
 * - T_FILE po.F_NAME           AS PO_NAME
 * - T_FILE po.F_FORMAT         AS PO_FORMAT
 * - T_FILE arc.F_ID            AS ARC_ID
 * - T_FILE arc.F_DIRECTORY     AS ARC_DIRECTORY
 * - T_FILE arc.F_NAME          AS ARC_NAME
 * - T_FILE arc.F_FORMAT        AS ARC_FORMAT
 *
 * @param PDO $database
 * @return array
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_ordered_parts(PDO &$database) {
    $req = $database->query('CALL ordered_part_list();');
    return $req->fetchAll();
}

/** ------------------------------ Generic Aircraft ------------------------------------------- */

/**
 * get info from database about all generic aircraft by type
 *
 * SQL: `CALL generic_aircraft_list_by_type(:type);`
 *
 * ARRAY OF ARRAY:
 * - GA_ID
 * - GA_NAME
 * - GA_CONSTRUCTOR
 * - GA_TYPE
 *
 * @param PDO $database
 * @param int $type
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_generic_aircraft_list_by_type(PDO &$database, $type) {
    $req = $database->prepare('CALL generic_aircraft_list_by_type(:type);');
    $req->execute(array('type' => $type));
    return $req->fetchAll();
}

/** ------------------------------ Vendor ------------------------------------------- */

/**
 * get info from database about all vendor
 *
 * SQL: `SELECT * FROM T_VENDOR;`
 *
 * ARRAY OF ARRAY:
 * - V_ID
 * - V_NAME
 * - V_ADDRESS
 * - V_CITY
 * - V_COUNTRY
 * - V_PHONE
 * - V_MAIL
 * - V_CONTACT
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_vendor_list(PDO &$database) {
    $req = $database->query('SELECT * FROM T_VENDOR;');
    return $req->fetchAll();
}

/** ------------------------------ Recorded Work ------------------------------------------- */

/**
 * get info from database about all recorded work
 *
 * SQL: `CALL recorded_work_list();`
 *
 * ARRAY OF ARRAY:
 * - T_RECORDED_WORK.*
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_WORK_ORDER.*
 * - T_PROJECT.*
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_recorded_work_list(PDO &$database) {
    $req = $database->query('CALL recorded_work_list();');
    return $req->fetchAll();
}

/** ------------------------------ User Work ----------------------------------------------- */

/**
 * get info from database about all user work for a recorded work
 *
 * SQL: `CALL user_work_for_rw(:id_rw);`
 *
 * ARRAY OF ARRAY:
 * - UW_ID
 * - UW_ID_RW
 * - UW_ID_U
 * - UW_DATETIME_BEGIN
 * - UW_DATETIME_END
 *
 * @param PDO $database
 * @param int $id_rw
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_user_work_for_rw(PDO &$database, $id_rw) {
    $req = $database->prepare('CALL user_work_for_rw(:id_rw);');
    $req->execute(array('id_rw' => $id_rw));
    return $req->fetchAll();
}

/**
 * get info from database about all user work for a recorded work and user
 *
 * SQL: `CALL user_work_for_rw_and_user(:id_rw, :id_u);`
 *
 * ARRAY OF ARRAY:
 * - UW_ID
 * - UW_ID_RW
 * - UW_ID_U
 * - UW_DATETIME_BEGIN
 * - UW_DATETIME_END
 *
 * @param PDO $database
 * @param int $id_rw
 * @param int $id_user
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_user_work_for_rw_and_user(PDO &$database, $id_rw, $id_user) {
    $req = $database->prepare('CALL user_work_for_rw_and_user(:id_rw, :id_u);');
    $req->execute(array('id_rw' => $id_rw, 'id_u' => $id_user));
    return $req->fetchAll();
}

/**
 * get info from database about all user work for user
 *
 * SQL: `CALL current_user_work_for_user(:id_user);`
 *
 * ARRAY OF ARRAY:
 * - T_USER_WORK.*
 * - T_RECORDED_WORK.*
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_WORK_ORDER.*
 * - T_PROJECT.*
 *
 * @param PDO $database
 * @param int $id_user
 * @return mixed array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_current_user_work_for_user(PDO &$database, $id_user) {
    $req = $database->prepare('CALL current_user_work_for_user(:id_user);');
    $req->execute(array('id_user' => $id_user));
    return $req->fetch();
}

/**
 * get info from database about all user work
 *
 * SQL: `CALL current_user_work_list();`
 *
 * ARRAY OF ARRAY:
 * - T_USER_WORK.*
 * - T_USER.*
 * - T_RECORDED_WORK.*
 * - T_SUB_TASK.*
 * - T_GENERIC_SUB_TASK.*
 * - T_WORK_ORDER.*
 * - T_PROJECT.*
 *
 * @param PDO $database
 * @return array array of data
 *
 * @tags SQL Getter
 * @source SQL_management\data_getters.php
 */
function get_current_user_work_list(PDO &$database) {
    $req = $database->query('CALL current_user_work_list();');
    return $req->fetchAll();
}