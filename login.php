<?php

include("db_info.php");
include("usable_functions.php");

//check availability of email and pwd
if(isset($_GET["email"]) && isset($_GET["password"])){
    $email= $mySqli->real_escape_string($_GET["email"] );
    $password = $mySqli->real_escape_string($_GET["password"]);
    $password = hash("sha256", $password);
} else{
    die("Your Email or Password are missing."); 
}


?>