<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'include/config.php';
require_once 'include/db_functions.php';
$response = array("error" => FALSE);
if (isset($_POST['phone']) ) {
    $phone = $_POST['phone'];
    //////////////// GET USER HISTORY ////////////////////////////
    $sql  = "SELECT type, card_value as value, date FROM `purchase` WHERE phone = ? "
            . "UNION ALL "
            . "SELECT 'deposit', value, date FROM deposit WHERE phone=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $phone, $phone);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $history = mysqli_fetch_all($result, MYSQLI_ASSOC );
       echo  json_encode($history);
        
    }
} else {
    $response['error'] = true;
    $response['msg'] = "parameters not set";
    echo "[". json_encode($response). "]";
    //echo json_encode($response);
}
