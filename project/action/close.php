<?php
/**
 * close action for PROJECT part
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

/* -------------------- Include Files -------------------- */

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/file_manager.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');

if(isset($_GET['wo']) AND !isset($_GET['delete']) AND !isset($_GET['delete_project'])) {
    $id_wo = (int) $_GET['wo'];

    if($id_wo > 0) {

        $data_project_parts = get_pp_list_by_po_for_wo($bdd, $id_wo);

        ?>
        <h2>Used Parts List</h2>
        <div class="table myTable">
            <div class="tr header">
                <span class="td" style="width:5%;">ID</span>
                <span class="td" style="width:15%;">ST Index</span>
                <span class="td" style="width:20%;">DESCRIPTION</span>
                <span class="td" style="width:20%;">PN</span>
                <span class="td" style="width:30%;">PO</span>
                <span class="td" style="width:10%;">QTY</span>
            </div>
            <?php foreach ($data_project_parts as $project_part) { ?>
                <div class="tr">
                    <span class="td"><?php echo $project_part['LPS_ID'];?></span>
                    <span class="td"><?php echo $project_part['GST_NUMBER'];?></span>
                    <span class="td"><?php echo $project_part['GP_NAME'];?></span>
                    <span class="td"><?php echo str_replace(';;','<br/>',$project_part['GP_NUMBER']);?></span>
                    <span class="td"><?php echo $project_part['S_PO_NAME'];?></span>
                    <span class="td"><?php echo $project_part['LPS_QUANTITY_NUMBER'];?></span>
                </div>
            <?php } ?>
        </div>
    <?php } else {
        display_modal('Error to load Parts information, did you ask a valid WO ?');
    }
    exit();
}

/*Get $project_info var from database*/
$id_project = (int) $_GET['id'];
if($id_project < 1) {
    header('Location: ./');
    exit();
}

if(isset($_GET['delete_project'])) {

    /* ---------- Close Project ---------- */
    $id_project_delete = (int)$_GET['delete_project'];
    if(check_token(300,$_SERVER['HTTP_HOST'].'/project/?page=close&id='.$id_project_delete.'&delete_project='.$id_project_delete,'close-project-'.$id_project_delete)) {

        if($id_project_delete < 1) {
            header('Location: ../error/404.php');
            exit();
        }

        /* Get Project information */
        $req = $bdd->prepare('CALL project_recapitulate_from_project_ID(:id);');
        $req->execute(array('id' => $id_project_delete));
        $data_project = $req->fetch();
        $req->closeCursor();

        /* Get Engine information */
        $req = $bdd->prepare('CALL engine_recapitulate(:e_id);');
        /* Engine 1 information */
        $req->execute(array('e_id' => $data_project['H_ID_E1']));
        $data_e1 = $req->fetch();
        $req->closeCursor();
        /* Engine 2 information */
        $req->execute(array('e_id' => $data_project['H_ID_E2']));
        $data_e2 = $req->fetch();
        $req->closeCursor();

        $req = $bdd->prepare('CALL remove_project(:id);');
        $req->execute(array('id' => $id_project_delete));
        $req->closeCursor();

        if(isset($data_project) AND $data_project) {
            if($data_project['H_ID_LOG_BOOK'] > 0)
                delete_file($data_project['H_ID_LOG_BOOK'], $bdd);

            if($data_project['H_ID_STATUS_SHEET'])
                delete_file($data_project['H_ID_STATUS_SHEET'], $bdd);
        }

        if(isset($data_e1) AND $data_e1)
            if($data_e1['E_ID_LOG_BOOK'])
                delete_file($data_e1['E_ID_LOG_BOOK'], $bdd);

        if(isset($data_e2) AND $data_e2)
            if($data_e2['E_ID_LOG_BOOK'])
                delete_file($data_e2['E_ID_LOG_BOOK'], $bdd);

    header('Location: index.php');

    }
}

if(isset($_GET['delete'])) {

    /* ---------- Close WO ---------- */
    $id_wo = (int)$_GET['delete'];
    if(check_token(300,$_SERVER['HTTP_HOST'].'/project/?page=close&id='.$id_project.'&delete='.$id_wo,'close-wo-'.$id_wo)) {

        if($id_wo < 1) {
            header('Location: ../error/404.php');
            exit();
        }

        $succeed = false;
        $data_wo = get_wo_info($bdd, $id_wo);
        $sow_name = str_replace('/', '.',str_replace('%DOC%','SOW',$id_wo.'-'.$data_wo['WO_NAME'].'.xlsx'));
        $wo_name = str_replace('/', '.',str_replace('%DOC%','WO',$id_wo.'-'.$data_wo['WO_NAME'].'.xlsx'));
        $erv_name = str_replace('/', '.',str_replace('%DOC%','ERV',$id_wo.'-'.$data_wo['WO_NAME'].'.xlsx'));
        $pif_name = str_replace('/', '.',str_replace('%DOC%','PIF',$id_wo.'-'.$data_wo['WO_NAME'].'.xlsx'));

        /**
         * Use in project/action/close.php to close project
         *
         * delete excel file
         *
         * @param string $path path of excel file
         *
         * @tags PROJECT Close
         * @source project/action/close.php
         */
        function deleteExcel($path) {
            if(file_exists($path)) {
                unlink($path);
            }
        }

        deleteExcel($_SERVER['DOCUMENT_ROOT'].'/files/sow/'.$sow_name);
        deleteExcel($_SERVER['DOCUMENT_ROOT'].'/files/wo/'.$wo_name);
        deleteExcel($_SERVER['DOCUMENT_ROOT'].'/files/ERV/'.$erv_name);
        deleteExcel($_SERVER['DOCUMENT_ROOT'].'/files/pif/'.$pif_name);

        /**
         * Use in project/action/close.php to close project
         *
         * Remove a part from stock
         *
         * @param PDO $database
         * @param int $id_lps lps that will be removed
         * @param int $id_part part that will be removed
         * @param int $qty quantity to remove
         *
         * @tags PROJECT Close
         * @source project/action/close.php
         */
        function removeFromStock(PDO &$database, $id_lps, $id_part, $qty)
        {

            $req = $database->prepare('DELETE FROM T_LINK_PP_S WHERE LPS_ID=:id;');
            $req->execute(array('id' => $id_lps));
            $req->closeCursor();

            $req = $database->prepare('SELECT * FROM T_STOCK WHERE S_ID=:id;');
            $req->execute(array('id' => $id_part));
            $part = $req->fetch();
            $req->closeCursor();

            if ($part['S_QUANTITY_NUMBER'] <= $qty) {

                $po_id = $part['S_ID_PURCHASE_ORDER'];
                $arc_id = $part['S_ID_ARC'];

                $req = $database->prepare('DELETE FROM T_STOCK WHERE S_ID=:id;');
                $req->execute(array('id' => $id_part));
                $req->closeCursor();

                if($po_id > 0)
                    delete_file($po_id, $database);
                if($arc_id > 0)
                    delete_file($arc_id, $database);

            } else {

                $req = $database->prepare('UPDATE T_STOCK SET S_QUANTITY_NUMBER=S_QUANTITY_NUMBER-:qty WHERE S_ID=:id;');
                $req->execute(array('id' => $id_part, 'qty' => $qty));
                $req->closeCursor();
            }
        }

        $data_project_parts = get_pp_list_by_po_for_wo($bdd, $id_wo);
        foreach($data_project_parts as $wo_part) {
            removeFromStock($bdd, $wo_part['LPS_ID'], $wo_part['LPS_ID_S'], $wo_part['LPS_QUANTITY_NUMBER']);
        }

        $req = $bdd->prepare('CALL remove_entire_wo(:id);');
        $req->execute(array('id' => $id_wo));
        $req->closeCursor();

        $succeed = true;
    } else {
        display_modal('Used token is not validated');
    }

}
