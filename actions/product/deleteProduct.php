<?php

session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();



function deleteDirectory($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }

    return rmdir($dir);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productId"])) {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }

    $productId = $_POST["productId"];
    // remove product photos
    $productDir = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/product/" . $productId . "/";
    deleteDirectory($productDir);

    // remove product from db
    echo $db->removeProduct($productId);

}

?>