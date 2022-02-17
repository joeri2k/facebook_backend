<?php

include("../Database/db_info.php");
include("../usable_functions.php");
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);
$user_id = decryption($_POST['user_id']); //decryption($user_id)


$query=$mySqli ->prepare("SELECT ID, first_name, family_name FROM users 
WHERE ID != (SELECT ID FROM users 
LEFT JOIN friends F ON U.ID = F.Users_recieved_ID 
WHERE (F.Users_sent_ID = ? AND F.Status_of_Request = 'confirmed')
UNION 
SELECT U.ID, U.first_name, U.family_name FROM users U 
LEFT JOIN friends F ON U.ID = F.Users_sent_ID 
WHERE (F.Users_recieved_ID = ? AND F.Status_of_Request = 'confirmed'))");

$query ->bind_param("ss", $user_id, $user_id);
$query -> execute();
$query -> store_result();
$query->bind_result($user_id, $user_first_name,$user_family_name);
$num_rows = $query ->num_rows;

if($num_rows==0){
    $array_response["status"]= "users not found !";
} else{
    //iterate over the result retrieving one row at a time 
    $count = 0;
    while ($row = $query->fetch())
    {
        //buffer the row onto $data
       // $data[] = $first_name.' '.$family_name ;
        $array_response["result-".$count]= ["user_id" => $user_id,
        "first_name" => $user_first_name,
        "family_name" => $user_family_name];
        $count = $count + 1;

    }
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>