<?php

include("../Database/db_info.php");
include("../usable_functions.php");

$my_id = $_GET['my_id'];
//decryption($user_id)
$user_id = $_GET['user_id'];

$query1=$mySqli ->prepare("SELECT * FROM friends 
WHERE (Users_sent_ID = ? AND Users_recieved_ID = ?) 
OR ( Users_recieved_ID= ? AND Users_sent_ID = ?)");
$query1 ->bind_param("ssss", $my_id, $user_id, $my_id ,$user_id);
$query1 -> execute();
$query1 -> store_result();
$num_rows1 = $query1 ->num_rows;
$query1 ->fetch();

// checking user availability
if($num_rows1 !=0){
    die("A request is pending or already friends!");
}

$query = $mySqli ->prepare("INSERT INTO friends(Users_sent_ID, Users_recieved_ID, Status_of_Request) 
VALUES(?,?,'pending')");
$query ->bind_param("ss", $my_id, $user_id);
$query -> execute();
$query -> store_result();
//$query ->get_result();


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