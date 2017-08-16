<?php
/**
 * sticker excel for PROJECT part
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

include_once($_SERVER['DOCUMENT_ROOT'] . '/excel/PHPExcel.php');

if(!isset($_GET['sticker_data'])) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}
$id_wo = (int)$_GET['sticker_data'];
/*Get $project_info var from database*/

/*  Get other data from database */
/*Get Project Part List */
$data_project_parts = get_pp_list_by_po_for_wo($bdd, $id_wo);

if(!$data_project_parts) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

$excel_name = '/sticker.xlsx';
$excel_path = '/files' . $excel_name;

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/sticker_datas.xlsx';
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
$L=2;
foreach ($data_project_parts as &$project_part) {
    if($project_part['LPS_QUANTITY_NUMBER'] > 0) {

        $sheet->setCellValue('A'.$L, $project_part['GST_NUMBER']);

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

echo '<a href="' . $excel_path . '"><button class="button">Sticker Data</button></a>';