<?php

include("../Database/db_info.php");
include("../usable_functions.php");

$user_id = $_GET["user_id"];
$post_id = $_GET["post_id"];

$query1=$mySqli ->prepare("SELECT * FROM likes WHERE User_ID = ? and Post_ID = ? ");
$query1 ->bind_param("ss", $user_id, $post_id);
$query1 -> execute();
$query1 -> store_result();
$num_rows1 = $query1 ->num_rows;
$query1 ->fetch();

// checking user availability
if($num_rows1 !=0){
    die("You already liked this post!");
}

// getting query result
$query=$mySqli ->prepare("INSERT INTO likes(User_ID,Post_ID) VALUES(?,?)");
//$query ->bind_param("s", $full_name);
$query ->bind_param("ss", $user_id, $post_id);
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