<?php 

session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();

$header = getallheaders();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    if(!isset($_POST["productId"]) || !isset($_POST["senderId"]) || !isset($_POST["receiverId"]) || !isset($_POST["data"]) || !isset($_POST["date"])){
        echo json_encode(array("status" => "error", "message" => "Couldn't send message!"));
        die();
    } 
    
    echo $db->sendMessage($_POST["productId"], $_POST["senderId"], $_POST["receiverId"], $_POST["data"], $_POST["date"], $_POST["time"]);
}

?>