<?php
/**
 * managementHome action for MANAGEMENT part
 *
 * action file
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Management\Action
 * @namespace Management
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/ui/modal_warning.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/form.php');

/**
 * transform a int number a minutes to hour string format
 *
 * @param int $min time in minutes
 * @return string format Ex: "4h35"
 *
 * @tags Calendar
 * @source management\action\managementHome.php
 */
function min_to_hour($min) {
    return floor($min/60).'h'.$min%60;
}

if(isset($_GET['excel'])) {
    include('excel/work_hours.php');
    exit();
}

if($lvl_access > 2) {

    $fields = array(
        'f_id_st' => array('type' => 'number-int', 'required' => TRUE),
        'f_description' => array('type' => 'text', 'required' => FALSE)
    );
    $sql_request = 'CALL new_recorded_work(:f_id_st, :f_description);';
    $form_path = $_SERVER['DOCUMENT_ROOT'] . '/form/new_dailyTask.php';
    try {
        $new_daily_task = new Form_modal($bdd, $fields, $sql_request, $form_path);

        if($new_daily_task->validateForm()) {

            $new_daily_task->sendForm();

            $last_insert = $bdd->query('SELECT MAX(RW_ID) AS ID FROM T_RECORDED_WORK;')->fetch()['ID'];
            $req = $bdd->prepare('SELECT * FROM T_RECORDED_WORK
                                    LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
                                    LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                                    LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
                                    LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
                                    WHERE RW_ID=:id;');
            $req->execute(array('id' => $last_insert));
            $recorded_work = $req->fetch();
            $data_user_works = get_user_work_for_rw($bdd, $recorded_work['RW_ID']);
            $isCurrent = false;
            foreach($data_user_works as $user_work){$isCurrent = ($isCurrent || !isset($user_work['UW_DATETIME_END']));}
            ?>
            <form id="delete_row-<?php echo $recorded_work['RW_ID'];?>" method="post" style="display: none;">
                <input type="hidden" name="f_delete" value="<?php echo $recorded_work['RW_ID'];?>" />
            </form>
            <form class="tr ALL <?php echo ($recorded_work['RW_ID_ST']!=NULL)? 'PROJECT h-0 h-'.$recorded_work['WO_ID_H'] : 'DAILY'.(($isCurrent)? ' CURRENT' : '');?>" id="row-<?php echo $recorded_work['RW_ID'];?>" onsubmit="xmlhttpPost(document.location.href, 'row-<?php echo $recorded_work['RW_ID'];?>', 'row-<?php echo $recorded_work['RW_ID'];?>', '<span class=\'td\'><img src=\'/img/wait_rot.gif\'/></span>');return false;" method="post">
                <?php include('row/managementHome.php');?>
            </form>
            <?php exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }

    if(isset($_POST['fe_rw_id'])) {
        $_POST['fe_tt_min']=(int)$_POST['fe_rw_tt_h']*60 + (int)$_POST['fe_rw_tt_m'];

        $fields = array(
            'fe_rw_id' => array('type' => 'hidden-int', 'required' => TRUE),
            'fe_tt_min' => array('type' => 'number-int', 'required' => TRUE),
            'fe_rw_description' => array('type' => 'text', 'required' => FALSE)
        );
        $sql_request = 'CALL update_recorded_work(:fe_rw_id, :fe_tt_min, :fe_rw_description);';
        try {
            $update_rw_form = new Form_modal($bdd, $fields, $sql_request, $form_path);

            if($update_rw_form->validateForm()) {

                $update_rw_form->sendForm();
            }
        } catch (Exception $error) {
            display_modal($error->getMessage());
            exit();
        }

        if(isset($_POST['fe_uw_new_performer']) AND (int)$_POST['fe_uw_new_performer'] > 0) {
            $fields = array(
                'fe_rw_id' => array('type' => 'hidden-int', 'required' => TRUE),
                'fe_uw_new_performer' => array('type' => 'hidden-int', 'required' => TRUE)
            );
            $sql_request = 'CALL new_user_work(:fe_rw_id, :fe_uw_new_performer);';
            try {
                $new_user_work = new Form_modal($bdd, $fields, $sql_request, $form_path);

                if($new_user_work->validateForm()) {

                    $new_user_work->sendForm();
                }
            } catch (Exception $error) {
                display_modal($error->getMessage());
                exit();
            }
        }

        if(isset($_POST['fe_uw_performer_number']) AND (int)$_POST['fe_uw_performer_number'] > 0) {

            for($i = 0; $i < (int)$_POST['fe_uw_performer_number']; $i++) {

                if(isset($_POST['fe_uw_close_performer_'.$i])) {
                    $fields = array(
                            'fe_uw_close_performer_'.$i => array('type' => 'hidden-int', 'required' => TRUE)
                    );
                    $sql_request = 'CALL close_user_work(:fe_uw_close_performer_'.$i.');';
                    try {
                        $close_user_work = new Form_modal($bdd, $fields, $sql_request, $form_path);

                        if($close_user_work->validateForm()) {

                            $close_user_work->sendForm();
                        }
                    } catch (Exception $error) {
                        display_modal($error->getMessage());
                        exit();
                    }
                }
            }
        }

        $req = $bdd->prepare('SELECT * FROM T_RECORDED_WORK
                                    LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
                                    LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                                    LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
                                    LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
                                    WHERE RW_ID=:id;');
        $req->execute(array('id' => (int)$_POST['fe_rw_id']));
        $recorded_work = $req->fetch();
        $data_user_works = get_user_work_for_rw($bdd, $recorded_work['RW_ID']);
        $isCurrent = false;
        foreach($data_user_works as $user_work){$isCurrent = ($isCurrent || !isset($user_work['UW_DATETIME_END']));}
        include('row/managementHome.php');
        exit();
    }

    $fields = array(
        'f_delete' => array('type' => 'hidden-int', 'required' => TRUE),
    );
    $sql_request = 'DELETE FROM T_RECORDED_WORK WHERE RW_ID=:f_delete;';
    try {
        $delete_recorded_work = new Form($bdd, $fields, $sql_request);

        if ($delete_recorded_work->validateForm()) {
            $delete_recorded_work->sendForm();
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
} else {

    $fields = array(
        'fe_rw_id' => array('type' => 'hidden-int', 'required' => TRUE),
        'fe_uw_toggle_performer' => array('type' => 'hidden-int', 'required' => TRUE)
    );
    $sql_request = 'CALL toggle_user_work(:fe_rw_id, :fe_uw_toggle_performer);';
    try {
        $toggle_user_work = new Form($bdd, $fields, $sql_request);

        if($toggle_user_work->validateForm()) {

            $toggle_user_work->sendForm();

            $req = $bdd->prepare('SELECT * FROM T_RECORDED_WORK
                                    LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
                                    LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
                                    LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
                                    LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
                                    WHERE RW_ID=:id;');
            $req->execute(array('id' => (int)$_POST['fe_rw_id']));
            $recorded_work = $req->fetch();
            $data_user_works = get_user_work_for_rw_and_user($bdd, $recorded_work['RW_ID'], $_SESSION['user_id']);
            $isCurrent = false;
            foreach($data_user_works as $user_work){$isCurrent = ($isCurrent || !isset($user_work['UW_DATETIME_END']));}
            include('row/managementHome.php');
            exit();
        }
    } catch (Exception $error) {
        display_modal($error->getMessage());
        exit();
    }
}