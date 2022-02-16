<?php

include("../Database/db_info.php");
include("../usable_functions.php");
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);
$user_id = decryption($_POST['user_id']);

$post_content = $_POST["post_content"];
$date0 = $_POST["date"];
$date1 = strtotime($date0);
$date = date("Y-m-d H:i:s",$date1);

// getting query result
$query=$mySqli ->prepare("INSERT INTO post(Users_ID,content_of_Post,Date_of_Post) VALUES(?,?,?)");
//$query ->bind_param("s", $full_name);
$query ->bind_param("sss", $user_id, $post_content, $date);
$query -> execute();
$query ->get_result();

// print_r($query);
$array_response=[];
if ($query -> affected_rows==1){
    $array_response = ["status" => "successful"];

}
else {
    $array_response = ["status" => "unsuccessful"];
}


$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>