<?php 

// first check if user is admin
session_start();
include_once ("../templates/common_tmp.php");
include_once ("../templates/navbar_tmp.php");
include_once ("../templates/footer_tmp.php");
include_once ("../includes/Database.php");

$db = Database::getInstance();

if(!isset($_SESSION["userid"]) ||  $db->isUserAdmin($_SESSION["userid"]) === false){
    header("Location: mainpage.php");
}

drawHead("admin");
drawNavbar();

?>

<main>

    <section class="admin-section elevate-user">
        <h2>Elevate a user to admin</h2>
        <input type="text" class="input-field email-input" placeholder ="User email">
        <input type="button" class="elevate-user-bttn" value="Submit"> 
        <i class="fa-solid fa-check elevate-check hidden"></i>
    </section>
    <section class="admin-section add-new-condition">
        <h2>Add a new condition</h2>
        <input type="text" class="input-field condition-name" placeholder ="Condition's name">
        <input type="text" class="input-field condition-desc" placeholder ="Condition's description">
        <input type="button" class="add-condition" value="Add condition">
        <i class="fa-solid fa-check condition-check hidden"></i>
    </section>
    <section class=" admin-section add-new-category">
        <h2>Add a new category</h2>
        <input class="input-field new-category" type="text" placeholder="Categorie's name">
        <input type="button" class="add-category" value="Add category">
        <i class="fa-solid fa-check category-check hidden"></i>
    </section>
    <section class="admin-section add-new-size">
        <h2>Add a new size to a category</h2>
        <select class="category-select">
            <option class="default-option" value='' selected >Please select an option</option>
        </select>
        <select class="sizes-available">
            <option class="default-option" value='' selected disabled>Available sizes</option>
        </select>
        <input type="text" class="input-field new-size" placeholder="New size">
        <input type="button" class="add-size" value="Add size">
        <i class="fa-solid fa-check size-check hidden"></i>
    </section>
    
    <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">

</main>

<?php
drawFooter(); 
?>
