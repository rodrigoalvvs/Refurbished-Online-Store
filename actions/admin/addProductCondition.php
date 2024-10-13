<?php 
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["conditionName"]) && isset($_POST["conditionDescription"]) && $db->isUserAdmin($_SESSION["userid"])){
    $header = getallheaders();
    if(!checkCSRFToken($header)){
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    echo $db->addCondition($_POST["conditionName"], $_POST["conditionDescription"]);
}else{
    echo json_encode(array("status"=> "error", "message" => "Couldn't add condition!"));
}

?>