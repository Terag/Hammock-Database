<?php
/**
 * ScopeOfWork excel for PROJECT part
 *
 * excel build
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Project\Excel
 * @namespace Project
 * @filesource
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/reference_helper.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/excel/PHPExcel.php');

if(!isset($_GET['excel'])) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}
$id_wo = (int) $_GET['excel'];
/*Get $project_info var from database*/

/*Get Data about Work Order*/
$data_wo = get_wo_info($bdd, $id_wo);

if(!$data_wo) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

/*  Get other data from database */
/*  Get Project information */
$data_project = get_project_info_from_helico($bdd, $data_wo['WO_ID_H']);
/*Get Sub Task List */
$data_sub_tasks = get_st_list_for_wo($bdd, $id_wo);

$data_engine1 = null;
if($data_project['H_ID_E1'] != null) {
    $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
}
$data_engine2 = null;
if($data_project['H_ID_E2'] != null) {
    $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
}

$excel_name = str_replace('/','.',$id_wo.'-'.str_replace('%DOC%','SOW',$data_wo['WO_NAME']).'.xlsx');
$excel_path = '/files/sow/'.$excel_name;

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'].'/files/templates/scope_of_work.xlsx';
$FILEPATH = $excel_path;
$FILEPATH_SEND = $_SERVER['DOCUMENT_ROOT'].$FILEPATH;
if(!file_exists($FILEPATH_TEMPLATE)) { exit(); }

$objet = PHPExcel_IOFactory::createReader('Excel2007');
$excel = $objet->load($FILEPATH_TEMPLATE);
$sheet = $excel->getSheet(0);

// Use for specific Cell Content
$html_helper = new PHPExcel_Helper_HTML();
$objRichText = new PHPExcel_RichText();

/* ---------- Fill Header ---------- */

$html_text = 'Customer: <b>'.$data_project['C_NAME'].'</b>';
$objRichText = $html_helper->toRichTextObject($html_text);
$sheet->setCellValue('B2',$objRichText); // Customer Name
$sheet->setCellValue('A5',$data_project['GA_NAME']); // Helicopter Type
$sheet->setCellValue('B5',$data_project['H_SERIAL']); // Helicopter Serial
$sheet->setCellValue('E5',$data_project['H_TIME']); // Helicopter Time
$sheet->setCellValue('G5',$data_project['H_LANDING']); // Landing Time

$sheet->setCellValue('A8',$data_engine1['GA_NAME']); //Eng#1 Type
$sheet->setCellValue('B8',$data_engine1['E_SERIAL']); // Eng#1 Serial
$sheet->setCellValue('C8',$data_engine1['E_TIME']); // Eng#1 Time
$sheet->setCellValue('E8',$data_engine1['E_NG_CYCLE']); // Eng#1 Ng Cycle
$sheet->setCellValue('G8',$data_engine1['E_NF_CYCLE']); // Eng#1 Nf Cycle

$sheet->setCellValue('A9',$data_engine2['GA_NAME']); //Eng#2 Type
$sheet->setCellValue('B9',$data_engine2['E_SERIAL']); // Eng#2 Serial
$sheet->setCellValue('C9',$data_engine2['E_TIME']); // Eng#2 Time
$sheet->setCellValue('E9',$data_engine2['E_NG_CYCLE']); // Eng#2 Ng Cycle
$sheet->setCellValue('G9',$data_engine2['E_NF_CYCLE']); // Eng#2 Nf Cycle

/* ---------- Prepare size of document ---------- */

$Nmax = count($data_sub_tasks);

while($Nmax > 29) {
    $sheet->insertNewRowBefore(42,37);
    // A to H column style
    for($i=65;$i<73;$i++) {
        $sheet->duplicateStyle($sheet->getStyle(chr($i).'41'),chr($i).'42:'.chr($i).'80');
    }
    $Nmax -= 53;
}

/* ---------- Fill Content ---------- */

$L = 13;
foreach ($data_sub_tasks as &$sub_task) {
    if($sub_task['ST_IN_SOW']=='yes') {
            // ST Number
            $sheet->setCellValue('A' . $L, $sub_task['GST_NUMBER']);

            // Description
            $html_text = $sub_task['GST_DESCRIPTION'] . '<b>, ' . $sub_task['M_REFERENCE'] . ', ' . $sub_task['ST_REFERENCE'] . '</b>';
            $objRichText = $html_helper->toRichTextObject($html_text);

            $sheet->setCellValue('B' . $L, $objRichText);

            //Release date
            $sheet->setCellValue('H' . $L, $sub_task['ST_RELEASE_DATE']);

            $L++;
    }
}

/* ---------- Save File ---------- */
$writer = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
$writer->save($FILEPATH_SEND);

if(isset($check_close_counter)) {
    $check_close_counter++;
    $SOW_path = $excel_path;
}
echo '<a href="' . $excel_path . '"><button class="button">SOW</button></a>';