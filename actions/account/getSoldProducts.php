<?php 
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");

$db = Database::getInstance();

function respond($status, $message) : void{
    echo json_encode(["status"=> $status,"message"=> $message]);
    exit();
}

if(!isset($_SESSION["userid"])){
    respond("error", "You must be logged to access this page!");
}

$userProducts = $db->getSoldProducts($_SESSION["userid"]);
$products = array();

foreach($userProducts as $userProduct){
    $array = $db->getProductReceipt($userProduct["productId"]);
    $array["photos"] = getProductPhotos($userProduct["productId"]);
    $products[] =  $array;
}

echo json_encode(array("status"=> "success","message"=> "Retrieved sold products succesfully!","products"=> $products));



?>