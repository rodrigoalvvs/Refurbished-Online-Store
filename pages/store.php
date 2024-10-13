<?php

include_once ("../templates/common_tmp.php");
include_once ("../templates/navbar_tmp.php");
include_once ("../templates/footer_tmp.php");
include_once ("../templates/card_tmp.php");
include_once ("../includes/Database.php");

drawHead("store");
drawNavbar();
?>
<main>
    <aside class="search-filter">
        <span class="push-sidebar sidebar-hidden"><i class="fa-solid fa-arrow-right"></i></span>
        <form class="filter-container">
            <div class="filter-item category">
                <span>Category</span>
                <i class="fa-solid fa-chevron-down  category-dropdown"></i>
            </div>
            <select class="filter-select category-select hidden">
                <option class="default-option" value='' selected >Please select an option</option>
            </select>
            <div class="filter-item size">
                <span>Size</span>
                <i class="fa-solid fa-chevron-down size-dropdown"></i>
            </div>
            <select class="filter-select size-select hidden">
                <option class="default-option" value='' selected>Please select an option</option>
            </select>
            <div class="filter-item price">
                <span>Price</span>

                <i class="fa-solid fa-chevron-down price-dropdown"></i>
            </div>
            <div class="price-container hidden">
                <input type="number" class="min" placeholder="Minimum price">
                <input type="number" class="max" placeholder="Maximum price">
            </div>

            <div class="filter-item condition">
                <span>Condition</span>
                <i class="fa-solid fa-chevron-down condition-dropdown"></i>
            </div>
            <select class="filter-select condition-select hidden">
                <option class="default-option" value='' selected >Please select an option</option>
            </select>
            
            <div class="filter-bttn-container">
                <i class="fa-solid fa-filter"></i>
                <input type="button" class="filter-bttn" value="Filter">
                <input type="button" class="clear-filter-bttn" value="Clear">
            </div>
        </form>
    </aside>
    <div class="store-container">
        <div class="products-container">
        </div>
        <input type="button" class="show-more" value="Show More">
    </div>
</main>

<?php
drawFooter();
?>