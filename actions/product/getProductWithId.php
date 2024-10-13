<?php 

include_once("../../includes/Database.php");


if(isset($_POST["productId"])){
    $db = Database::getInstance();
    echo json_encode(array("status"=> "success","message"=> "Product retrieved succesfully", "product" => $db->getProduct($_POST["productId"])));
}else{
    echo json_encode(array("status"=> "error","message"=> "Couldn't retrieve product", "product"=> null));
}

?>