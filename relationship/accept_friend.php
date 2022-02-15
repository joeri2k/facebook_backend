<?php

include("db_info.php");
include("usable_functions.php");

$my_id = $_GET['my_id'];
//decryption($user_id)
$user_id = $_GET['user_id'];

$query1=$mySqli ->prepare("SELECT * FROM friends 
WHERE (Users_sent_ID = ? AND Users_recieved_ID = ?)");
$query1 ->bind_param("ss", $user_id, $my_id);
$query1 -> execute();
$query1 -> store_result();
$num_rows1 = $query1 ->num_rows;
$query1 ->fetch();

// checking availability
if($num_rows1 == 0){
    die("no request pending!");
}

$query = $mySqli ->prepare("UPDATE friends 
SET  Status_of_Request = 'confirmed'
WHERE (Users_sent_ID = ? AND Users_recieved_ID = ?)");
$query ->bind_param("ss", $user_id, $my_id);
$query -> execute();
$query -> store_result();


// print_r($query);
$array_response=[];
if ($query -> affected_rows == 1){
    
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