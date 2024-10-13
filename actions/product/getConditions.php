<?php 

include_once("../../includes/Database.php");
$database = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    echo $database->getAllConditions();
}

?>