<?php

include("db_info.php");
include("usable_functions.php");

$my_id = $_GET['my_id'];
//decryption($user_id)
$user_id = $_GET['user_id'];

$query=$mySqli ->prepare("SELECT ID, Status_of_Request FROM friends 
WHERE (Users_sent_ID = ? AND Users_recieved_ID = ?) 
OR ( Users_recieved_ID= ? AND Users_sent_ID = ?)");
$query ->bind_param("ssss", $my_id, $user_id, $my_id ,$user_id);
$query -> execute();
$query -> store_result();
$query->bind_result($relationship_id, $status);
$num_rows = $query ->num_rows;
$query ->fetch();

if($num_rows==0){
    $array_response["relation_id"] = $relationship_id;
    $array_response["relationship"] = "NA";
} else if( $status == "confirmed"){
    $array_response["relation_id"] = $relationship_id;
    $array_response["relationship"] = "friends";
} else if( $status == "pending"){
    $array_response["relation_id"] = $relationship_id;
    $array_response["relationship"] = "pending";
} else if( $status == "blocked"){
    $array_response["relation_id"] = $relationship_id;
    $array_response["relationship"] = "blocked";
} else{
    $array_response["error"] = "it's complicated";
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>