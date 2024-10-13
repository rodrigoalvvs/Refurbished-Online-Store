<?php 
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
$database = Database::getInstance();



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = "success";
    $text = "Changes Saved!";
    $header = getallheaders();
    if(!checkCSRFToken($header)){
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }

    if (isset($_POST["username"])) {
        $response = json_decode($database->changeUsersname($_SESSION["userid"], $_POST["username"]));
        
        if ($response->status === "error") {
            $status = "error";
            $text = $response->message . "\n";
            echo json_encode(array("status" => $status, "message" => $text));
            exit();
        }
    }
    if (isset($_POST["email"])) {
        $response = json_decode($database->changeEmail($_SESSION["userid"], $_POST["email"]));
        if ($response->status === "error") {
            $status = "error";
            $text = $response->message . "\n";
            echo json_encode(array("status" => $status, "message" => $text));
            exit();
        }
    }
    if (isset($_POST["address"])) {
        $response = json_decode($database->changeAddress($_SESSION["userid"], $_POST["address"]));
        if ($response->status === "error") {
            $status = "error";
            $text = $response->message . "\n";
            echo json_encode(array("status" => $status, "message" => $text));
            exit();
        }
    }
    if (isset($_POST["postalCode"])) {
        $response = json_decode($database->changeZIP($_SESSION["userid"], $_POST["postalCode"]));
        if ($response->status === "error") {
            $status = "error";
            $text = $response->message . "\n";
            echo json_encode(array("status" => $status, "message" => $text));
            exit();
        }
    }
    if (isset($_POST["phoneNumber"])) {
        $response = json_decode($database->changePhone($_SESSION["userid"], $_POST["phoneNumber"]));
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