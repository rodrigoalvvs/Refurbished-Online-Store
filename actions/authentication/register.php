<?php 

include_once("../../includes/Database.php");


$database = Database::getInstance();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        echo $database->registerUser($username, $email, $password);
    }
}
?>