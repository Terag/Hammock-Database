<?php
/**
 * workProjectCalendar action for MANAGEMENT part
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

    try {
        $calendar = new Calendar($year, $month, $day);
    } catch (Exception $error) {
        $_SESSION['error'] = $error;
    }

    if(isset($calendar)) {
        $date_begin = $calendar->getBeginDate();
        $date_end = $calendar->getEndDate();

        try {
            $req = $bdd->prepare('CALL project_work_list_between_two_date(:id_p, :date_begin, :date_end);');
            $req->execute(array(
                'id_p' => $id,
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
                $work_days[$date]['total'] = $userwork['RW_TOTAL_MIN'];
                $work_days[$date]['tasks'] = array();
                $work_days[$date]['performers'] = array();
                $work_days[$date]['project_list'] = array();
                //$work_days[$date]['z'] = '<br/><br/>';
            }

            if(!in_array($userwork['RW_ID'], $work_days[$date]['tasks']))
                array_push($work_days[$date]['tasks'], $userwork['RW_ID']);

            $work_days[$date]['begin'] = (strtotime($userwork['UW_DATETIME_BEGIN']) < $work_days[$date]['begin'])?
                strtotime($userwork['UW_DATETIME_BEGIN']):
                $work_days[$date]['begin'];

            $work_days[$date]['end'] = ( ((strtotime($userwork['UW_DATETIME_END']) - 24*3600) < strtotime($date)) AND (strtotime($userwork['UW_DATETIME_END']) > $work_days[$date]['end']))?
                strtotime($userwork['UW_DATETIME_END']):
                $work_days[$date]['end'];

            if(!in_array($userwork['UW_ID_U'], $work_days[$date]['performers']))
                array_push($work_days[$date]['performers'], $userwork['UW_ID_U']);

            $recorded_work = $userwork['RW_ID'];
            if(!isset($work_days[$date]['project_list'][$recorded_work])) {
                $work_days[$date]['project_list'][$recorded_work] = array();
            }

            array_push($work_days[$date]['project_list'][$recorded_work], array(
                'st_name' => ($userwork['P_ID']==0)? 'DAILY' : $userwork['GST_NUMBER'],
                'Perf' => $userwork['U_NAME'],
                'Sum' => (($userwork['RW_ID_ST']==NULL)? $userwork['RW_DESCRIPTION']: $userwork['GST_DESCRIPTION']),
                'Begin' => date('H:i', strtotime($userwork['UW_DATETIME_BEGIN'])),
                'Time' => (int)($userwork['UW_TOTAL_MIN']/60).'h'.(int)($userwork['UW_TOTAL_MIN']%60)
            ));
        }

        foreach ($work_days as $key => $day) {

            $global_content = '<div class="calendar-day__content-global">
                                <b>Begin: </b>'.date('H:i',$day['begin']).'<br/>
                                <b>End: </b>'.(($day['end']==0)? 'N/A': date('H:i',$day['end'])).'<br/>
                                <b>Total: </b>'.((int)($day['total']/60)).'h'.((int)($day['total']%60)).'<br/>
                                <b>Tasks: </b>'.count($day['tasks']).'<br/>
                                <b>Performers: </b>'.count($day['performers']).'
                               </div>';
            $global_content .= '<div class="table myTable">
                                    <div class="tr header" style="border-bottom: white solid;">
                                        <span class="th" style="width: 20%;">STI</span>
                                        <span class="th" style="width: 70%;">Begin</span>
                                        <span class="th" style="width: 30%;">Time</span>
                                    </div>';

            foreach ($day['project_list'] as $key_subtask => $project) {
                $global_content .= '<div class="tr header">
                                        <span class="td" style="width: 20%;">'.$project[0]['st_name'].'</span>
                                        <span class="th" style="width: 40%;">'.$project[0]['Sum'].'</span>
                                        <span class="th" style="width: 30%;"></span>
                                    </div>';
                foreach ($project as $task) {
                    $global_content.= '<div class="tr">
                                            <span class="td">'.$task['Perf'].'</span>
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