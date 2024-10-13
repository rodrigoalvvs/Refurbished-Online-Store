<?php

include_once ("../templates/common_tmp.php");
include_once ("../templates/navbar_tmp.php");
include_once ("../templates/footer_tmp.php");

drawHead("messages");
drawNavbar();


?>

<main>

    <aside class="messages-sidebar messages-hidden">
    </aside>
    <span class="push-sidebar sidebar-hidden"><i class="fa-solid fa-arrow-right push-icon"></i></span>
    <div class="message-body-container">
        <div class="messages-div">
        </div>
        <div class="send-container hidden">
            <input type="text" class="message-box" placeholder="Enter message">
            <i class="fa-regular fa-paper-plane send-message-icon"></i>
        </div>
    </div>
    <input type="hidden" name="csrf" class="csrf" value="<?=$_SESSION['csrf']?>">
</main>


<?php

drawFooter();
?>