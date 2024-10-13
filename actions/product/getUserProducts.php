<?php
session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    if (isset($_SESSION["userid"])) {
        echo $db->getUserProducts($_SESSION["userid"]);
    }
}
?>