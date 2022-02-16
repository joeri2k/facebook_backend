<?php

header("Access-Control-Allow-Origin: *");
include("../Database/db_info.php");
include("../usable_functions.php");

//check availability of email and pwd
$_POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST["email"]) && isset($_POST["password"])){
    $email= $mySqli->real_escape_string($_POST["email"] );
    $password = $mySqli->real_escape_string($_POST["password"]);
    $password = hash("sha256", $password);
} else{
    $array_response = ["error" => "Your Email or Password are missing."]; 
    $json_response=json_encode($array_response);
    echo $json_response;
    return;
}

// getting query result
$query=$mySqli ->prepare("SELECT ID FROM users WHERE email=? AND password=? ");
$query ->bind_param("ss", $email, $password);
$query -> execute();
$query -> store_result();
$query->bind_result($id);
$num_rows = $query ->num_rows;
$query ->fetch();

// checking user availability
if($num_rows==0){
    $array_response = ["status" => "Email or Password is incorrect !"];
} else{
    $encrypted_user_id = encryption($id);
    $array_response = ["status" => "logged in!",
    "user_id" => $encrypted_user_id];
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>