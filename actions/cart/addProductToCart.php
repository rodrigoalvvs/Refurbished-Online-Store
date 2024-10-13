<?php
session_start();

include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Product.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");
$db = Database::getInstance();

$header = getallheaders();
if (!checkCSRFToken($header)) {
    echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
    return;
}

function respond($status, $message)
{
    echo json_encode(array("status" => $status, "message" => $message));
    exit();
}

if (!isset($_SESSION["userid"]) || !isset($_POST["productId"])) {
    respond("error", "Oops! Something went wrong. Please try again.");
}

$userId = $_SESSION["userid"];
$productId = $_POST["productId"];
$product = $db->getProduct($productId);

if (!$product) {
    respond("error", "Oops! Product not found.");
}

if ($db->userOwnsProduct($productId, $userId)) {
    respond("error", "Hold on! You already own this product.");
}

if ($db->isProductSold($productId)) {
    respond("error", "Sorry, this product has already found a new home.");
}


$productObj = new Product($productId, $product["productInfo"]["basePrice"], $product["productInfo"]["discount"]);


$cart = isset($_SESSION["cart"]) ? unserialize($_SESSION["cart"]) : array();

foreach ($cart as $key => $value) {
    if ($value->productId == $productId) {
        respond("error", "This product is already in your cart. No need for duplicates!");
    }
}



$cart[] = $productObj;
$_SESSION["cart"] = serialize($cart);
respond("success", "Awesome! Product added to your cart successfully.");
?>