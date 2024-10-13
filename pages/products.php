<?php 

session_start();
include_once("../templates/common_tmp.php");
include_once("../templates/navbar_tmp.php");
include_once("../templates/account_tmp.php");
include_once("../templates/producttable_tmp.php");

drawHead("products");
drawNavbar();


if(!isset($_SESSION['userid'])) {
    header("Location: authentication.php");
    exit();
}

?>
<main>
    <?php
    drawAccountMenu();
    drawProductTable($_SESSION["userid"]);
    ?> 
</main>
