<?php
session_start();
include_once($_SERVER["DOCUMENT_ROOT"] . "/templates/common_tmp.php");
include_once($_SERVER["DOCUMENT_ROOT"] ."/includes/macros.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");

$db = Database::getInstance();
$productId = $_GET["productId"];

$product = $db->getSoldProductById($productId)[0];

if(!isset($_SESSION["userid"]) || $_SESSION["userid"] != $product["sellerId"] ) return;

drawHead("receipt");

?>

<main class="receipt">
    <header>
        <h1>Item "<?php echo $product["name"];?>"</h1>
        <h2>Receipt</h2>
        <h4>Purchased @ Refurbished</h4>
        <span>Sold to <?php echo $product["customer_name"] ?></span>
    </header>
    <div class="shipping-info">
        <div>
            <span class="bill-to">Bill To</span>
            <span class="address-to"><?php echo $product["delivery_address"]?></span>
            <span class="zip-to"><?php echo $product["zip_code"]?></span>
            <span class="city-to"><?php echo $product["city"]?></span>
        </div>
        <div>
            <span class="ship-to">Ship To</span>
            <span class="address"><?php echo $product["delivery_address"]?></span>
            <span class="zip"><?php echo $product["zip_code"]?></span>
            <span class="city"><?php echo $product["city"]?></span>
        </div>
        <div>
            <span class="sale-id-title">Sale #</span>
            <span class="sale-id"><?php echo $product["id"]?></span>
        </div>
        <div>
            <span class="receipt-date">Receipt Date</span>
            <span class="receipt-date-text"><?php echo date("Y/m/d")?></span>
        </div>
        <div>
            <span class="purchase-date">Purchase Date</span>
            <span class="purchase-date-text"><?php echo (new DateTime($product["timestamp"]))->format("Y/m/d") ?></span>
        </div>
        

    </div>
    <div class="product-info">
        <h2>Product Info</h2>
        <span class="product-name">
            <?php echo $product["name"]?>
        </span>
        <span class="product-description">
            <?php echo $product["description"]?>
        </span>
        <span class="product-category">
            <?php echo $product["categoryName"]?>
        </span>
        <span class="product-size">
            <?php echo $product["size"] ?>
        </span>
        <span class="product-baseprice">
            Base Price: <?php echo number_format($product["basePrice"],2)?>€
        </span>
        <span class="product-discount">
            Discount: -<?php echo number_format($product["basePrice"] * ($product["discount"] / 100), 2) ?>€
        </span>
        <span class="final-price">
            Price: <?php echo number_format($product["basePrice"] * (1- ($product["discount"] / 100)), 2)?>€
        </span>

    </div>
</main>