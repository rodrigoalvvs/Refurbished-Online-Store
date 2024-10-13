<?php
session_start();

include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");

$header = getallheaders();
if (!checkCSRFToken($header)) {
    echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
    return;
}

function onError()
{
    echo json_encode(array("status" => "error", "message" => "Couldn't remove product from cart!"));
}
function onSuccess()
{
    echo json_encode(array("status" => "success", "message" => "Product removed from the cart succesfully!"));
}

if (!isset($_SESSION["userid"]) || !isset($_POST["idx"])) {
    onError();
    die();
}

$cart = unserialize($_SESSION["cart"]);


if (isset($cart[$_POST["idx"]])) {
    unset($cart[$_POST["idx"]]);
}
$_SESSION["cart"] = serialize($cart);

onSuccess();