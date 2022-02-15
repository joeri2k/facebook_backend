<?php
include("db_info.php");

$user_id = $_GET["user_id"];
$post_content = $_GET["post"];
$date0 = $_GET["date"];
$date1 = strtotime($date0);
$date = date("Y-m-d H:i:s",$date1);
echo $date;

// getting query result
$query=$mySqli ->prepare("INSERT INTO post(Users_ID,content_of_Post,Date_of_Post) VALUES(?,?,?)");
//$query ->bind_param("s", $full_name);
$query ->bind_param("sss", $user_id, $post_content, $date);
$query -> execute();
$query ->get_result();

// print_r($query);
$array_response=[];
if ($query -> affected_rows==1){
    $array_response = ["result" => "successful"];

}
else {
    $array_response = ["result" => "unsuccessful"];
}


$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>