<?php 

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_SESSION["userid"])){
        unset($_SESSION["userid"]);
        if(isset($_SESSION["csrf"])){
            unset($_SESSION["csrf"]);
        }
        echo json_encode(array("status"=> "success","message"=> "Logout succesfull!"));
        die();
    }
    
    echo json_encode(array("status"=> "error", "message" => "Couldn't logout!"));
}

?>