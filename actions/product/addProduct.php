<?php

session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }

    function validateInteger($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    function validateFloating($value)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    if (
        !empty($_POST["name"]) &&
        !empty($_POST["description"]) &&
        !empty($_POST["size"]) &&
        !empty($_POST["gender"]) &&
        validateFloating($_POST["price"]) &&
        validateInteger($_POST["discount"]) &&
        !empty($_POST["category"] && !empty($_SESSION["userid"] && !empty($_POST["condition"])))
    ) {
        $jsonInfo = json_decode($db->addProduct(
            $_SESSION["userid"],
            $_POST["category"],
            $_POST["condition"],
            $_POST["gender"],
            $_POST["name"],
            $_POST["description"],
            $_POST["price"],
            $_POST["discount"],
            $_POST["size"],
        )
        );

        if ($jsonInfo->status == "success") {
            // move all temp files to a permanent folder
            $source = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/temp/" . $_SESSION["userid"] . "/";
            $files = glob($source . '*');
            $target = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/product/" . $jsonInfo->productId;
            if (is_dir($target)) {
                rmdir($target);
            }
            mkdir($target, 0777, true);

            foreach ($files as $file) {
                $filename = basename($file);
                $destinationPath = $target . "/" . $filename;
                rename($file, $destinationPath);
            }
        }

        echo json_encode($jsonInfo);
    } else {
        echo json_encode(array("status" => "error", "message" => "Invalid input. Please check your data and try again."));
    }
}


?>