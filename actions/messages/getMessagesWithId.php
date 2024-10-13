<?php
session_start();
include_once("../../includes/Database.php");
$db = Database::getInstance();  

if(!isset($_SESSION["userid"]) || !isset($_POST["productId"])) echo json_encode(array("status"=> "error", "message" => "Couldn't retrieve user's messages"));

echo $db->getMessagesWithId($_SESSION["userid"], $_POST["productId"]);

?>