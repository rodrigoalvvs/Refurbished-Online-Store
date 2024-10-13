<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
$db = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    if (isset($_POST["productId"])) {
        echo $db->toggleProductVisible($_POST["productId"]);
    }
}
?>