<?php 
    // Should be a table with all the products owned by a specific user
    // Product info - Price - Views - Active
    declare(strict_types=1);

    function drawProductTable($user_id) : void{
    // should invoke a php file that retrieves from db all products 
?>

<div class="products-controller">
    <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">
    <div class="new-product-container">
        <input type="button" class="newproduct-bttn" value="Add new product"/>
        <label for="newproduct-bttn"><i class="fa-solid fa-plus"></i></label>
    </div>
    <form class="new-product-form hidden">
        <div class="add-header">
            <div class="product-header">
                <i class="fa-solid fa-store"></i>
                <h2>Add New Product</h2>
            </div> 
            <div class="add-product-container">

                <label for="add-product">
                        <i class="fa-solid fa-check"></i>
                </label>
                <input type="button" name="add-product" class="add-product" value="Add Product">
            </div>
        </div>
        <div class="left-container">
            <h3>General Information</h3>
            <label for = "product-name">Product Name</label>
            <input type="text" name="product-name" class="product-name" placeholder="Name..."/>

            <label for ="product-desc">Product Description</label>
            <input type="textarea" name="product-desc" class="product-desc" placeholder="Description..."/>

            <div class="size-container">
                <label for= "product-size">Size</label>
                <select name="product-size" id="product-size">
                <option value="" selected disabled>Please select an option</option>
            </select>
            </div>
            <div class="gender-container">
                <label for="gender">Gender</label><br>
                <input type="radio" id="male" name="gender" value="male">
                <label for="male">Male</label><br>
                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Female</label><br>
                <input type="radio" id="unisex" name="gender" value="unisex">
                <label for="unisex">Unisex</label><br>
            </div>

            <h3>Pricing and Stock</h3>
            <label for="price">Base Price</label>
            <input type="text" name="price" class="price"/>

            <label for="discount">Discount</label>
            <input type="number" name="discount" class="discount"/>

        </div>
        <div class="right-container">
            <h3>Upload Image</h3>
            <div class="image-upload-container">

                <img src="../assets/img/banner.png" draggable="false"class="main-image">
                <div class="all-images" >
    
                </div>
                <label for="imageUpload" class="imagelabel">
                    <i class="fa-solid fa-circle-plus"></i>
                </label>
                <input type="file" id="imageUpload" name="imageUpload" class="imageUpload" multiple>
            </div>
            
    
            <h3>Additional Info</h3>
            <label for="product-category">Product Category</label>
            <select name="product-category" id="product-category">
                <option value="" selected disabled>Please select an option</option>
            </select>
            <label for="product-condition">Product Condition</label>
            <select name="product-condition" id="product-condition">
                <option value="" selected disabled>Please select an option</option>
            </select>
            <div class="output-info"/></div>

            
        </div>
    </form>
    <div class="products-table">
        <div class="products-header">
            <span>Product Info</span>
            <span>Price</span>
            <span>Active</span>
            <span>Delete</span>
        </div>
    </div>
</div>

<?php
}
?>


