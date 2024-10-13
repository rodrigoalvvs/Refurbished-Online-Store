<?php

include_once("../templates/common_tmp.php");
include_once("../templates/navbar_tmp.php");
include_once("../templates/account_tmp.php");

drawHead("privacy");
drawNavbar();


session_start();
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
                    <h3>Privacy Settings</h3>
                    <span class="fa-solid fa-pen" id="account-edit"></span>
                </header>
            <div class="user-info">
                <form enctype="multipart/form-data" method="POST" id="password-form">
                    <label for="old-password">Old Password</label>
                    <input name="old-password" type="password" class="account-change user-old-password" id="user-old-password" placeholder="Old Password" readonly>

                    <label for="password">New Password</label>
                    <input name="password" type="password" class="account-change user-password" id="user-password" placeholder="New Password" readonly>

                    <label for="confirm-password">Confirm Password</label>
                    <input name="confirm-password" type="password" class="account-change user-confirm-password" id="user-confirm-password" placeholder="Confirm New Password" readonly>
                    <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">

                    <div id="password-strength"></div>

                    <div class="changes-status"></div>
                    <input type="button" id="submit-password" value="Change Password">
                </form>
            </div>
        </section>

</main>

