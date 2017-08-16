<?php
/**
 * workUserCalendar action for MANAGEMENT part
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
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/calendar.php');

if(isset($_GET['id'])) {
    $year = (isset($_GET['Y']))? (int)$_GET['Y']: (int)date('Y');
    $month = (isset($_GET['M']))? (int)$_GET['M']: (int)date('m');
    $day = (isset($_GET['D']))? (int)$_GET['D']: (int)date('d');
    $id = (int)$_GET['id'];

    if($id != $_SESSION['user_id'] AND $lvl_access < 2) {
        header('Location: ./');
        exit();
    }

    try {
        $calendar = new Calendar($year, $month, $day);
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    if(isset($calendar)) {
        $date_begin = $calendar->getBeginDate();
        $date_end = $calendar->getEndDate();

        try {
            $req = $bdd->prepare('CALL user_work_list_between_two_date(:id_u, :date_begin, :date_end);');
            $req->execute(array(
                'id_u' => $id,
                'date_begin' => $date_begin,
                'date_end' => date('Y-m-d', strtotime($date_end) + 3600*24)
            ));
            $date_userworks = $req->fetchAll();
            $req->closeCursor();
        } catch (Exception $error) {
            display_modal($error->getMessage());
            exit();
        }

        $work_days = array();

        foreach ($date_userworks as &$userwork) {
            $date = date('Y-m-d',strtotime($userwork['UW_DATETIME_BEGIN']));
            if(!isset($work_days[$date])) {
                $work_days[$date]['begin'] = strtotime($date) + 3600*24;
                $work_days[$date]['end'] = 0;
                $work_days[$date]['total'] = 0;
                $work_days[$date]['tasks'] = 0;
                $work_days[$date]['project_list'] = array();
                //$work_days[$date]['z'] = '<br/><br/>';
            }

            $work_days[$date]['tasks']++;

            $work_days[$date]['total'] += $userwork['UW_TOTAL_MIN'];

            $work_days[$date]['begin'] = (strtotime($userwork['UW_DATETIME_BEGIN']) < $work_days[$date]['begin'])?
                strtotime($userwork['UW_DATETIME_BEGIN']):
                $work_days[$date]['begin'];

            $work_days[$date]['end'] = ( ((strtotime($userwork['UW_DATETIME_END']) - 24*3600) < strtotime($date)) AND (strtotime($userwork['UW_DATETIME_END']) > $work_days[$date]['end']))?
                strtotime($userwork['UW_DATETIME_END']):
                $work_days[$date]['end'];

            $project_name = ($userwork['P_ID']==0)? 'DAILY' : $userwork['P_NAME'];
            if(!isset($work_days[$date]['project_list'][$project_name])) {
                $work_days[$date]['project_list'][$project_name] = array();
            }

            array_push($work_days[$date]['project_list'][$project_name], array(
                'Ref' => (($userwork['RW_ID_ST']==NULL)? 'DAILY': $userwork['GST_NUMBER']),
                'Sum' => (($userwork['RW_ID_ST']==NULL)? $userwork['RW_DESCRIPTION']: $userwork['GST_DESCRIPTION']),
                'Begin' => date('H:i', strtotime($userwork['UW_DATETIME_BEGIN'])),
                'Time' => (int)($userwork['UW_TOTAL_MIN']/60).'h'.(int)($userwork['UW_TOTAL_MIN']%60)
            ));
        }

        foreach ($work_days as $key => $day) {

            $global_content = '<div class="calendar-day__content-global">
                                <b>Begin :</b>'.date('H:i',$day['begin']).'<br/>
                                <b>End :</b>'.(($day['end']==0)? 'N/A': date('H:i',$day['end'])).'<br/>
                                <b>Total :</b>'.((int)($day['total']/60)).'h'.((int)($day['total']%60)).'<br/>
                                <b>Tasks :</b>'.$day['tasks'].'
                               </div>';
            $global_content .= '<div class="table myTable">
                                    <div class="tr header" style="border-bottom: white solid;">
                                        <span class="th" style="width: 20%;">Ref</span>
                                        <span class="th" style="width: 40%;">Sum</span>
                                        <span class="th" style="width: 30%;">Begin</span>
                                        <span class="th" style="width: 30%;">Time</span>
                                    </div>';

            foreach ($day['project_list'] as $key_project => $project) {
                $global_content .= '<div class="tr header">
                                        <span class="td" style="width: 20%;">'.$key_project.'</span>
                                        <span class="th" style="width: 40%;"></span>
                                        <span class="th" style="width: 30%;"></span>
                                        <span class="th" style="width: 30%;"></span>
                                    </div>';
                foreach ($project as $task) {
                    $global_content.= '<div class="tr">
                                            <span class="td">'.$task['Ref'].'</span>
                                            <span class="td">'.$task['Sum'].'</span>
                                            <span class="td">'.$task['Begin'].'</span>
                                            <span class="td">'.$task['Time'].'</span>
                                        </div>';
                }
            }
            $global_content.= '</div>';

            $calendar->SetContentOnDate($key, $global_content);
        }
    }


} else {
    header('Location: ./');
    exit();
}

