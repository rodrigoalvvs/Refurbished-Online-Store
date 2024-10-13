<?php
include_once("../../includes/Database.php");
$db = Database::getInstance();

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["productId"])){
    echo $db->getSimilarProducts($_POST["productId"]);
    die();
}
echo json_encode(array("status"=> "error","message"=> "Couldn't retrieve similar products!"));

