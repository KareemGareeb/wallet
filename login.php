<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'include/config.php';
require_once 'include/DB_Functions.php';
//$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);


////////////////////Example for json_decode////////////
//$jsonobj = '{"Peter":35,"Ben":37,"Joe":43}';
//$jsonArray = json_decode($jsonobj, true);//true for assocc array
//echo $jsonArray['Peter'];//35
////END OF Example for json_decode////////////////


//$postParams = file_get_contents('php://input');
//$paramArray = json_decode($postParams, true);

//$jsonParams = json_decode('{"email":"aa@bb.com"}', true);
//echo $jsonParams['email'];
//echo $jsonParams->email;//for second param 'false' which is the default
if (isset($_POST['phone']) && isset($_POST['password'])) {
//if (isset($paramArray['phone']) && isset($paramArray['password'])) {

    // receiving the post params
    $phone = $_POST['phone'];
    //$phone = $paramArray['phone'];

    $password = $_POST['password'];
    //$password = $paramArray['password'];

    // get the user by phone and password
    $user = getUserByPhoneAndPassword($phone, $password);
    //$user = $db->getUserByEmail($phone);

    if ($user != false) {
        // user is found
        $response["error"] = FALSE;
        $response["id"] = $user["id"];
        $response["name"] = $user["name"];
        $response["phone"] = $user["phone"];
        $response["balance"] = $user["balance"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        //$response["params"] = $postParams;
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["post"] = count($_POST);
    $response["error_msg"] = "Required parameters phone or password is missing!";
    echo json_encode($response);
}
?>
