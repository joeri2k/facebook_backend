<?php

include("../Database/db_info.php");
include("../usable_functions.php");
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

$user_id = decryption($_POST['user_id']);
//decryption($user_id)

$query=$mySqli ->prepare("SELECT users.ID, users.first_name, users.family_name, 
post.ID,post.content_of_Post, post.Date_of_Post 
FROM post
Inner Join friends On post.Users_ID = friends.Users_recieved_ID
Inner Join users On post.Users_ID = users.ID
Where friends.Status_of_Request = 'confirmed' And friends.Users_sent_ID = ?
UNION
SELECT users.ID, users.first_name, users.family_name, post.ID,post.content_of_Post, post.Date_of_Post 
FROM post
Inner Join friends On post.Users_ID = friends.Users_sent_ID
Inner Join users On post.Users_ID = users.ID
Where friends.Status_of_Request = 'confirmed' And friends.Users_recieved_ID = ?
UNION
SELECT users.ID, users.first_name, users.family_name, 
post.ID,post.content_of_Post, post.Date_of_Post 
FROM post
Inner Join users On post.Users_ID = users.ID
Where  users.ID = ?
ORDER BY Date_of_Post DESC");

$query ->bind_param("sss", $user_id,$user_id, $user_id);
$query -> execute();
$query -> store_result();
$query->bind_result($userID,$firstname,$familyname,$postID,$postcontent,$postDate);
$num_rows = $query ->num_rows;

if($num_rows==0){
    $array_response["status"]= "Posts not found !";
} else{
    //iterate over the result retrieving one row at a time 
    $count = 0;
    while ($row = $query->fetch())
    {
        //buffer the row onto $data
        $array_response["result-".$count]= ["first_name" => $firstname,
        "family_name" => $familyname,
        "contentPost" => $postcontent];
        $count = $count + 1;

    }
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>