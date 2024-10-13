<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/Database.php");
$database = Database::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    echo $database->getAllCategories();
}

?>