<?php

include("../Database/db_info.php");
include("../usable_functions.php");

//check availability of email and pwd
if(isset($_GET["email"]) && isset($_GET["password"])){
    $email= $mySqli->real_escape_string($_GET["email"] );
    $password = $mySqli->real_escape_string($_GET["password"]);
    $password = hash("sha256", $password);
} else{
    die("Your Email or Password are missing."); 
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
    $array_response = ["status" => "logged in!"];
    $encrypted_user_id = encryption($id);
    $array = ["user_id" => $encrypted_user_id];
    echo json_encode($array);
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>