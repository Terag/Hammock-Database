<?php
/* Script */

/* New_Database */
$db_host_name  = "db677852214.db.1and1.com";
$db_database   = "db677852214";
$db_user_name  = "dbo677852214";
$db_password   = "Borneo225!";

try {
    $bdd_new = new PDO('mysql:host='.$db_host_name.';dbname='.$db_database,$db_user_name,$db_password);
    $bdd_new->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Erreur New!: " . $e->getMessage() . "<br/>";
    die();
}

/* Old_Database */
$db_host_name  = "db644187066.db.1and1.com";
$db_database   = "db644187066";
$db_user_name  = "dbo644187066";
$db_password   = "christophe00#";

try {
    $bdd_old = new PDO('mysql:host='.$db_host_name.';dbname='.$db_database,$db_user_name,$db_password);
    $bdd_old->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Erreur Old !: " . $e->getMessage() . "<br/>";
    die();
}

/* -------------------- Script1 -------------------- */

if(isset($_POST['f_script1']) AND $_POST['f_script1'] == 1) {

    $req_old = $bdd_old->query("SELECT * FROM stock_list WHERE LOCATION='3-A-2';");
    $data_old_stock = $req_old->fetchAll();

    $i = 0;
    $j = 0;
    foreach ($data_old_stock as $old_stock) {

        $i++;
        //print_r($old_stock);
        $arc_name = NULL;
        $arc_pdf = NULL;
        echo '<br/><br/>-------------- New part !! --------- <br/>';
        echo $old_stock['DESCRIPTION'].' : '.$old_stock['PART_NUMBER'].'<br/>'.$old_stock['Balance'].' et '.$old_stock['LOCATION'].'<br/>';

        if( $old_stock['Balance'] > 0  AND ($old_stock['LOCATION'] == '3-A-2')) {
            echo 'Line : '.$i.'<br/>';
            echo 'UPDATE !<br/>';
            echo 'UPDATE !<br/>';
            echo $old_stock['ARC_TYPE'].'<br/>';
            echo 'Stock Quantity : '.$old_stock['Balance'].' AND location : '.$old_stock['LOCATION'].'<br/>';
            if(!isset($old_stock['PO']) OR $old_stock['PO'] == '') {
                echo '-------------------------------- Set PO<br/>';
                $old_stock['PO']='PO NOT AVAILABLE';
            }
            if(!isset($old_stock['ARC_TYPE']) OR $old_stock['ARC_TYPE'] == '') {
                echo '-------------------------------- Set ARC<br/>';
                $old_stock['ARC_TYPE']='ARC NOT AVAILABLE';
            }
            echo 'PO : '.$old_stock['PO'].' AND ARC : '.$old_stock['ARC_TYPE'].'<br/>';
            echo $j++.'<br/>';

            if (preg_match('/(.*)([0-9]{2}-[0-9]{2}-[0-9]{2})(.*)/', $old_stock['ARC_TYPE']) OR preg_match('/(.*)([0-9]{4}-[0-9]{2}-[0-9]{2})(.*)/', $old_stock['ARC_TYPE'])) {
                $arc_name = $old_stock['ARC_TYPE'] . '<br/>';

                echo 'Old arc_name valid found : ' . $arc_name . '<br/>';
                if ($old_stock['pdf_arc'] != NULL AND file_exists($_SERVER['DOCUMENT_ROOT'] . '/old/' . $old_stock['pdf_arc'])) {

                    $arc_name = substr($old_stock['pdf_arc'], strrpos($old_stock['pdf_arc'], '/') + 1, strrpos($old_stock['pdf_arc'], '.') - strrpos($old_stock['pdf_arc'], '/') - 1);
                    $arc_format = substr($old_stock['pdf_arc'], strrpos($old_stock['pdf_arc'], '.') + 1, strlen($old_stock['pdf_arc']) - strrpos($old_stock['pdf_arc'], '.') - 1);
                    $arc_folder = '/files/arc';
                    $arc_size = filesize($_SERVER['DOCUMENT_ROOT'] . '/old/' . $old_stock['pdf_arc']);
                    $arc_md5 = md5_file($_SERVER['DOCUMENT_ROOT'] . '/old/' . $old_stock['pdf_arc']);

                    echo '--- ARC FILE FOUND : <br/>';
                    echo $arc_name . ' - ' . $arc_format . ' - ' . $arc_folder . ' - ' . $arc_size . ' - ' . $arc_md5 . '<br/>';


                    $req_new = $bdd_new->prepare("INSERT INTO T_FILE (`F_ID`, `F_NAME`, `F_DIRECTORY`, `F_SIZE`, `F_FORMAT`, `F_MD5`) VALUES
                                                                     (NULL, :fname, :fdirectory, :fsize, :fformat, :fmd5);");
                    $req_new->execute(array('fname' => $arc_name, 'fdirectory' => $arc_folder, 'fsize' => $arc_size, 'fformat' => $arc_format, 'fmd5' => $arc_md5));
                    echo 'FILE CREATED !<br/>';

                    $arc_pdf = $bdd_new->lastInsertId();

                }
            }

            $sql_request = 'CALL import_old_to_new_stock(:new_number, :new_alt_number, :new_name, NULL, :new_location, NULL,
                                                         :qty_in, :qty_number, NULL, :u_price, :currency, :vendor, :received, :expiration,
                                                         :arc_name, :arc_id, :po_name, NULL);';
            $req_new = $bdd_new->prepare($sql_request);
            $req_new->execute(array(
                'new_number' => $old_stock['PART_NUMBER'], 'new_alt_number' => $old_stock['altPN'], 'new_name' => $old_stock['DESCRIPTION'], 'new_location' => $old_stock['LOCATION'],
                'qty_in' => $old_stock['QtyIn'], 'qty_number' => $old_stock['Balance'], 'u_price' => $old_stock['PRICE'], 'currency' => $old_stock['CURRENCY'], 'vendor' => $old_stock['VENDOR'], 'received' => $old_stock['RcvDate'], 'expiration' => $old_stock['ExpiryDate'],
                'arc_name' => $old_stock['ARC_TYPE'], 'arc_id' => $arc_pdf, 'po_name' => $old_stock['PO']
            ));
            echo 'DONE ! No Problemo<br/>';

            echo '<br/><br/>-----------------------------------------------------<br/>';
        }
    }

    echo '<br/> Line : '.$i.' and Insert : '.$j;
}
?>

<!-- Script 1 Form -->
<h1>Stock Import (3-A-2) Go</h1>
<p>Import stock from old to new web application <span style="color: red">/!\ Can destroy a part of stock Database /!\</span></p>
<form action="./script.php" method="post">
    <input type="hidden" name="f_script1" value="1"/>
    <input type="submit" value="Script1"/>
</form>