<?php
session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");

$header = getallheaders();
if(!checkCSRFToken($header)){
    echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
    return;
}

if (isset($_FILES["user-avatar"]) && isset($_SESSION["userid"])) {
    $allowed = array("gif", "png", "jpg", "webp");
    $fileType = pathinfo($_FILES["user-avatar"]["name"], PATHINFO_EXTENSION);

    $target_dir = "../../database/uploads/ProfilePictures/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $originalFileName = basename($_FILES["user-avatar"]["name"]);
    $newFileName = $_SESSION["userid"] . "." . pathinfo($_FILES["user-avatar"]["name"], PATHINFO_EXTENSION);
    $targetFile = $target_dir . $newFileName;

    if (!file_exists($_FILES["user-avatar"]["tmp_name"])) {
        echo json_encode(array("status" => "error", "message" => "Uploaded file does not exist"));
        exit();
    }

    if (!in_array($fileType, $allowed)) {
        echo json_encode(array("status" => "error", "message" => "Please upload a file in one of the following formats: [jpg, gif, png, webp]"));
        exit();
    }

    if (move_uploaded_file($_FILES["user-avatar"]["tmp_name"], $targetFile)) {
        // On success i want to erase all pictures that belong to userid (except for the file that i just moved)
        $userFiles = glob($target_dir . $_SESSION["userid"] . ".*");
        $newFile = $target_dir . $_SESSION["userid"] . "." . $fileType;
        $filestoerase = array_diff($userFiles, [$newFile]);

        foreach ($filestoerase as $file) {
            unlink($file);
        }

        echo json_encode(array("status" => "success", "message" => "File uploaded successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to upload file"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Failed to upload file"));
}
?>