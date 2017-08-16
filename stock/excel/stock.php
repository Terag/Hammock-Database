<?php
/**
 * Stock excel for STOCK part
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category Part
 * @package Stock\Excel
 * @namespace Stock
 * @filesource
 */

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/data_getters.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/reference_helper.php');
include($_SERVER['DOCUMENT_ROOT'] . '/excel/PHPExcel.php');

/*Get $data_stock_parts var from database*/
$data_stock_parts = get_stock_list($bdd);

if(!$data_stock_parts) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}

$date_array = getdate();
$date = $date_array['mday'].'-'.$date_array['mon'].'-'.$date_array['year'].'  '.$date_array['hours'].':'.$date_array['minutes'];
$file_name = 'stock';
$excel_name = 'stock.xlsx';
$excel_path = '/files/' . $excel_name;

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/stock.xlsx';
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

$sheet->setCellValue('D2', $date);

/* ---------- Prepare size of document ---------- */

$Nmax = count($data_stock_parts);
$sheet->setCellValue('D3', $Nmax);

if ($Nmax > 25) {
    $Nmax -= 25;
    $sheet->insertNewRowBefore(33, $Nmax);
    // A to N column style
    for ($i = 65; $i < 80; $i++) {
        $sheet->duplicateStyle($sheet->getStyle(chr($i) . '31'), chr($i).'32:'.chr($i) . (32 + $Nmax));
    }
}

/* ---------- Fill Content ---------- */
$L=9;
foreach ($data_stock_parts as &$stock_part) {

    $part_numbers = explode(';;', $stock_part['GP_NUMBER']);
    $index = ($stock_part['S_INDEX_PN'] > 0 AND $stock_part['S_INDEX_PN'] < count($part_numbers))? $stock_part['S_INDEX_PN'] : 0;
    $part_numbers[$index] = '<b>'.$part_numbers[$index].'</b>';
    $html_text = $part_numbers[0];
    for($i=1;$i<count($part_numbers);$i++) {
        $html_text .= '<br/>'.$part_numbers[$i];
    }
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('A'.$L, $objRichText);

    $sheet->setCellValue('B'.$L, $stock_part['GP_NAME']);

    $part_locations = explode(';;', $stock_part['GP_LOCATION']);
    $index = ($stock_part['S_INDEX_PN'] > 0 AND $stock_part['S_INDEX_PN'] < count($part_locations))? $stock_part['S_INDEX_PN'] : 0;
    $part_locations[$index] = '<b>'.$part_locations[$index].'</b>';
    $html_text = $part_locations[0];
    for($i=1;$i<count($part_locations);$i++) {
        $html_text .= '<br/>'.$part_locations[$i];
    }
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('C'.$L, $objRichText);

    $sheet->setCellValue('D'.$L, $stock_part['S_SERIAL']);

    $sheet->setCellValue('E'.$L, $stock_part['S_ARC_NAME']);

    $sheet->setCellValue('F'.$L, $stock_part['S_PO_NAME']);

    $sheet->setCellValue('G'.$L, $stock_part['S_RECEIVED_DATE']);

    $sheet->setCellValue('H'.$L, $stock_part['S_EXPIRATION_DATE']);

    $sheet->setCellValue('I'.$L, $stock_part['S_QUANTITY_IN']);

    $sheet->setCellValue('J'.$L, $stock_part['S_QUANTITY_NUMBER']);

    $sheet->setCellValue('K'.$L, $stock_part['S_PRICE']);

    $sheet->setCellValue('L'.$L, $stock_part['S_ACCURENCY']);

    $sheet->setCellValue('M'.$L, $stock_part['S_VENDOR']);

    $L++;
}

foreach ($sheet->getRowDimensions() as $row) {
    $row->setRowHeight(-1);
}

/* ---------- Save File ---------- */
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save($FILEPATH_SEND);

echo '<a href="' . $excel_path . '"><button class="button">Excel</button></a>';