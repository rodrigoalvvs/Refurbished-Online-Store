<?php 


session_start();
include_once("../templates/common_tmp.php");
include_once("../templates/navbar_tmp.php");
include_once("../templates/account_tmp.php");

drawHead("account");
drawNavbar();

if(!isset($_SESSION['userid'])) {
    header("Location: authentication.php");
    exit();
}

?>
<main>
<?php 
drawAccountMenu();
?>

    <section class="account-container">
        <header>
            <h3>Account Settings</h3>
            <span class="fa-solid fa-pen" id="account-edit"></span>
        </header>
        <div class="user-info">
            <form enctype="multipart/form-data" method="POST">
                
                <span>Profile Picture</span>
                <div class="profile-picture-div" id="profile-picture-div">
                    <img id="profile-picture" src ="../database/uploads/ProfilePictures/default-profile-picture.jpg" alt="Profile Picture">
                    
                    <label for="user-avatar" id="avatar-label">Edit..</label>

                    <input name = "user-avatar" type="file" class="user-avatar" id="user-avatar" accept="image/*">
                </div>
                
                <label for="username">Name</label>
                <input name = "username" type="text" class="account-change user-name " id="user-name" placeholder = "Username" readonly>
                
                <label for="username">Email</label>
                <input name = "email" type="email" class="account-change user-email" id="user-email" placeholder = "Email" readonly>

                <label for="address">Address</label>
                <input name = "address" type="text" class=" account-change user-address" id= "user-address" placeholder="Address" readonly>
                
                <label for="postalCode"> Postal code</label>
                <input class="account-change" name = "postalCode" id="zip" type="text" pattern="[0-9]{4}-[0-9]{3}" placeholder="Postal Code" readonly>
                
                <label for="phoneNumber"> Phone Number</label>
                <input  class="account-change" type="tel" id="phone" name="phoneNumber" pattern="[0-9]{9}" placeholder="Phone Number" readonly>

                <input type="hidden" class="csrf" name="csrf" value="<?=$_SESSION['csrf']?>">

                <div class="changes-status"></div>
                <input type="button" id="submit-info" value="Save Changes">
            </form>
        </div>
        
    </section>  
</main>

