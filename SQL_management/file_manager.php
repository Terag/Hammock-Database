<?php
/**
 * This file provides a set of function to manage files sent by form
 *
 * @author Victor ROUQUETTE
 * @copyright Hammock Helicopter Database by Victor ROUQUETTE
 * @license GPL
 * @license https://www.gnu.org/licenses/gpl-3.0.fr.html
 * @category modules
 * @package Files
 * @namespace Files
 * @filesource
 */

/**
 * get info from database about a specific file.
 *
 * SQL: `SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id`
 *
 * @param PDO $database
 * @param int $id id of file
 * @return mixed array of file's information
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function get_file_info(PDO &$database, $id) {
    $id = (int)$id;
    $req = $database->prepare('SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id');
    $req->execute(array('id' => $id));
    return $req->fetch();
}

/**
 * get name of a file from database with corresponding ID
 *
 * SQL: `SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id`
 *
 * @param int $id id of file
 * @param PDO $database
 * @return bool|string false if doesn't exist
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function get_filename($id, PDO &$database) {
    $id = (int)$id;
    $req = $database->prepare('SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id');
    $req->execute(array('id' => $id));
    if ($data = $req->fetch()) {
        $req->closeCursor();
        return $data['F_NAME'].'.'.$data['F_FORMAT'];
    } else {
        $req->closeCursor();
        return FALSE;
    }
}

/**
 * get path of file from database with corresponding ID
 *
 * SQL: `SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id`
 *
 * @param int $id id of file
 * @param PDO $database
 * @return bool|string false if doesn't exist
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function get_file_path($id, PDO &$database) {
    $id = (int)$id;
    $req = $database->prepare('SELECT F_DIRECTORY, F_NAME, F_FORMAT FROM T_FILE WHERE F_ID=:id');
    $req->execute(array('id' => $id));
    if($data = $req->fetch()) {
        $req->closeCursor();
        return $data['F_DIRECTORY'].'/'.$data['F_NAME'].'.'.$data['F_FORMAT'];
    } else {
        $req->closeCursor();
        return FALSE;
    }
}

/**
 * Check if the file on this path is referenced in database or not
 *
 * SQL: `SELECT * FROM T_FILE WHERE F_DIRECTORY=:folder AND F_NAME=:name AND F_FORMAT=:format`
 *
 * @param string $path path of file
 * @param PDO $database
 * @return bool
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function file_is_used($path, PDO &$database) {
    $slash_pos = strripos($path, '/');
    $point_pos = strripos($path, '.');
    $folder_name = str_replace($_SERVER['DOCUMENT_ROOT'], '', substr($path, 0, $slash_pos));
    $file_name = substr($path, $slash_pos+1, $point_pos-$slash_pos-1);
    $file_format = substr($path, $point_pos+1);

    $check = $database->prepare('SELECT * FROM T_FILE WHERE F_DIRECTORY=:folder AND F_NAME=:name AND F_FORMAT=:format;');
    $check->execute(array('folder' => $folder_name, 'name' => $file_name, 'format' => $file_format));
    if($data = $check->fetch()) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * Reference file from a directory in database
 *
 * SQL: `INSERT INTO T_FILE ('F_ID', 'F_NAME', 'F_DIRECTORY', 'F_SIZE', 'F_FORMAT', 'F_MD5') VALUES
 *          (NULL, :f_name, :f_directory, :f_size, :f_format, :f_md5);`
 *
 * @param string $path path of file
 * @param PDO $database
 * @param bool $maxsize maximum size
 * @param bool $extensions authorized extensions
 * @return int|string  if of file in database or -2 if error
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function reference_file($path, PDO &$database, $maxsize=FALSE, $extensions=FALSE)
{

    // Config file var
    $path_ref = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
    //Destination
    $destination = substr($path_ref ,0,strrpos($path_ref, '/'));
    //Name
    $name = substr(strrchr($path_ref, '/'), 1);
    $name = substr($name, 0, strrpos($name, '.'));
    //Extension
    $extension = substr(strrchr($path_ref, '.'),1);

    //Secure info
    if(!file_exists($path)) {
        echo 'Error 1   ';
        return -2;
    }
    $maxsize = (int)$maxsize;
    $size = filesize($path);

    //Test1: max size
    if ($maxsize !== FALSE AND $size > $maxsize) {
        echo 'Error 2   ';
        return -2;
    }

    //Test3: extension
    if ($extensions !== FALSE AND !in_array($extension,$extensions)) {
        echo 'Error 3   ';
        return -2;
    }

    $md5 = md5_file($path);

    //Insert file info in database
    $query = "INSERT INTO T_FILE (`F_ID`, `F_NAME`, `F_DIRECTORY`, `F_SIZE`, `F_FORMAT`, `F_MD5`) VALUES
              (NULL, :f_name, :f_directory, :f_size, :f_format, :f_md5);";
    $req = $database->prepare($query);
    $req->execute(array(
        'f_name' => $name,
        'f_directory' => $destination,
        'f_size' => $size,
        'f_format' => $extension,
        'f_md5' => $md5
    ));
    $error = $req->errorInfo();
    $req->closeCursor();

    //Check Insert call
    if($error[0] != 00000 AND !file_is_used($path, $bdd)) {
        unlink($path);
        echo 'Error 4   ';
        return -2;
    } else {
        return $database->lastInsertId();
    }
}

/**
 * Upload a file from $_FILES
 *
 * SQL: `INSERT INTO T_FILE ('F_ID', 'F_NAME', 'F_DIRECTORY', 'F_SIZE', 'F_FORMAT', 'F_MD5') VALUES
 *          (NULL, :f_name, :f_directory, :f_size, :f_format, :f_md5);`
 *
 * @param string $index index of file in $_FILES
 * @param string $destination path to send file
 * @param string $name name of file
 * @param PDO $database
 * @param bool $maxsize
 * @param bool $extensions
 * @return int|string ID in database if successfully and -2 if not
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function upload_file($index, $destination, $name, PDO &$database, $maxsize=FALSE, $extensions=FALSE)
{
    //Secure info
    $maxsize = (int)$maxsize;
    $size = (int)$_FILES[$index]['size'];

    //Test1: file correctly uploaded
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
        return -2;
    }

    //Test2: max size
    if ($maxsize !== FALSE AND $size > $maxsize) {
        return -2;
    }

    //Test3: extension
    $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
    if ($extensions !== FALSE AND !in_array($ext,$extensions)) {
        echo 3;
        return -2;
    }

    //Create md5
    $md5 = md5_file($_FILES[$index]['tmp_name']);

    //Check if file is already exist
    if(file_exists($_SERVER['DOCUMENT_ROOT'].$destination.'/'.$name.'.'.$ext)) {
        unlink($_SERVER['DOCUMENT_ROOT'].$destination.'/'.$name.'.'.$ext);
    }

    //Upload file and insert in database
    if(move_uploaded_file($_FILES[$index]['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$destination.'/'.$name.'.'.$ext)) {

        //Insert file info in database
        $query = "INSERT INTO T_FILE (`F_ID`, `F_NAME`, `F_DIRECTORY`, `F_SIZE`, `F_FORMAT`, `F_MD5`) VALUES
                  (NULL, :f_name, :f_directory, :f_size, :f_format, :f_md5);";
        $req = $database->prepare($query);
        $req->execute(array('f_name' => $name, 'f_directory' => $destination, 'f_size' => $size, 'f_format' => $ext, 'f_md5' => $md5));
        $error = $req->errorInfo();
        $req->closeCursor();

        //Check Insert call
        if($error[0] != 00000) {
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$destination.'/'.$name.'.'.$ext);
            return -2;
        } else {
            return $database->lastInsertId();
        }
    } else {
        return -2;
    }
}

/**
 * Delete files from database and website.
 *
 * Delete in website if no more references to it in database.
 * SQL: `DELETE FROM T_FILE WHERE F_ID=:id;`
 *
 * @param int $id id of file
 * @param PDO $database
 * @return int|string ID in database if successfully and -2 if not
 *
 * @tags Files
 * @source SQL_management\file_manager.php
 */
function delete_file($id, PDO &$database) {
    //echo '<br/> id : '.$id;
    if($path = $_SERVER['DOCUMENT_ROOT'].get_file_path($id, $database) AND file_is_used($path, $database)) {
        //Delete file info in database
        $req = $database->prepare('DELETE FROM T_FILE WHERE F_ID=:id;');
        $req->execute(array('id' => $id));
        $error = $req->errorInfo();
        $req->closeCursor();

        //Check error and delete in directory
        if($error[0] == 00000) {
            if(file_exists($path) AND !file_is_used($path, $database)){
                if(unlink($path)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}