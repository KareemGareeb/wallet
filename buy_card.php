<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'include/config.php';
require_once 'include/db_functions.php';
$response = array("error" => FALSE);
if (isset($_POST['phone']) && isset($_POST['type']) && isset($_POST['card_value'])) {
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $card_value = $_POST['card_value'];
    //////////////// SELECT a CARD ////////////////////////////
    $stmt = mysqli_prepare($link, "SELECT * FROM repository WHERE type=? AND card_value =? GROUP BY card_value");
    mysqli_stmt_bind_param($stmt, "si", $type, $card_value);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)==0){
            $response['error'] = true;
            $response['msg'] = "required cart not available";
            $response['msg_ar'] = "فئة الكرت المطلوب غير متاحة";
            echo "[". json_encode($response, JSON_UNESCAPED_UNICODE). "]";
            exit();
        }
        $card = mysqli_fetch_assoc($result);
        $type = $card['type'];
        $card_value = $card['card_value'];
        $SN = $card['SN'];
        $code = $card['code'];
        $date = date('y-m-d H:i:s', time());
        
        $response['error'] = false;
        $response['type'] = $type;
        $response['card_value'] = $card_value;
        $response['SN'] = $SN;
        $response['code'] = $code;
        $card_id = $card['id'];
        
        /////////////Insert purchased cart into purchase table////////////////
        mysqli_query($link, "INSERT INTO purchase( phone, type, card_value, SN, code, date)"
                . "VALUES ('$phone', '$type', '$card_value', $SN, $code, '$date' )") or die(mysqli_error($link));

        //////////UPDATE USER BALANCE AND REPOSITORY/////////////////////////////
        mysqli_query($link, "UPDATE `user` SET `balance`= balance-'$card_value' WHERE phone='$phone'");
        mysqli_query($link, "DELETE FROM repository WHERE id= '$card_id'");

        //////////GET NEW BALANCE//////////////////////////////
        $response['new_balance'] = getUserBalance($link, $phone);
        echo "[". json_encode($response). "]";
        
    }
} else {
    $response['error'] = true;
    $response['msg'] = "parameters not set";
    echo "[". json_encode($response). "]";
    //echo json_encode($response);
}
