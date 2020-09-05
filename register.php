<?php

 
require_once 'include/DB_Functions.php';
require_once 'include/config.php';
 
// json response array
$response = array("error" => FALSE);
$_POST['name'] = "kareem";
$_POST['email']="cc@bb.com";
$_POST['password'] = "123456";
$_POST['city'] = "sirte";
$_POST['phone'] = "91999999444";
 
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['city']) && isset($_POST['phone'])) {
 
    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
 
    // check if user is already existed with the same phone number
    if (isUserExisted($link, $phone)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User with phon number ".$phone." already exists ";
        echo json_encode($response);
    } else {
        // create a new user
        $user = storeUser($link, $name, $email, $phone, $password, $city);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["user"]["phone"] = $user["phone"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            //$response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>