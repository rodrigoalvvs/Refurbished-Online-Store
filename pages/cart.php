<?php

include_once ("../templates/common_tmp.php");
include_once ("../templates/navbar_tmp.php");
include_once ("../templates/footer_tmp.php");
include_once ("../includes/Database.php");

session_start();
drawHead("cart");
drawNavbar();

$productsInCart = unserialize($_SESSION["cart"]);
$db = Database::getInstance();
$total = 0;
$idx = 0;
?>

<main>
    <div class="cart-container">
        <header class="cart-header">
            <i class="fa-solid fa-cart-shopping"></i>
            <h1 class="cart-title">Cart</h1>
        </header>
        <div class="cart-content">

        </div>
        <div class="cart-empty hidden">
            <i class="fa-regular fa-face-smile-wink"></i>
            <span>Cart's waiting for some stylish additions!</span>
        </div>

        <div class="card-total-container">
            <span class="card-total">Total</span>
            <span class="card-price"><?php echo $total ?> €</span>
        </div>
        <footer class="cart-footer">
            <a href="checkout.php">Complete order</a>
        </footer>
        <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">
    </div>
</main>