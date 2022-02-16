<?php

header("Access-Control-Allow-Origin: *");

$db_host= "localhost";
$db_user="root";
$db_pass= null;
$db_name="facebookdb";

$mySqli= new mySqli ($db_host,$db_user,$db_pass,$db_name);
if(mysqli_connect_errno()){
    die("Connection Failure");
}

?>