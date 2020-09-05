<?php

require_once("include/config.php");

//$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

$sql = "SELECT type, card_value FROM repository group by type, card_value";
$result = mysqli_query($link, $sql);
//$arrvalues = mysqli_fetch_all($result, MYSQLI_NUM);
$arrvalues = mysqli_fetch_all($result, MYSQLI_ASSOC);//indexed array of associative arrays
echo json_encode($arrvalues);// json array of json objects
/** this is for test
 * indexed array encoded to json array
 * $testArray = array("sdfsf","olololo");
 * echo json_encode($testArray);//["sdfsf","olololo"]
 * 
 * associative array encoded to json object
 * $testArray = array("test1"=>"sdfsf","test2"=>"olololo");
 * echo json_encode($testArray);//{"test1":"sdfsf","test2":"olololo"}
 */

?>






	