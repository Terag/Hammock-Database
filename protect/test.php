<?php
session_start();
//Create and set var '$UserConnected' to true or false
include('../account/checkConnection.php');

/*HTML Content is send if user is connected*/
if($UserConnected) {

    unset($_SESSION['error']);

    //Include action file to manage forms
    if (isset($_GET['page'])) {
        $currentPage = $_GET['page'];
        /*select the content of the project page.
        Can be :
        -projectHome.php
        -specificProject.php
        -recapitulate.php
        -createNewProject.php
        -workOrder.php
        -scopeOfWork.php
        -ERV.php
        -PIF.php
        */
        $possibilities = array('specificProject', 'recapitulate', 'workOrder', 'ERV');

        if (in_array($currentPage, $possibilities)) {
            if (isset($_GET['excel'])) {
                include('excel/' . $currentPage . '.php');
                exit();
            } else {
                include('action/' . $currentPage . '.php');
            }
        } else {
            include('action/projectHome.php');
        }
    } else {
        include('action/projectHome.php');
    }
}
