<?php
/**
 * work_hours excel for MANAGEMENT part
 *
 * excel building
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Management\Excel
 * @namespace Management
 * @filesource
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/excel/PHPExcel.php');

if(!isset($_GET['excel'])) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

/* Get other data from database */
/* Get Recorded Work */
$data_recorded_works = get_recorded_work_list($bdd);

if(!$data_recorded_works) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

$excel_name = 'work_hours_report.xlsx';
$excel_path = '/files/' . $excel_name;


/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/work_hours_report.xlsx';
$FILEPATH = $excel_path;
$FILEPATH_SEND = $_SERVER['DOCUMENT_ROOT'] . $FILEPATH;

if (!file_exists($FILEPATH_TEMPLATE)) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

$objet = PHPExcel_IOFactory::createReader('Excel2007');
$excel = $objet->load($FILEPATH_TEMPLATE);
$sheet = $excel->getSheet(0);

// Use for specific Cell Content
$html_helper = new PHPExcel_Helper_HTML();
$objRichText = new PHPExcel_RichText();

/* ---------- Fill Function ---------- */

function copyRowStyle($sheet, $origin, $target) {
    for ($i = 65; $i < 69; $i++) {
        $sheet->duplicateStyle($sheet->getStyle(chr($i) . $origin), chr($i) . $target);
    }
}

$project_style = 2;
$rw_style = 3;
$uw_style = 4;

/* ---------- Fill Content ---------- */

$L=2;
$p_name = NULL;
foreach ($data_recorded_works as $recorded_work) {

    if( ( ($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['P_NAME'] : 'DAILY') != $p_name ) {

        $p_name = ($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['P_NAME'] : 'DAILY';

        copyRowStyle($sheet, $project_style, $L);
        $sheet->setCellValue('A'.$L, 'Project:');
        $sheet->setCellValue('B'.$L, $p_name);
        $sheet->setCellValue('D'.$L, 'TT');

        $L++;
    }

    copyRowStyle($sheet, $rw_style, $L);
    $sheet->setCellValue('A'.$L, 'STI: '.(($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['GST_NUMBER'] : 'DAILY'));
    $sheet->setCellValue('B'.$L, 'Description:');
    $sheet->setCellValue('C'.$L, (($recorded_work['RW_ID_ST']!=NULL)? $recorded_work['GST_DESCRIPTION'] : $recorded_work['RW_DESCRIPTION']));
    $sheet->setCellValue('D'.$L, min_to_hour($recorded_work['RW_TOTAL_MIN']));

    $L++;

    $data_user_works = get_user_work_for_rw($bdd, $recorded_work['RW_ID']);
    foreach ($data_user_works as $user_work) {

        copyRowStyle($sheet, $uw_style, $L);
        $sheet->setCellValue('A'.$L, $user_work['U_NAME']);
        $sheet->setCellValue('B'.$L, $user_work['UW_DATETIME_BEGIN']);
        $sheet->setCellValue('C'.$L, $user_work['UW_DATETIME_END']);
        $sheet->setCellValue('D'.$L, min_to_hour($user_work['UW_TOTAL_MIN']));

        $L++;
    }
}

/* ---------- Save File ---------- */
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save($FILEPATH_SEND);

if(isset($check_close_counter)) {
    $check_close_counter++;
    $ERV_path = $excel_path;
}
echo '<a href="' . $excel_path . '"><button class="button">Work Hours</button></a>';