<?php 

include_once("../../includes/Database.php");
session_start();



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!isset($_SESSION["userid"])){
        echo json_encode(array("status" => "error", "message" => "User is not logged in!"));
        exit();
    }
    
    $userPP = retrieveUserPhoto();
    $usersInfo = retrieveUserInfo();
    $result = json_encode(array_merge_recursive($userPP, $usersInfo));
    echo json_encode(array("status" => "success", "data" => $result));
}


function retrieveUserPhoto(){
    $db = Database::getInstance();
    $pp_path = "../../database/uploads/ProfilePictures/";
    $files = glob($pp_path . $_SESSION["userid"] . ".*");

    if(!isset($_SESSION["userid"]) || empty($files)){
        return array("profile-picture" => "default-profile-picture.jpg");
    }else{
        $fileExtension = pathinfo($files[0], PATHINFO_EXTENSION);
        $message = $_SESSION["userid"] . "." . $fileExtension;
        return array("profile-picture" => $message);
    }
}

function retrieveUserinfo(){
    $db = Database::getInstance();
    return $db->getUser($_SESSION["userid"]);
}

?>