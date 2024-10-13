<?php 

include_once("../templates/common_tmp.php");
include_once("../templates/navbar_tmp.php");
include_once("../templates/footer_tmp.php");

drawHead("mainpage");
drawNavbar();
?>

<main>
    <header class="landing-header">
        <div class="landing-left-container">
            <h1>MAKE YOUR LOOK MORE PERFECT</h1>
            <h3>Upgrade Your Wardrobe, Upgrade Your Life</h3>
            <a href="store.php">Shop now</a>
        </div>
        <div class="landing-right-container">
            <img src="../assets/img/landing.png">
        </div>

    </header>
    <section id="about">
    <div class="container">
        <h2>About Refurbished</h2>
        <p>Welcome to <strong>Refurbished</strong>, the premier marketplace for buying and selling second-hand products. Our platform connects users who want to sell their gently used items with those looking for great deals on quality products. Whether you’re decluttering your home or searching for a specific item, Refurbished makes the process simple, secure, and efficient.</p>
        <h3>Our Mission</h3>
        <p>At Refurbished, we believe in sustainability and the power of reusing and recycling. Our mission is to reduce waste by giving pre-owned products a second life. We strive to create a community where users can find value in items that others no longer need, promoting a circular economy and minimizing environmental impact.</p>
        <h3>How It Works</h3>
        <ol>
            <li><strong>Sign Up:</strong> Create an account to start buying and selling.</li>
            <li><strong>List Your Item:</strong> Post your second-hand products with detailed descriptions and photos.</li>
            <li><strong>Browse Listings:</strong> Search for items you need at unbeatable prices.</li>
            <li><strong>Connect and Trade:</strong> Communicate with sellers/buyers to finalize the deal.</li>
            <li><strong>Enjoy Your Purchase:</strong> Receive your product and give it a new home.</li>
        </ol>
        <p>Join us today and be part of a community that values sustainability and quality. Together, we can make a difference one product at a time.</p>
    </div>
</section>

</main>

<?php
drawFooter();
?>