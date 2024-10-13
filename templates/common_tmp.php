<?php
declare(strict_types=1); 

?>

<?php function drawHead($page_name): void{
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">


        <link rel = "stylesheet" href ="../assets/css/global.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.2/css/all.css">
        
        <link rel="stylesheet" href="../assets/css/<?php echo $page_name ?>.css">
        <link rel="stylesheet" href = "../assets/css/navbar.css">
        <link rel="stylesheet" href = "../assets/css/account_tmp.css">
        <link rel="stylesheet" href = "../assets/css/products_tmp.css">
        <link rel="stylesheet" href = "../assets/css/footer.css">
        <link rel="stylesheet" href = "../assets/css/card.css">


        <script src="../assets/js/AJAXClient.js" defer></script>
        <script src="../assets/js/reusable.js" defer></script>
        <script src="../assets/js/validator.js" defer></script>
        <script src="../assets/js/<?php echo $page_name ?>.js" defer></script>
        <script src="../assets/js/navbar.js" defer></script>
        
        <title>Refurbished</title>
    </head>
<?php } ?>