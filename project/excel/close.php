<?php
/**
 * Close excel for PROJECT part
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

include_once($_SERVER['DOCUMENT_ROOT'].'/SQL_management/connection.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/SQL_management/file_manager.php');

if(!isset($_GET['excel'])) {
    echo '<button class="button">!ERROR!</button>';
    exit();
}
$id_project = (int) $_GET['id'];
$id_wo = (int) $_GET['excel'];

$check_close_counter = 0;
?>

<div class="row">
    <h2>Work Order Documents</h2>
    <div class="col-lg-3">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/project/excel/scopeOfWork.php');?>
    </div>
    <div class="col-lg-3">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/project/excel/workOrder.php');?>
    </div>
    <div class="col-lg-3">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/project/excel/ERV.php');?>
    </div>
    <div class="col-lg-3">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/project/excel/PIF.php');?>
    </div>
</div>

<?php

/*Get Data about Work Order*/
$data_wo = get_wo_info($bdd, $id_wo);
$wo_po_name = str_replace('/','-',str_replace('%DOC%', 'PO', $data_wo['WO_NAME']));
$wo_arc_name = str_replace('/','-',str_replace('%DOC%', 'ARC', $data_wo['WO_NAME']));

$data_project_parts = get_pp_list_by_po_for_wo($bdd, $id_wo);

$ZIP_ERROR = [
    ZipArchive::ER_EXISTS => 'File already exists.',
    ZipArchive::ER_INCONS => 'Zip archive inconsistent.',
    ZipArchive::ER_INVAL => 'Invalid argument.',
    ZipArchive::ER_MEMORY => 'Malloc failure.',
    ZipArchive::ER_NOENT => 'No such file.',
    ZipArchive::ER_NOZIP => 'Not a zip archive.',
    ZipArchive::ER_OPEN => "Can't open file.",
    ZipArchive::ER_READ => 'Read error.',
    ZipArchive::ER_SEEK => 'Seek error.',
];

$zip = new ZipArchive();

/* ---------- PO Zip File ---------- */

$open = $zip->open($_SERVER['DOCUMENT_ROOT'].'/files/zip/po_files.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
if($open !== TRUE) { ?>
    <div class="row" style="margin-top: 20px;">
        Error Open zip files for PO :
        <?php echo isset($ZIP_ERROR[$open])? $ZIP_ERROR[$open] : 'Unknown error.'; ?>
    </div>
<?php exit();
}

foreach($data_project_parts as $project_part) {

    /* Add PO */
    if ($project_part['S_ID_PURCHASE_ORDER'] > 0) {

        $po_path = $_SERVER['DOCUMENT_ROOT'] . get_file_path($project_part['S_ID_PURCHASE_ORDER'], $bdd);
        $zip->addFile($po_path, str_replace('/', '-', $project_part['S_PO_NAME']).'.pdf');
    }
}


$zip->addFromString('index.txt','po files');

$zip->close();

/* ---------- ARC Zip File ---------- */

$open = $zip->open($_SERVER['DOCUMENT_ROOT'].'/files/zip/arc_files.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
if($open !== TRUE) { ?>
    <div class="row" style="margin-top: 20px;">
        Error Open zip files for ARC :
        <?php echo isset($ZIP_ERROR[$open])? $ZIP_ERROR[$open] : 'Unknown error.'; ?>
    </div>
<?php exit();
}

foreach($data_project_parts as $project_part) {

    /* Add ARC */
    if($project_part['S_ID_ARC'] > 0) {

        $arc_path = $_SERVER['DOCUMENT_ROOT'].get_file_path($project_part['S_ID_ARC'], $bdd);
        $zip->addFile($arc_path, str_replace('/','-',$project_part['S_ARC_NAME']).'.pdf');
    }
}


$zip->addFromString('index.txt','arc files');

$zip->close();

?>
<div class="row" style="margin-top: 40px;">
    <div class="col-lg-4 col-lg-offset-2">
        <a href="<?php echo '/files/zip/po_files.zip';?>"><button class="button">PO Files</button></a>
    </div>
    <div class="col-lg-4">
        <a href="<?php echo '/files/zip/arc_files.zip';?>"><button class="button">ARC Files</button></a>
    </div>
</div>
<?php

$check_close_counter += 2;

?>

<div class="row">

</div>

<?php if($check_close_counter == 6) { ?>
    <div class="row" style="margin-top:40px;">
        <div class="col-lg-4 col-lg-offset-4">
            <form id="close-wo-form" onsubmit="return confirm('Delete WO ?');" action="/project/?page=close&id=<?php echo $id_project;?>&delete=<?php echo $id_wo;?>" method="post">
                <input type="hidden" value="<?php echo generate_token('close-wo-'.$id_wo);?>" name="token" required/>
                <input type="hidden" value="<?php echo $SOW_path;?>" name="path_sow">
                <input type="hidden" value="<?php echo $WO_path;?>" name="path_wo">
                <input type="hidden" value="<?php echo $ERV_path;?>" name="path_erv">
                <input type="hidden" value="<?php echo $PIF_path;?>" name="path_pif">
                <button type="submit" class="button danger">Close WO</button>
            </form>
        </div>
    </div>
<?php }