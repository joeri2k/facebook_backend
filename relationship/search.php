<?php
include("../Database/db_info.php");
include("../usable_functions.php");

if (isset($_GET["search"])){
    $search = $mySqli->real_escape_string($_GET["search"]);
} else{
    die("Enter a name to search for."); 
}

$name = filteringSearch($search);


// getting query result
$query=$mySqli ->prepare("SELECT ID, first_name, family_name FROM users 
WHERE (CONCAT(first_name,' ', family_name)  LIKE ? or 
CONCAT(family_name,' ', first_name )  LIKE ?)
ORDER BY first_name, family_name"); // add comment

$query -> execute([$name."%",$name."%"]);
$query -> store_result();
$num_rows = $query ->num_rows;
$query->bind_result($id,$first_name,$family_name);

if($num_rows==0){
    $array_response["status"]= "user not found !";
} else{
    //iterate over the result retrieving one row at a time 
    $count = 0;
    while ($row = $query->fetch())
    {
        //buffer the row onto $data
       // $data[] = $first_name.' '.$family_name ;
        $array_response["result-".$count]= ["id" => $id,
        "first_name" => $first_name,
        "family_name" => $family_name];
        $count = $count + 1;

    }


}

$json_response=json_encode($array_response);
echo $json_response;
$query ->close();
$mySqli -> close();
?>