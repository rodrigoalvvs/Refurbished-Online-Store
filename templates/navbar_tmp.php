<?php
declare(strict_types=1);
session_start();
include_once ("../includes/Database.php");


?>

<?php function drawNavbar(): void
{
    $db = Database::getInstance();
    ?>
    <nav class="navbar-container">
        <div class="left">
            <a href="mainpage.php">
                <img class="navbar-logo" src="../assets/img/logo.svg" alt="refurbished-logo">
            </a>
        </div>
        <div class="navbar-search-container">
            <input class="search-input" type="text" placeholder="Search for an item">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        <div class="right">
            <ul class="navbar hidden" id="navbar">
                <li><a href="messages.php"><i class="fa-solid fa-inbox"></i></a></li>
                <li><a href="store.php"><i class="fa-solid fa-store"></i></a></li>

                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <?php
                if ($db->isUserAdmin($_SESSION["userid"])) {
                    ?>
                    <li><a href="admin.php"><i class="fa-solid fa-hammer"></i></a></li>
                    <?php
                }
                ?>


                <li>
                    <a href="account.php" class="profile-picture-a">
                        <img id="profile-picture-navbar" class="profile-picture-navbar"
                            href="../database/uploads/ProfilePictures/default-profile-picture.jpg">
                    </a>
                </li>

                <li><a class="logout-bttn"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></li>

                <li>
                    <button class="dropdown-button" id="dropdown-button"><i id="dropdown-icon"
                            class="fa-solid fa-bars "></i></button>
                </li>

            </ul>

            <ul class="navbar-guest hidden" id="navbar-guest">
                <li><a href="authentication.php">Sign in</a></li>
            </ul>
        </div>
        <div class="dropdown-menu" id="dropdown-menu">
            <ul>
                <li>
                    <a href="store.php">
                        <span>Store</span>
                        <span class="fa-solid fa-store"></span>
                    </a>
                </li>
                <li>
                    <a href="cart.php">
                        <span>Cart</span>
                        <span class="fa-solid fa-cart-shopping"></span>
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <span>Inbox</span>
                        <span class="fa-solid fa-inbox"></span>
                    </a>
                </li>
                <li>
                    <a href="account.php">
                        <span>Account</span>
                        <span class="fa-regular fa-user"></span>
                    </a>
                </li>
                <li>
                    <a class="logout-bttn">
                        <span>Logout</span>
                        <span class="fa-solid fa-arrow-right-from-bracket"></span>
                    </a>
                </li>
                <?php
                if ($db->isUserAdmin($_SESSION["userid"])) {
                    ?>
                    <li>
                        <a href="admin.php" class="admin-bttn">
                            <span>Admin</span>
                            <span class="fa-solid fa-hammer"></span>
                        </a>
                    </li>
                    <?php
                }
                ?>


            </ul>
        </div>
    </nav>
<?php } ?>