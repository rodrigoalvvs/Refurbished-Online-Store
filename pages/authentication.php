<?php 

require_once("../templates/common_tmp.php");

drawHead("authentication"); 

?>  


<body>
    <main>
        <section class ="authentication-container">
            <div class = "auth-section login active-section" id="login"> 
                <header>
                    <h2>Welcome back,</h2>
                    <h3>dear friend.</h3>
                </header>
                <form class="login-form">
                    <input class="input-auth login-input" type="email" id="email-login" placeholder="Email" required>
                    <input class="input-auth  login-input" type="password" id="password-login" placeholder="Password" required>
                    <div class="login-status"></div>
                    <input type="button" value="Continue" id="login-submit">
                </form>
            </div>
            <div class = "auth-section register inactive-section" id = "register">
                <header>
                    <h2>Start your</h2>
                    <h3>journey here.</h3>
                </header>
                <form class="register-form">
                    <input  class="input-auth register-input" type ="text" id = "name-register" placeholder="Name" required>
                    
                    <input class="register-input" type="email" id="email-register"  placeholder="Email" required>
                    
                    <input class="register-input" type="password" id="password-register" placeholder="Password"  required>
                    
                    <input class="register-input" type="password" id="password-register-confirm" placeholder="Confirm password" required>
                    <div id="password-register-info" class="register-status"></div>
                    
                    <input type="button" value="Continue" id="register-submit">
                </form>
            </div>
            <aside class="slider slider-moved-right" id="slider">
                <div class ="auth-info active" id="login-active">
                    <h2>Join us today and unlock a</h2> 
                    <h3>world of possibilities!</h3>
                    <button  id="signup-button">I want to create an account</button>
                </div>
                <div class ="auth-info inactive" id="register-active">
                    <h2>Have we met before,</h2> 
                    <h3>old friend?</h3>
                    <button  id="signin-button">I want to sign in</button>
                </div>
            </aside>
        </section>
    </main>
</body>