<?php
define('DATABASE_DIR', __DIR__ . '/../database/users.db');

function getProductPhotos($productId): array
{
    $path = $_SERVER["DOCUMENT_ROOT"] . "/database/uploads/product/" . $productId . "/";
    $files = scandir($path);
    $files = array_diff($files, array('.', '..'));
    return array_values($files);
}

function generate_random_token()
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}
function checkCSRFToken($headers): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($headers['X-CSRF-Token']) && isset($_SESSION['csrf'])) {
        return hash_equals($headers['X-CSRF-Token'], $_SESSION['csrf']);
    }
    return false;
}

?>