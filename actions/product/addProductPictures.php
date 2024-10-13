<?php
session_start();
include_once ($_SERVER["DOCUMENT_ROOT"] . "/includes/macros.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $header = getallheaders();
    if (!checkCSRFToken($header)) {
        echo json_encode(array("status" => "error", "message" => "Invalid request origin!"));
        return;
    }
    uploadImages();
}

function uploadImages(): void
{
    $target_dir = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/temp/" . $_SESSION["userid"];

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $savedImages = createFolder($target_dir);
    $file_count = count($savedImages);

    if (isset($_POST["image-len"])) {
        $allowed = array("png", "jpg", "jpeg");
        $status = "success";
        $message = "Uploaded files succesfully";

        if ($file_count >= 5) {
            // Image max for each product should be 5
            echo json_encode(array("status" => "error", "message" => "Each product must have a maximum of 5 photos!", "images" => $savedImages, "uid" => $_SESSION["userid"]));
            return;
        }

        for ($i = 0; ($i < $_POST["image-len"]) && ($i < 5); $i++) {
            // $i is the index of the images to add

            $imageName = "image-" . $i;
            $originalFileName = $_FILES[$imageName]["name"];
            $fileType = pathinfo($_FILES[$imageName]["name"], PATHINFO_EXTENSION);


            $newFileName = ($i + $file_count) . "." . pathinfo($_FILES[$imageName]["name"], PATHINFO_EXTENSION);
            $targetFile = $target_dir . "/" . $newFileName;

            if (!file_exists($_FILES[$imageName]["tmp_name"])) {
                $status = "error";
                $message = "Couldn't upload " . $originalFileName;
            } else if (!in_array($fileType, $allowed)) {
                $status = "error";
                $message = "Formats allowed: [jpg, gif, png, webp]";
            } else if (!move_uploaded_file($_FILES[$imageName]["tmp_name"], $targetFile)) {
                $status = "error";
                $message = "Failed to upload file: " . $originalFileName;
            } else {
                $savedImages = createFolder($target_dir);
            }

        }
    }

    echo json_encode(array("status" => $status, "message" => $message, "images" => $savedImages, "uid" => $_SESSION["userid"]));
}

function createFolder($newFolder): array
{
    if (!is_dir($newFolder))
        mkdir($newFolder);
    return array_diff(scandir($newFolder), array('.', '..'));
}
?>