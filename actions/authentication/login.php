<?php 

include_once("../../includes/Database.php");
session_start();
$database = Database::getInstance();


if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["email"]) && isset($_POST["password"])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $response = $database->loginUser($email, $password);
        echo $response;
    }
}
?>