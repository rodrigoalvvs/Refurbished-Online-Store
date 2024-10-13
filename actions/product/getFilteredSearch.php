<?php 
session_start();
include_once("../../includes/Database.php");
$db = Database::getInstance();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $category = $_POST["category"];
        $size = $_POST["size"];
        $min = $_POST["min"];
        $max = $_POST["max"];  
        $condition = $_POST["condition"];
        $page = $_POST["page"];
        
        $userId = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;
        if($category == "") $category = null;
        if($size == "") $size = null;
        if($min == "" || $min == null) $min = PHP_INT_MIN;
        if($max == "" || $max == null) $max = PHP_INT_MAX;
        if($condition == "") $condition = null;

        echo $db->getProducts($category, $size, $min, $max, $condition, $page, $userId);    
    }
?>