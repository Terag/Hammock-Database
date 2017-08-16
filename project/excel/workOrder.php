<?php
/**
 * WorkOrder excel for PROJECT part
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
if ($data_project['H_ID_E1'] != null) {
    $data_engine1 = get_engine_info($bdd, $data_project['H_ID_E1']);
}
$data_engine2 = null;
if ($data_project['H_ID_E2'] != null) {
    $data_engine2 = get_engine_info($bdd, $data_project['H_ID_E2']);
}

$wo_name = str_replace('%DOC%', 'WO', $data_wo['WO_NAME']);
$excel_name = str_replace('/', '.', $id_wo . '-' . $wo_name . '.xlsx');
$excel_path = '/files/wo/' . $excel_name;

/* Prepare data for str_link_reference function */
$structure = array(
    'DOC' => array('VALUE' => $wo_name),
    'P' => array('VALUE' => $data_project['P_NAME']),
    'M' => array('VALUE' => NULL),
    'H' => array('N' => 'H_SERIAL', 'array_name' => 'project'),
    'E1' => array('N' => 'E_SERIAL', 'array_name' => 'engine1'),
    'E2' => array('N' => 'E_SERIAL', 'array_name' => 'engine2'),
    'R' => array('N' => 'number', 'array_name' => 'references'),
    'PI' => array('N' => 'number', 'array_name' => 'installed'),
    'PR' => array('N' => 'number', 'array_name' => 'removed')
);
$data = array(
    'project' => array('array' => $data_project),
    'engine1' => array('array' => $data_engine1),
    'engine2' => array('array' => $data_engine2),
    'references' => array('array' => NULL, 'separator' => ';;'),
    'installed' => array('array' => NULL, 'separator' => ';;'),
    'removed' => array('array' => NULL, 'separator' => ';;')
);

/* ---------- Create File ---------- */

$FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/work_order.xlsx';
$FILEPATH = $excel_path;
$FILEPATH_SEND = $_SERVER['DOCUMENT_ROOT'] . $FILEPATH;
if (!file_exists($FILEPATH_TEMPLATE)) {
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
$sheet->setCellValue('F1', $wo_name);
$sheet->setCellValue('F2', $data_wo['WO_OPENED_DATE']);
$sheet->setCellValue('F3', $data_wo['WO_CLOSED_DATE']);

/* Aircraft Info */
$html_text = 'Aircraft Total Time: <b>' . $data_project['H_TIME'] . '</b>';
$objRichText = $html_helper->toRichTextObject($html_text);
$sheet->setCellValue('C2', $objRichText);

$html_text = 'Aircraft Landings: <b>' . $data_project['H_LANDING'] . '</b>';
$objRichText = $html_helper->toRichTextObject($html_text);
$sheet->setCellValue('C3', $objRichText);

/* Engine1 */
if (isset($data_engine1)) {
    $html_text = 'Engine No. 1 Total Time: <b>' . $data_engine1['E_TIME'] . '</b>';
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('D2', $objRichText);

    $html_text = 'Engine No. 1 Ng Cycles / Nf Cycles: <b>' . $data_engine1['E_NG_CYCLE'] . ' / ' . $data_engine1['E_NF_CYCLE'] . '</b>';
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('D3', $objRichText);
}

/* Engine2 */
if (isset($data_engine2)) {
    $html_text = 'Engine No. 2 Total Time: <b>' . $data_engine2['E_TIME'] . '</b>';
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('D4', $objRichText);

    $html_text = 'Engine No. 2 Ng Cycles / Nf Cycles: <b>' . $data_engine2['E_NG_CYCLE'] . ' / ' . $data_engine2['E_NF_CYCLE'] . '</b>';
    $objRichText = $html_helper->toRichTextObject($html_text);
    $sheet->setCellValue('D5', $objRichText);
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

$Nmax = count($data_sub_tasks);

if ($Nmax > 11) {
    $Nmax -= 11;
    $sheet->insertNewRowBefore(33, $Nmax * 2);
    // A to G column style
    for ($i = 65; $i < 72; $i++) {
        for ($n = 1; $n < $Nmax * 2; $n += 2) {
            $sheet->duplicateStyle($sheet->getStyle(chr($i) . '31'), chr($i) . (32 + $n));
            $sheet->duplicateStyle($sheet->getStyle(chr($i) . '32'), chr($i) . (32 + $n + 1));
        }
    }
}

/* ---------- Fill Content ---------- */
$L = 9;
foreach ($data_sub_tasks as $sub_task) {
    if ($sub_task['ST_APPROVED_DATE']) {
        /* Prepare data for str_link_reference function */
        $data['references']['array'] = $sub_task['ST_REFERENCE'] . ';;' . $sub_task['ST_S_REFERENCES'];
        $data['removed']['array'] = $sub_task['ST_S_REMOVE'];
        $data['installed']['array'] = $sub_task['ST_S_INSTALL'];
        $structure['M']['VALUE'] = $sub_task['M_REFERENCE'];

        /* Write data */
        $html_text = '<b>' . $sub_task['GST_NUMBER'] . '</b>';
        $sheet->setCellValue('A' . $L, $html_helper->toRichTextObject($html_text));

        $html_text = '<b>' . $sub_task['M_REFERENCE'] . ', ' . $sub_task['ST_REFERENCE'];
        if (isset($sub_task['ST_S_REFERENCES'])) {
            $html_text .= '<br/>' . str_replace(';;', '<br/>', $sub_task['ST_S_REFERENCES']);
        }
        $html_text .= '</b>';
        $sheet->setCellValue('B' . $L, $html_helper->toRichTextObject($html_text));

        $html_text = str_link_references($sub_task['ST_WORK_REQUIRED'], $structure, $data);
        $sheet->setCellValue('C' . $L, $html_helper->toRichTextObject($html_text));

        if (isset($sub_task['ST_S_REMOVE']) AND $sub_task['ST_S_REMOVE'] != '') {
            $html_text = ' - ' . str_replace(';;', '<br/> - ', $sub_task['ST_S_REMOVE']);
            $sheet->setCellValue('C' . ($L + 1), $html_helper->toRichTextObject($html_text));
        }

        $html_text = str_link_references($sub_task['ST_RECTIFICATION_DETAILS'], $structure, $data);
        $sheet->setCellValue('D' . $L, $html_helper->toRichTextObject($html_text));

        if (isset($sub_task['ST_S_INSTALL']) AND $sub_task['ST_S_INSTALL'] != '') {
            $html_text = ' - ' . str_replace(';;', '<br/> - ', $sub_task['ST_S_INSTALL']);
            $sheet->setCellValue('D' . ($L + 1), $html_helper->toRichTextObject($html_text));
        }

        if (isset($sub_task['ST_ID_U_PERFORMER'])) {
            $data_name = get_user_info($bdd, $sub_task['ST_ID_U_PERFORMER']);
            $sheet->setCellValue('E' . $L, $html_helper->toRichTextObject($data_name['U_NAME']));
            $sheet->setCellValue('E' . ($L + 1), $html_helper->toRichTextObject($sub_task['ST_PERFORM_DATE']));
        }

        if (isset($sub_task['ST_ID_U_RELEASER'])) {
            $data_name = get_user_info($bdd, $sub_task['ST_ID_U_RELEASER']);
            $html_text = $data_name['U_NAME'] . '<br/>' . $data_name['U_CODE'];
            $sheet->setCellValue('F' . $L, $html_helper->toRichTextObject($html_text));
            $sheet->setCellValue('F' . ($L + 1), $html_helper->toRichTextObject($sub_task['ST_RELEASE_DATE']));
        }

        $L += 2;
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
    $WO_path = $excel_path;
}
echo '<a href="' . $excel_path . '"><button class="button">WO</button></a>';

