<?php
declare(strict_types=1);
?>

<?php function drawAccountMenu(): void
{
    ?>
    <aside class="account-navigator">
        <ul>
            <li>
                <a href="account.php" class="icon fa-regular fa-user "></a>
                <a href="account.php">Account</a>
            </li>
            <li>
                <a href="products.php" class="icon fa-solid fa-euro-sign "></a>
                <a href="products.php">My Products</a>
            </li>
            <li>
                <a href="privacy.php" class="icon fa-solid fa-shield-halved "></a>
                <a href="privacy.php">Privacy and security</a>
            </li>
            <li>
                <a href="order_history.php" class="icon fa-solid fa-receipt"></a>
                <a href="order_history.php">Order History</a>
            </li>
        </ul>

    </aside>
<?php } ?>