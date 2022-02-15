<?php
include("db_info.php");
include("usable_functions.php");

if (isset($_GET["first_name"]) && isset($_GET["family_name"]) && isset($_GET["email"])  
&& isset($_GET["password"]) && isset($_GET["phone_number"])){
    
$first_name=$mySqli->real_escape_string($_GET["first_name"]); // filter
$family_name=$mySqli->real_escape_string($_GET["family_name"]);
$email=$mySqli->real_escape_string($_GET["email"]);
$password=$mySqli->real_escape_string($_GET["password"]);
$phone_number=$mySqli->real_escape_string($_GET["phone_number"]);

} else{
    die("There's a missing field."); 
}

$query1=$mySqli ->prepare("SELECT * FROM users WHERE email=? or Phone_number = ? ");
$query1 ->bind_param("ss", $email, $phone_number);
$query1 -> execute();
$query1 -> store_result();
$num_rows1 = $query1 ->num_rows;
$query1 ->fetch();

// checking user availability
if($num_rows1 !=0){
    die("User already exists please change your email or phone number!");
}
?>