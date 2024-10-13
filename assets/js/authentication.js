function initPage(){
    animateSlider();
    const loginBtn = document.getElementById("login-submit");
    const registerBtn = document.getElementById("register-submit");
    const pwd_register = document.getElementById("password-register");

    pwd_register.addEventListener("input", (item) =>{
        checkPasswordStrength(pwd_register.value, document.getElementById("password-register-info"));
    });

    loginBtn.addEventListener("click", ()=>{sendLoginData()});
    registerBtn.addEventListener("click", ()=>{sendRegisterData()});

    document.querySelectorAll(".login-input").forEach((input) => {
        input.addEventListener("keypress", function(e){
            if(e.key === 'Enter'){
                sendLoginData();
            }
        });
    });
    document.querySelectorAll(".register-input").forEach((input) => {
        input.addEventListener("keypress", function(e){
            if(e.key === 'Enter'){
                sendRegisterData();
            }
        });
    });
}

function animateSlider() {
    const buttonRegister = document.getElementById("signup-button");
    const buttonLogin = document.getElementById("signin-button");
    const parent = document.getElementsByClassName("authentication-container")[0];


    buttonLogin.addEventListener("click", function () {
        // If a user clicks this button we want to shift the slider to the right
        moveSlider(parent.offsetWidth, 1);
        
    });

    buttonRegister.addEventListener("click", function () {
        // If a user clicks this button we want to shift the slider to the left
        moveSlider(parent.offsetWidth, 0);
        
    });
}



function toggleClass(className, state0, state1) {
    const elements = document.getElementsByClassName(className);

    for (let i = 0; i < elements.length; i++) {
        if (elements[i].classList.contains(state0)) {
            elements[i].classList.remove(state0);
            elements[i].classList.add(state1);
        } else {
            elements[i].classList.remove(state1);
            elements[i].classList.add(state0);
        }
    }
}

function moveSlider(width, direction) {
    toggleClass("auth-info", "active", "inactive");
    toggleClass("auth-section", "active-section", "inactive-section");
    toggleClass("slider", "slider-moved-left", "slider-moved-right");
}


function validateLoginForm() {
    sendLoginData();
}


function validateRegisterForm() {
    const email = document.getElementById("email-register").value;
    const password = document.getElementById("password-register").value;
    const confirm_password = document.getElementById("password-register-confirm").value;
    const info = document.getElementById("password-register-info");
    
    let valid = checkPasswordStrength(password, info) && validateEmail(email);
    if (password !== confirm_password) {
        valid = false;
        info.innerHTML = "<span class='strong' style='opacity: 50%'>Passwords don't match!</span>";
    }

    if (!valid) return false;
    return true;
}

async function sendLoginData() {
    const info = document.querySelector(".login-status");
    const email = document.getElementById("email-login").value;
    const password = document.getElementById("password-login").value;
    const data = "email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password);

    
    const jsonInfo = await ajaxClient.fetchData("../../actions/authentication/login.php", "POST", data,{"Content-Type": "application/x-www-form-urlencoded"});
    ajaxClient.displayJSONToUser(jsonInfo, info);

    if(jsonInfo.status=="success"){
        window.location.href="mainpage.php";
    }
}


async function sendRegisterData(){
    const info = document.querySelector(".register-status");
    const username = document.getElementById("name-register").value;
    const email = document.getElementById("email-register").value;
    const password = document.getElementById("password-register").value;
    if(!validateRegisterForm()){
        return;
    }
    const data = "username=" + encodeURIComponent(username) + "&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password);
    
    const jsonInfo = await ajaxClient.fetchData("../../actions/authentication/register.php", "POST", data,{"Content-Type": "application/x-www-form-urlencoded"});

    ajaxClient.displayJSONToUser(jsonInfo, info);
    if(jsonInfo.status=="success"){
        window.location.href="account.php";
    }
    
}

window.addEventListener("load", initPage);
