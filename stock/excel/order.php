<?php
/**
 * Order excel for STOCK part
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
include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/file_manager.php');
include($_SERVER['DOCUMENT_ROOT'] . '/excel/PHPExcel.php');

if(isset($_SESSION['LOCK_CREATOR']) AND $_SESSION['LOCK_CREATOR'] == true) {
    exit();
}
$_SESSION['LOCK_CREATOR'] = true;


if(!isset($_POST['f_g_doctype']) OR !isset($_POST['f_count'])) {
    echo '<p>Error : '.$_POST['f_g_doctype'].' AND '.$_POST['f_count'].'</p>';
    echo '<button class="button">!ERROR!</button>';
    $_SESSION['LOCK_CREATOR'] = false;
    exit();
}

if(isset($_POST['f_g_add_receive']) AND (int)$_POST['f_g_add_receive'] == 1) {
    $g_add_receive = true;
} else {
    $g_add_receive = false;
}

if(isset($_POST['f_v_vendor_info']) AND (int)$_POST['f_v_vendor_info'] == 1) {
    $v_edit_info = true;
} else {
    $v_edit_info = false;
}


/* ---------- Form Checking Data ---------- */

$is_PO = (int)$_POST['f_g_doctype'] === 0;
$count = (int)$_POST['f_count'];
/* General */
$g_name = htmlspecialchars($_POST['f_g_name']);
$g_rep = htmlspecialchars($_POST['f_g_rep']);
$g_date = htmlspecialchars($_POST['f_g_date']);
$g_phone = htmlspecialchars($_POST['f_g_phone']);
$g_location = htmlspecialchars($_POST['f_g_location']);
if($_POST['f_g_project'] == '0') {
    $g_project = htmlspecialchars($_POST['f_g_project_name']);
} else {
    $g_project = htmlspecialchars($_POST['f_g_project']);
}
/* Vendor */
$v_id_vendor = (int)$_POST['f_v_vendor'];
$v_name = htmlspecialchars($_POST['f_v_name']);
$v_address = htmlspecialchars($_POST['f_v_address']);
$v_city = htmlspecialchars($_POST['f_v_city']);
$v_country = htmlspecialchars($_POST['f_v_country']);
$v_phone = htmlspecialchars($_POST['f_v_phone']);
$v_contact = htmlspecialchars($_POST['f_v_contact']);
$v_mail = htmlspecialchars($_POST['f_v_mail']);
$format = htmlspecialchars($_POST['f_g_currency']);

$rows = array();
for($i = 0; $i < $count; $i++) {

    if(!isset($_POST[$i.'-f_gp_index'])) {
        $_POST[$i.'-f_gp_index'] = (int)explode('|', $_POST[$i.'-f_gp_number'])[1];
        $_POST[$i.'-f_gp_number'] = explode('|', $_POST[$i.'-f_gp_number'])[0];
    }

    $rows[$i] = array(
        'st_index' => (isset($_POST[$i.'-f_sti']))? htmlspecialchars($_POST[$i.'-f_sti']): NULL,
        'gp_id' => (int)$_POST[$i.'-f_gp_id'],
        'p_index' => (int)$_POST[$i.'-f_gp_index'],
        'gp_index' => (int)$_POST[$i.'-f_gp_index'],
        'p_ipc' => htmlspecialchars($_POST[$i.'-f_p_ipc']),
        'p_qty' => (int)$_POST[$i.'-f_p_qty'],
        'p_price' => (float)$_POST[$i.'-f_p_price'],
    );
    if($rows[$i]['gp_id'] > 0) {
        $req = $bdd->prepare('SELECT * FROM T_GENERIC_PART WHERE GP_ID=:id');
        $req->execute(array('id' => $rows[$i]['gp_id']));
        $data = $req->fetch();
        $rows[$i]['p_gp_description'] = $data['GP_NAME'];
        if(isset($_POST[$i.'-f_gp_number'])) {
            $rows[$i]['p_gp_number'] = htmlspecialchars($_POST[$i.'-f_gp_number']);
        } else {
            $index = (int)$_POST[$i.'-f_gp_index'];
            $part_numbers = explode(';;', $data['GP_NUMBER']);
            $index = ($index < 0 || $index > count($part_numbers)-1)? 0 : $index;
            $rows[$i]['p_gp_number'] = $part_numbers[$index];
        }
        $rows[$i]['p_gp_location'] = $data['GP_LOCATION'];
    } else {
        $new_gp_name = htmlspecialchars($_POST[$i.'-f_new_gp_name']);
        $new_gp_number = htmlspecialchars($_POST[$i.'-f_new_gp_number']);
        $new_gp_location = htmlspecialchars($_POST[$i.'-f_new_gp_location']);
        $rows[$i]['p_gp_description'] = $new_gp_name;
        $rows[$i]['p_gp_number'] = $new_gp_number;
        $rows[$i]['p_gp_location'] = $new_gp_location;
    }
}

/* ---------- Create File ---------- */

try {
    if($is_PO) {
        $excel_name = str_replace('/', '.', 'po_t.xlsx');
        $FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/PO.xlsx';
        $FILEPATH = '/files/po/po_t.xlsx';
        $excel_path = '/files/po/' . $excel_name;
    } else {
        $excel_name = str_replace('/', '.', 'rfq_t.xlsx');
        $FILEPATH_TEMPLATE = $_SERVER['DOCUMENT_ROOT'] . '/files/templates/RFQ.xlsx';
        $FILEPATH = '/files/rfq/rfq_t.xlsx';
        $excel_path = '/files/rfq/' . $excel_name;
    }
    $FILEPATH_SEND = $_SERVER['DOCUMENT_ROOT'] . $FILEPATH;
    if (!file_exists($FILEPATH_TEMPLATE)) {
        echo '<p>Error Template File</p>';
        echo '<button class="button">!ERROR!</button>';
        $_SESSION['LOCK_CREATOR'] = false;
        exit();
    }

    $objet = PHPExcel_IOFactory::createReader('Excel2007');
    $excel = $objet->load($FILEPATH_TEMPLATE);
    $sheet = $excel->getSheet(0);

    // Use for specific Cell Content
    $html_helper = new PHPExcel_Helper_HTML();
    $objRichText = new PHPExcel_RichText();

/* ---------- Prepare size of document ---------- */

    $sheet->setPrintGridlines(true);

    $Nmax = $count;

    while($Nmax > 42) {
        $sheet->insertNewRowBefore(53,43);
        // A to K column style
        for($i=65;$i<76;$i++) {
            $sheet->duplicateStyle($sheet->getStyle(chr($i).'52'),chr($i).'53:'.chr($i).'96');
        }
        $Nmax -= 43;
    }

/* ---------- File document ---------- */

    /* ----- HEADER ----- */
    $sheet->setCellValue('A1',$g_name);
    $sheet->setCellValue('K2', $format);
    // Global Information
    $sheet->setCellValue('I3', $g_date);
    $sheet->setCellValue('I4', $g_project);
    $sheet->setCellValue('I5', $g_rep);
    $sheet->setCellValue('I6', $g_location);
    $sheet->setCellValue('I7', $g_phone);
    // Vendor Information
    $sheet->setCellValue('C3', $v_name);
    $sheet->setCellValue('F3', $v_contact);
    $sheet->setCellValue('C4', $v_address);
    $sheet->setCellValue('C6', $v_city . ', ' . $v_country);
    $sheet->setCellValue('C7', $v_mail);
    $sheet->setCellValue('F7', $v_phone);

    $currency = $format;
    switch ($format) {
        case 'MYR':
            $format = '"MYR "#,##0.00';
            break;
        case 'EUR':
            $format = PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE;
            break;
        case 'USD':
            $format = PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE;
            break;
        default:
            $format = "";
            break;
    }

    /* ----- ROWS CONTENT ----- */
    $i = 11;
    foreach ($rows as $row) {
        $sheet->setCellValue('A'.$i, $row['p_qty']);
        $sheet->setCellValue('C'.$i, $row['st_index']);
        $sheet->setCellValue('D'.$i, $row['p_gp_description']);

        $sheet->setCellValue('E'.$i, $row['p_gp_number']);
        $sheet->setCellValue('G'.$i, $row['p_ipc']);
        $sheet->setCellValue('I'.$i, $row['p_price']);
        $sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode($format);
        $sheet->getStyle('K'.$i)->getNumberFormat()->setFormatCode($format);
        $i++;
    }

    //Footer values format
    $sheet->getStyle('K55')->getNumberFormat()->setFormatCode($format);
    $sheet->getStyle('K57')->getNumberFormat()->setFormatCode($format);
    $sheet->getStyle('K58')->getNumberFormat()->setFormatCode($format);

/* ---------- Save File ---------- */

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save($FILEPATH_SEND);

} catch (Exception $error) {
    echo '<p>Error On File filling : '.$error->getMessage().'</p>';
    echo '<button class="button">!ERROR!</button>';
    $_SESSION['LOCK_CREATOR'] = false;
    exit();
}

/* ---------- Send link ---------- */

echo '<a href="' . $excel_path . '"><button class="button" type="button">Excel</button></a>';

/* ---------- Edit Vendor info --------*/

if($v_edit_info) {

    $fields = array(
        /* UPDATE VENDOR */
        'f_v_vendor' => $v_id_vendor,
        'f_v_name' => $v_name,
        'f_v_address' => $v_address,
        'f_v_city' => $v_city,
        'f_v_country' => $v_country,
        'f_v_phone' => $v_phone,
        'f_v_mail' => $v_mail,
        'f_v_contact' => $v_contact
    );
    $sql_request = 'CALL update_vendor(:f_v_vendor, :f_v_name, :f_v_address, :f_v_city, :f_v_country, :f_v_phone, :f_v_mail, :f_v_contact);';
    $req = $bdd->prepare($sql_request);
    $req->execute($fields);
    $req->closeCursor();
}

/* ---------- Add Part in Receive ---------- */

if($is_PO AND $g_add_receive AND $_SESSION['access'] > 2) {

    $path_po = $_SERVER['DOCUMENT_ROOT'] . '/files/po/' . str_replace(' ', '_', $g_name) . '.pdf';

    //Set PDF Renderer
    $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
    // tcpdf folder path
    $rendererLibraryPath = $_SERVER['DOCUMENT_ROOT'].'/excel/mpdf';

    PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'PDF');
    $objWriter->save($path_po);

    $sql_request = 'CALL add_part_stock(:gp_id, NULL, :g_name, NULL, :po_file, :p_index,
                                        :p_qty, NULL, :p_price, :p_currency, :v_vendor, NULL, NULL,
                                        :p_gp_name, :p_gp_number, NULL, :p_gp_location, NULL);';
    echo '<div style="margin-top: 20px;"></div>';
    foreach ($rows as $row) {

        $file_id = reference_file($path_po, $bdd, 100000000, array('pdf'));
        if($file_id > 0){

            $req = $bdd->prepare($sql_request);
            $fields = array(
                'gp_id' => $row['gp_id'],
                'g_name' => $g_name,
                'po_file' => $file_id,
                'p_index' => $row['p_index'],
                'p_qty' => $row['p_qty'],
                'p_price' => $row['p_price'],
                'p_currency' => $currency,
                'v_vendor' => $v_name,
                'p_gp_name' => $row['p_gp_description'],
                'p_gp_number' => $row['p_gp_number'],
                'p_gp_location' => $row['p_gp_location']
            );

            try {
                $req->execute($fields);
                echo '<p><b>Part added : </b>'.$fields['p_gp_name'].' : '.$fields['p_gp_number'].'</p>';
            } catch (PDOException $error) {
                echo '<p>'.$format.'<br/><b style="color: darkred;">Error add (Insert part): </b>'.$fields['p_gp_name'].' : '.$fields['p_gp_number'].'</p>';
            }
            $req->closeCursor();
        } else {
            echo '<p><b style="color: darkred;">Error add (Ref file): </b>'.$fields['p_gp_name'].' : '.$fields['p_gp_number'].'</p>';
        }

    }
}

$_SESSION['LOCK_CREATOR'] = false;
