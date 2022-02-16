<?php
include("../Database/db_info.php");
include("../usable_functions.php");
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);


if (isset($_POST["name"]) && isset($_POST["family_name"]) && isset($_POST["email"])  
&& isset($_POST["password"]) && isset($_POST["phone_number"])){
    
$first_name=$mySqli->real_escape_string($_POST["name"]); // filter
$family_name=$mySqli->real_escape_string($_POST["family_name"]);
$email=$mySqli->real_escape_string($_POST["email"]);
$password=$mySqli->real_escape_string($_POST["password"]);
$phone_number=$mySqli->real_escape_string($_POST["phone_number"]);

} else{
    $array_response = ["error" => "There's a missing field."]; 
    $json_response=json_encode($array_response);
    echo $json_response;
    return;
}

$query1=$mySqli ->prepare("SELECT * FROM users WHERE email=? or Phone_number = ? ");
$query1 ->bind_param("ss", $email, $phone_number);
$query1 -> execute();
$query1 -> store_result();
$num_rows1 = $query1 ->num_rows;
$query1 ->fetch();

// checking user availability
if($num_rows1 !=0){
    $array_response = ["status" => "User already exists"]; 
    $json_response=json_encode($array_response);
    echo $json_response;
    return;
} else if (checkPasswordStrength($password)){
    $password = hash("sha256", $password);
} else{
    $array_response = ["status" => "Password not strong."]; 
    $json_response=json_encode($array_response);
    echo $json_response;
    return;
}

$query=$mySqli ->prepare("INSERT INTO users(first_name,family_name,email,password,Phone_number) VALUES(?,?,?,?,?)");
$query ->bind_param("sssss", $first_name,$family_name,$email,$password,$phone_number );
$query -> execute();
$query -> store_result();

$array_response=[];
if ($query -> affected_rows == 1){
    
    $array_response = ["status" => "successful"];

}
else {
    $array_response = ["status" => "unsuccessful"];
}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>