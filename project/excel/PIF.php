<?php
/**
 * PIF excel for PROJECT part
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
$id_wo = (int)$_GET['excel'];
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
/*Get Project Part List */
$data_project_parts = get_pp_list_by_po_for_wo($bdd, $id_wo);

$data_engine1 = null;
if ($data_project['H_ID_E1'] != null) {
    $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
}
$data_engine2 = null;
if ($data_project['H_ID_E2'] != null) {
    $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
}

$pif_name = str_replace('%DOC%', 'PIF', $data_wo['WO_NAME']);
$excel_name = str_replace('/', '.', $id_wo . '-' . $pif_name . '.xlsx');
$excel_path = '/files/pif/' . $excel_name;

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/PIF.xlsx';
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

/* ---------- Fill Header ---------- */

/* WO Info */
$sheet->setCellValue('H1', $pif_name);
$sheet->setCellValue('H2', $data_wo['WO_OPENED_DATE']);
$sheet->setCellValue('H3', $data_wo['WO_CLOSED_DATE']);

/* Aircraft Info */
$sheet->setCellValue('D2', $data_project['H_TIME']);

$sheet->setCellValue('D3', $data_project['H_LANDING']);

/* Engine1 */
if (isset($data_engine1)) {
    $sheet->setCellValue('F2', $data_engine1['E_TIME']);

    $sheet->setCellValue('F3', $data_engine1['E_NG_CYCLE'] . ' / ' . $data_engine1['E_NF_CYCLE']);
}

/* Engine2 */
if (isset($data_engine2)) {
    $sheet->setCellValue('F4', $data_engine2['E_TIME']);

    $sheet->setCellValue('F5', $data_engine2['E_NG_CYCLE']);
}

/* Header_Text */
$html_text = '<font size="14"><b>AIRCRAFT TYPE:' . $data_project['GA_NAME'] . ', <font color="#1e90ff">S/N: ' . $data_project['H_SERIAL'] . '</font>';
if (isset($data_engine1)) {
    $html_text .= '<font size="14"> - LH Engine: ' . $data_engine1['GA_NAME'] . ' <font color="#1e90ff">S/N: ' . $data_engine1['E_SERIAL'] . '</font>';
}
if (isset($data_engine2)) {
    $html_text .= '<font size="14"> - RH Engine: ' . $data_engine2['GA_NAME'] . ' <font color="#1e90ff">S/N: ' . $data_engine2['E_SERIAL'] . '</font>';
}
$html_text .= '</b>';
$objRichText = $html_helper->toRichTextObject($html_text);
$sheet->setCellValue('C7', $objRichText);

/* ---------- Prepare size of document ---------- */

$Nmax = count($data_project_parts);

if ($Nmax > 25) {
    $Nmax -= 25;
    $sheet->insertNewRowBefore(33, $Nmax);
    // A to G column style
    for ($i = 65; $i < 72; $i++) {
        $sheet->duplicateStyle($sheet->getStyle(chr($i) . '31'), chr($i).'32:'.chr($i) . (32 + $Nmax));
    }
}

/* ---------- Fill Content ---------- */
$L=9;
foreach ($data_project_parts as &$project_part) {
    if($project_part['LPS_QUANTITY_NUMBER'] > 0) {

        $html_text = $project_part['GST_NUMBER'];
        if($project_part['PP_DEFECT'] == 'yes') {
            $html_text .= ' (Discrepency)';
            for($i = 65; $i < 72; $i++) {
                $sheet->getStyle(chr($i).$L)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('DDEBF7');
            }
        }
        $sheet->setCellValue('A'.$L, $html_text);

        $sheet->setCellValue('B'.$L, $project_part['GP_NAME']);

        $part_numbers = explode(';;', $project_part['GP_NUMBER']);
        $index = ($project_part['S_INDEX_PN'] > 0 AND $project_part['S_INDEX_PN'] < count($part_numbers))? $project_part['S_INDEX_PN'] : 0;
        $sheet->setCellValue('C'.$L, $part_numbers[$index]);

        $sheet->setCellValue('D'.$L, $project_part['S_SERIAL']);

        $sheet->setCellValue('E'.$L, $project_part['PP_IPC']);

        $sheet->setCellValue('F'.$L, $project_part['LPS_QUANTITY_NUMBER']);

        $sheet->setCellValue('G'.$L, $project_part['S_ARC_NAME']);

        $L++;
    }
}

foreach ($sheet->getRowDimensions() as $row) {
    $row->setRowHeight(-1);
}

/* ---------- Save File ---------- */
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save($FILEPATH_SEND);

if(isset($check_close_counter)) {
    $check_close_counter++;
    $PIF_path = $excel_path;
}
echo '<a href="' . $excel_path . '"><button class="button">PIF</button></a>';