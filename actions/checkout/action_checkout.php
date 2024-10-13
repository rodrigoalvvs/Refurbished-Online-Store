<?php

session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Product.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();


function respond($status, $message)
{
    echo json_encode(array("status" => $status, "message" => $message));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    if (!isset($_SESSION["userid"])) {
        respond("error", "You are not logged in!");
    }

    if (
        !isset($_POST["name"]) ||
        !isset($_POST["email"]) ||
        !isset($_POST["address"]) ||
        !isset($_POST["city"]) ||
        !isset($_POST["zip"]) ||
        !isset($_POST["cardNumber"]) ||
        !isset($_POST["cardholderName"])
    ) {
        respond("error", "One or more required fields are missing.");
    }

    // first check if all items in cart are available
    $cart = unserialize($_SESSION["cart"]);
    $sold = false;
    foreach ($cart as $key => $value) {
        if ($db->isProductSold($key->productId)) {
            $sold = true;
        }
    }
    if ($sold)
        respond("error", "One or more items in your cart are not available!");

    // none of the items were sold yet
    $response = $db->checkout($cart, $_SESSION["userid"], $_POST["name"], $_POST["email"], $_POST["address"], $_POST["city"], $_POST["zip"], $_POST["cardNumber"], $_POST["cardholderName"]);

    unset($_SESSION["cart"]);

    echo $response;
}

?>