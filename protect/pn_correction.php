<?php
echo 'go';
/* For Test on Local */
date_default_timezone_set('Asia/Kuala_Lumpur');

include($_SERVER['DOCUMENT_ROOT'] . '/SQL_management/connection.php');

$data_generic_parts = $bdd->query('SELECT * FROM T_GENERIC_PART');

function get_gp_pn_under_id(PDO &$database, $number, $id) {

    $req = $database->prepare("SELECT * FROM T_GENERIC_PART 
                            WHERE INSTR(CONCAT(';',CONCAT(REPLACE(GP_NUMBER,'-',''),';')), CONCAT(';',CONCAT(REPLACE(:p_number,'-',''),';'))) 
                              AND GP_ID<:id;");
    $req->execute(array('p_number' => $number, 'id' => $id));
    return $req->fetch();
}

function traitement(PDO &$bdd, &$generic_part) {
    echo '<br/><br/> ------------------- TRAITEMENT GP -------------------- <br/><br/>';

    echo 'PART NUMBER : '.$generic_part['GP_NUMBER'].' AND ALT_PN : '.$generic_part['GP_ALT_NUMBER'].'<br/>';

    if($data = get_gp_pn_under_id($bdd, $generic_part['GP_NUMBER'], $generic_part['GP_ID'])) {

        echo ' ----- /!\ NUMBER FOUND /!\<br/><br/>';
        echo 'Before : '.$data['GP_NUMBER'].' AND '.$generic_part['GP_NUMBER'].'<br/>';
        $index = array_search($generic_part['GP_NUMBER'], explode(';;',$data['GP_NUMBER']));

        if(isset($generic_part['GP_ALT_NUMBER']) AND $generic_part['GP_ALT_NUMBER'] != '') {

            $alt_pos = array_search($generic_part['GP_ALT_NUMBER'], explode(';;',$data['GP_NUMBER']));
            if($alt_pos != FALSE) {

                $req = $bdd->prepare("UPDATE T_GENERIC_PART SET GP_NUMBER=:number_pn, GP_LOCATION=:location WHERE GP_ID=:id");
                $req->execute(array(
                    'number_pn' => $data['GP_NUMBER'].';;'.$generic_part['GP_ALT_NUMBER'],
                    'location' => $data['GP_LOCATION'].';;'.$generic_part['GP_LOCATION'],
                    'id' => $data['GP_ID']
                ));
                $req->closeCursor();
                echo 'After : '.$data['GP_NUMBER'].';;'.$generic_part['GP_ALT_NUMBER'].'<br/>';
                echo 'New Positions : '.$data['GP_LOCATION'].';;'.$generic_part['GP_LOCATION'].'<br/>';
            }
        }

        $req = $bdd->prepare("UPDATE T_STOCK SET S_ID_GP=:new_gp, S_INDEX_PN=:new_index WHERE S_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data['GP_ID'],
            'new_index' => $index,
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("UPDATE T_PROJECT_PART SET PP_ID_GP=:new_gp WHERE PP_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data['GP_ID'],
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("UPDATE T_LINK_GP_GST SET LGG_ID_GP=:new_gp WHERE LGG_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data['GP_ID'],
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("DELETE FROM T_GENERIC_PART WHERE GP_ID=:id;");
        $req->execute(array('id' => $generic_part['GP_ID']));
        $req->closeCursor();

    } else if(isset($generic_part['GP_ALT_NUMBER']) AND $generic_part['GP_ALT_NUMBER'] != '' AND $data_alt = get_gp_pn_under_id($bdd, $generic_part['GP_ALT_NUMBER'], $generic_part['GP_ID'])) {

        echo ' ----- /!\ ALT_NUMBER FOUND /!\<br/><br/>';
        echo 'Before : '.$data_alt['GP_NUMBER'].' AND '.$generic_part['GP_ALT_NUMBER'].'<br/>';

        $index = count(explode(';;',$data_alt['GP_NUMBER']));

        $req = $bdd->prepare("UPDATE T_GENERIC_PART SET GP_NUMBER=:number_pn, GP_LOCATION=:location WHERE GP_ID=:id");
        $req->execute(array(
            'number_pn' => $data_alt['GP_NUMBER'].';;'.$generic_part['GP_NUMBER'],
            'location' => $data_alt['GP_LOCATION'].';;'.$generic_part['GP_LOCATION'],
            'id' => $data_alt['GP_ID']
        ));
        $req->closeCursor();

        echo 'After : '.$data_alt['GP_NUMBER'].';;'.$generic_part['GP_NUMBER'].'<br/>';
        echo 'New Positions : '.$data_alt['GP_LOCATION'].';;'.$generic_part['GP_LOCATION'].'<br/>';

        $req = $bdd->prepare("UPDATE T_STOCK SET S_ID_GP=:new_gp, S_INDEX_PN=:new_index WHERE S_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data_alt['GP_ID'],
            'new_index' => $index,
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("UPDATE T_PROJECT_PART SET PP_ID_GP=:new_gp WHERE PP_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data_alt['GP_ID'],
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("UPDATE T_LINK_GP_GST SET LGG_ID_GP=:new_gp WHERE LGG_ID_GP=:old_gp");
        $req->execute(array(
            'new_gp' => $data_alt['GP_ID'],
            'old_gp' => $generic_part['GP_ID']
        ));
        $req->closeCursor();

        $req = $bdd->prepare("DELETE FROM T_GENERIC_PART WHERE GP_ID=:id;");
        $req->execute(array('id' => $generic_part['GP_ID']));
        $req->closeCursor();

    } else {

        if(isset($generic_part['GP_ALT_NUMBER']) AND $generic_part['GP_ALT_NUMBER'] != '') {

            echo ' ----- /!\ NUMBER - ALT_NUMBER CONCAT /!\<br/><br/>';

            $req = $bdd->prepare('UPDATE T_GENERIC_PART SET GP_NUMBER=:number, GP_LOCATION=:location, GP_ALT_NUMBER=NULL WHERE GP_ID=:id;');
            $req->execute(array(
                'number' => $generic_part['GP_NUMBER'].';;'.$generic_part['GP_ALT_NUMBER'],
                'location' => $generic_part['GP_LOCATION'].';;'.$generic_part['GP_LOCATION'],
                'id' => $generic_part['GP_ID']
            ));
            $req->closeCursor();
        }
    }
}

foreach($data_generic_parts as $generic_part) {
    traitement($bdd, $generic_part);
}

echo ' ------------------------------------------------------------ END !! ---------------------------- ';