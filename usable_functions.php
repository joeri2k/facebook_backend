<?php

function encryption($id){
    $ecrypt_id_1 = str_replace(['1','5','9'], ['%','#','$'],((($id+2022)*975318642)-2000));
    $ecrypt_id_2 = base64_encode($ecrypt_id_1);
    return $ecrypt_id_2;
}

function decryption($encrypted_id){
    $decrypt_id_1 = str_replace(['%','#','$'],['1','5','9'] ,base64_decode($encrypted_id));
    $decrypt_id_2 = ((($decrypt_id_1+2000)/975318642)-2022);
    return $decrypt_id_2;
}

function checkPasswordStrength($password){

    $char = '/^(?=.*[a-z])(?=.*\\d).{8,}$/i'; // add comment
     return preg_match($char, $password);
}

function filteringSearch($string){

    $pieces = [];
    $string = trim(strtolower(trim(preg_replace('/\s+/', ' ', $string)))); // add comment
    //$pieces = explode(" ", $string, 2); // explode at most to 2 elements on white space
    return $string;
}
?>