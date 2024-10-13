<?php 
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userEmail"]) && $db->isUserAdmin($_SESSION["userid"])){
    $header = getallheaders();
    if(!checkCSRFToken($header)){
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    echo $db->elevateUserToAdmin($_POST["userEmail"]);
}else{
    echo json_encode(array("status"=> "error", "message" => "Couldn't elevate user to admin!"));
}

?>