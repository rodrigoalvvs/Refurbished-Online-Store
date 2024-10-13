<?php 
session_start();
function onError(){
    echo json_encode(array("status"=> "error", "message" => "Couldn't get cart contents!"));
}
function onSuccess(){
    echo json_encode(array("status"=> "success", "message"=> "Cart contents retrieved succesfully", "cart" => unserialize($_SESSION["cart"])));
}

if(isset($_SESSION["cart"])){
    onSuccess();
}
else{
    onError();
}



