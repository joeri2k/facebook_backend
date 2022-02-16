<?php

include("../Database/db_info.php");
include("../usable_functions.php");
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

$post_id = $_POST['post_id'];
//decryption($user_id)


$query=$mySqli ->prepare("SELECT COUNT(*) FROM likes
LEFT JOIN post ON likes.Post_ID =  post.ID
WHERE post.ID = ?");

$query ->bind_param("s", $post_id);
$query -> execute();
$query -> store_result();
$query->bind_result($number_likes);
$query->fetch();

$array_response["number_likes"]= $number_likes;
$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>