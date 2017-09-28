<?php
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
$data_project_parts = get_pp_list_for_wo($bdd, $id_wo);

$data_engine1 = null;
if ($data_project['H_ID_E1'] != null) {
    $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
}
$data_engine2 = null;
if ($data_project['H_ID_E2'] != null) {
    $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
}

$pif_name = str_replace('%DOC%', 'ERV', $data_wo['WO_NAME']);
$excel_name = str_replace('/', '.', $id_wo . '-' . $pif_name . '.xlsx');
$excel_path = '/files/ERV/' . $excel_name;

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/ERV.xlsx';
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
$sheet->setCellValue('C2', $data_project['H_TIME']);

$sheet->setCellValue('C3', $data_project['H_LANDING']);

/* Engine1 */
if (isset($data_engine1)) {
    $sheet->setCellValue('E2', $data_engine1['E_TIME']);

    $sheet->setCellValue('E3', $data_engine1['E_NG_CYCLE'] . ' / ' . $data_engine1['E_NF_CYCLE']);
}

/* Engine2 */
if (isset($data_engine2)) {
    $sheet->setCellValue('E4', $data_engine2['E_TIME']);

    $sheet->setCellValue('E5', $data_engine2['E_NG_CYCLE']);
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
    // A to J column style
    for ($i = 65; $i < 77; $i++) {
        $sheet->duplicateStyle($sheet->getStyle(chr($i) . '31'), chr($i).'32:'.chr($i) . (32 + $Nmax));
    }
}

/* ---------- Fill Content ---------- */
$L=9;
foreach ($data_project_parts as &$project_part) {

    if($project_part['PP_QUANTITY_REQUESTED'] != $project_part['LPS_QUANTITY_USED']) {
        for($i = 65; $i < 76; $i++) {
            $sheet->getStyle(chr($i).$L)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('FFD966');
        }
    }

    $html_text = $project_part['GST_NUMBER'];
    if($project_part['PP_DEFECT'] == 'yes') {
        $html_text .= ' (Discrepency)';
        $sheet->getStyle('A'.$L)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FFC0CB');
    }
    $sheet->setCellValue('A'.$L, $html_text);

    $sheet->setCellValue('B'.$L, $project_part['GP_NAME']);

    $html_text = str_replace(';;','<br/>',$project_part['GP_NUMBER']);
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('C'.$L, $objRichText);

    $sheet->setCellValue('D'.$L, $project_part['PP_IPC']);

    $sheet->setCellValue('E'.$L, $project_part['PP_QUANTITY_REQUESTED']);

    $html_text = '';
    $data_stock_available = get_stock_available_list_by_po_from_gp_id($bdd, $project_part['GP_ID']);
    $delivered = 0;
    $html_text_price = '';
    $html_text_currency = '';
    $part_numbers= explode(';;', $project_part['GP_NUMBER']);
    foreach ($data_stock_available as $available_part) {
        $index = ($available_part['S_INDEX_PN'] > 0 AND $available_part['S_INDEX_PN'] < count($part_numbers))? $available_part['S_INDEX_PN'] : 0;

        $link_stock_pp = [];
        $link_stock_pp = get_lps_for_pp_s_id($bdd, $project_part['PP_ID'], $available_part['S_ID']);



        $delivered += $link_stock_pp['LPS_QUANTITY_NUMBER'];

        if(isset($link_stock_pp['LPS_QUANTITY_NUMBER']))
            $html_text .= '<b>';

        $html_text .= $link_stock_pp['LPS_QUANTITY_NUMBER'] . ' - ' . $available_part['S_PO_NAME'] . ' (' . $part_numbers[$index] . ')<br/>';

        if(isset($link_stock_pp['LPS_QUANTITY_NUMBER']))
            $html_text .= '</b>';

        $html_text_price .= '- '.$available_part['S_PRICE'].'<br/>';

        $html_text_currency .= '- '.$available_part['S_ACCURENCY'].'<br/>';
    }

    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('H'.$L, $objRichText);

    $objRichText = $html_helper->toRichTextObject($html_text_price);
    $sheet->setCellValue('I'.$L, $objRichText);

    $objRichText = $html_helper->toRichTextObject($html_text_currency);
    $sheet->setCellValue('J'.$L, $objRichText);

    $sheet->setCellValue('F'.$L, $delivered);

    $req = $bdd->prepare('CALL number_available_for_gp_id(:id);');
    $req->execute(array('id' => $project_part['GP_ID']));
    $data_qty_available = $req->fetch();
    $req->closeCursor();
    $sheet->setCellValue('G'.$L, $data_qty_available['S_QUANTITY_AVAILABLE']);

    $L++;
}

foreach ($sheet->getRowDimensions() as $row) {
    $row->setRowHeight(-1);
}

/* ---------- Save File ---------- */
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save($FILEPATH_SEND);

if(isset($check_close_counter)) {
    $check_close_counter++;
    $ERV_path = $excel_path;
}
echo '<a href="' . $excel_path . '"><button class="button">ERV</button></a>';