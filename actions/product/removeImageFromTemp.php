<?php

session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");

if (isset($_POST["image"])) {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    
    // Image is set so i need to remove from temp folder
    $file_path = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/temp/" . $_SESSION["userid"] . "/" . $_POST["image"]; // Specify the path to the file you want to delete

    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            echo "File deleted successfully.";
        } else {
            echo "Error deleting file.";
        }
    } else {
        echo "File does not exist.";
    }
}

?>