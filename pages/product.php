<?php
session_start();

include_once ("../templates/common_tmp.php");
include_once ("../templates/navbar_tmp.php");
include_once ("../templates/footer_tmp.php");
include_once ("../includes/Database.php");

drawHead("product");
drawNavbar();

$productId = $_GET["productId"];
$db = Database::getInstance();
$product = $db->getProduct($productId);
if(!$product["productInfo"]) return;

$productCategory = $db->getCategoryName($product["productInfo"]["categoryId"]);
$productCondition = $db->getConditionName($product["productInfo"]["conditionId"]);

?>
<main>
    <div class="product-page" id="<?php echo $product["productInfo"]["productId"] ?>">

        <div class="miniature-img-container">
            <?php
            foreach ($product["productPhotos"] as $photo) {
                ?>
                <div id="<?php echo $photo ?>" class='miniature-img'>

                    <img alt="product-miniature-img"
                        src="../database/uploads/product/<?php echo $product["productInfo"]["productId"] ?>/<?php echo $photo ?>">

                </div>

                <?php
            }

            ?>
        </div>
        <div class="main-img-container">
            <img alt="product-main-img"
                src="../database/uploads/product/<?php echo $product["productInfo"]["productId"] ?>/<?php echo $product["productPhotos"][0] ?>">
        </div>

        <div class="product-info-container">
            <h2 class="product-title"><?php echo $product["productInfo"]["name"] ?></h2>
            <h3 class="product-subtitle"><?php echo $productCategory ?> -
                <?php echo $product["productInfo"]["gender"] ?>
            </h3>
            <h4 class="product-condition"><?php echo $productCondition; ?></h4>

            <span class="product-description"><?php echo $product["productInfo"]["description"] ?></span>
            <div class="product-add-container">
                <?php

                $basePrice = $product["productInfo"]["basePrice"];
                $discount = $product["productInfo"]["discount"];
                $finalPrice = round($basePrice * (1 - ($discount / 100)), 2);
                if ($discount != 0) {
                    ?>
                    <span class="base-price">
                        <?php echo number_format($basePrice, 2) . " €" ?>
                    </span>
                    <span class="product-price">
                        <?php echo number_format($finalPrice, 2) . " €" ?>
                    </span>
                    <?php
                } else {
                    ?>
                    <span class="product-price">
                        <?php echo number_format($finalPrice, 2) . " €" ?>
                    </span>
                    <?php
                }

                ?>
            </div>
            <div class="product-bttns">
            <?php

            if (isset($_SESSION["userid"])) {
                ?>
                    <input type="button" class="product-add-bttn" value="Buy">
                    <input type="button" class="product-start-message" value="Message Seller">
                    <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">
                    <?php
            }
            else{
                ?> 
                <a href="authentication.php" type="button" class="product-add-bttn product-login" >Login to interact</a>
                
                <?php
            }

            ?>
            </div>

            <div class="buy-info"></div>
        </div>
    </div>
    <h2 class="similar-title">Similar products</h2>
    <div class="alike-items-empty hidden">
        <i class="fa-regular fa-face-smile-wink"></i>
        <span>No similar items available. Discover more in our store!</span>
    </div>
    <div class="alike-items-container">

        <i class="fa-solid fa-angle-left"></i>
        <div class="items-container">

        </div>
        <i class="fa-solid fa-angle-right"></i>

    </div>
</main>

<?php
drawFooter();
?>