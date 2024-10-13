<?php
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();  

$header = getallheaders();
if (!checkCSRFToken($header)) {
    echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
    return;
}

if(!isset($_SESSION["userid"])) echo json_encode(array("status"=> "error", "message" => "Couldn't retrieve user's messages"));

echo $db->getUserMessages($_SESSION["userid"]);

?>