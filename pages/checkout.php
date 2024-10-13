<?php
session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/templates/common_tmp.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Product.php");

$db = Database::getInstance();

drawHead("checkout");

if (count(unserialize($_SESSION["cart"])) === 0) {
    header("Location: mainpage.php");
    exit();
}

function calculateCheckout(): float
{
    if (!isset($_SESSION["cart"]))
        return 0;
    $cost = 0;
    $cart = unserialize($_SESSION["cart"]);
    foreach ($cart as $key => $value) {
        $cost += $value->getProductPrice();
    }
    return $cost;
}
function calculateSaved(): float
{
    if (!isset($_SESSION["cart"]))
        return 0;
    $saved = 0;
    $cart = unserialize($_SESSION["cart"]);
    foreach ($cart as $key => $value) {
        $saved += $value->getAbsoluteDiscount();
    }
    return $saved;
}


?>


<main class="checkout">
    <header>
        <a href="cart.php">
            <i class="fa-solid fa-arrow-left-long"></i>
            <span>Back</span>
        </a>

    </header>
    <div class="checkout-container">
        <aside class="checkout-left">
            <header>
                <h1>Checkout</h1>
                <h4>Ready to Checkout? Let's Make It Official!</h4>
            </header>

            <form class="personal-info-form">
                <h3>Personal Information</h3>
                <label for="first-name">First name</label>
                <input type="text" class="checkout-input" id="first-name" placeholder="John">

                <label for="last-name">Last name</label>
                <input type="text" class="checkout-input" id="last-name" placeholder="doe">

                <label for="email-address">Email address</label>
                <input type="text" class="checkout-input" id="email" placeholder="John@mail.com">

                <label for="address">Delivery address</label>
                <input type="text" class="checkout-input" id="address" placeholder="123 Main St Apt 4B">

                <label for="city">City</label>
                <input type="text" class="checkout-input" id="city" placeholder="Springfield">

                <label for="zip">Zip code</label>
                <input type="text" placeholder="12345" id="zip">
            </form>
        </aside>
        <aside class="checkout-right">
            <div class="card-checkout-container">
                <img src="../assets/img/visa.png" alt="Visa Logo">
                <input type="text" id="cardNumber" name="cardNumber" placeholder="4111 1111 1111 1111" maxlength="16"
                    required>
                <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YY" maxlength="5" required>
                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                <input type="text" id="cardholderName" name="cardholderName" placeholder="John Doe" maxlength="26"
                    required>
            </div>
            <div class="checkout-total">
                <h3>Amount saved: <span id="totalAmount">
                        <?php
                        echo calculateSaved();
                        ?>

                        €</span>

                </h3>
                <h2>Total: <span id="totalAmount">
                        <?php
                        echo calculateCheckout();
                        ?>

                        €</span>

                </h2>
            </div>
            <div class="checkout-info"></div>
            <button type="submit" class="checkout-button">Pay now</button>
        </aside>

    </div>

    <input type="hidden" name="csrf" class="csrf" value="<?= $_SESSION['csrf'] ?>">
</main>