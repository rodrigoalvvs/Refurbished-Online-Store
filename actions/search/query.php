<?php 
session_start();

include_once("../../includes/Database.php");
$db = Database::getInstance();  

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["query"])){
    echo $db->getProductSearch($_POST["query"], $_SESSION["userid"]);  
}

?>