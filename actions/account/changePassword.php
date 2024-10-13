<?php
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$database = Database::getInstance();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = "success";
    $text = "Password Changed!";
    $header = getallheaders();
    if(!checkCSRFToken($header)){
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }

    if (isset($_POST["oldPassword"]) && isset($_POST["newPassword"])) {
        $response = json_decode($database->changePassword($_SESSION["userid"], $_POST["oldPassword"], $_POST["newPassword"]));

        if ($response->status === "error") {
            $status = "error";
            $text = $response->message . "\n";
            echo json_encode(array("status" => $status, "message" => $text));
            exit();
        }
    }

    echo json_encode(array("status" => $status, "message" => $text));
}
?>
