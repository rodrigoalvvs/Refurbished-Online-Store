<?php

include_once ($_SERVER["DOCUMENT_ROOT"] . "/templates/common_tmp.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/templates/navbar_tmp.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/templates/account_tmp.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");

drawHead("order_history");
drawNavbar();
drawAccountMenu();


?>

<main>
    <div class="orders-container">
        <div class="order-table">
            <div class="orders-header">
                <span>Product Info</span>
                <span>Customer name</span>
                <span>Customer email</span>
                <span>Shipping details</span>
            </div>
        </div>
        <div class="orders">
            
        </div>
    </div>
</main>