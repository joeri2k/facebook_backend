<?php
include("db_info.php");

$post_id = $_GET["post_id"];


$query = $mySqli ->prepare("DELETE FROM post 
WHERE ID = ?");
$query ->bind_param("s", $post_id);
$query -> execute();
$query -> store_result();

// print_r($query);
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