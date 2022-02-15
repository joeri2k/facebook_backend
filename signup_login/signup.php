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
if (checkPasswordStrength($password)){
    $password = hash("sha256", $password);
} else{
    die ("Password not strong!");
}

$query=$mySqli ->prepare("INSERT INTO users(first_name,family_name,email,password,Phone_number) VALUES(?,?,?,?,?)");
$query ->bind_param("sssss", $first_name,$family_name,$email,$password,$phone_number );
$query -> execute();
$query -> store_result();

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