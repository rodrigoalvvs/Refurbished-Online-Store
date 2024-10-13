const ajaxClient = AJAXClient.getInstance();

function initNavBar(){
    const dropdown = document.getElementById("dropdown-button");
    if(dropdown != null){
        dropdown.addEventListener("click", toggleDropdown);
        checkUserLoggedIn();
    }
    document.querySelectorAll(".logout-bttn").forEach((logoutBttn) => {
        logoutBttn.addEventListener("click", logout);
        toggleDropdown();
    });
    document.querySelector(".search-input").addEventListener("keypress", (event) => {
        if(event.key === "Enter"){
            submitQuery();
        }
    });
}

window.addEventListener("load", initNavBar);


function toggleDropdown(){
    document.getElementById("dropdown-menu").classList.toggle("show");
    toggleDropdownIcon(); 
}

function toggleDropdownIcon(){
    const dropdownIcon = document.getElementById("dropdown-icon");
    dropdownIcon.classList.toggle("rotate");
    dropdownIcon.classList.toggle("fa-bars");
    dropdownIcon.classList.toggle("fa-xmark");

}


function loadUserProfilePicture(ppSrc){
    if(ppSrc === "default-profile-picture.jpg"){
        document.querySelectorAll(".profile-picture-navbar")[0].src = "../../assets/img/" + ppSrc;
    }else{
        document.querySelectorAll(".profile-picture-navbar")[0].src = "../../database/uploads/ProfilePictures/" + ppSrc;
    }
}

async function checkUserLoggedIn(){
    const userInfo = await ajaxClient.fetchData("../../actions/account/retrieveUserInfo.php", "POST", "");

    if(userInfo.status == "success"){
        const data = JSON.parse(userInfo.data);
        // user is logged in, show user panel and user profile picture
        document.getElementById("navbar").classList.remove("hidden");
        document.getElementById("navbar-guest").classList.add("hidden");
        loadUserProfilePicture(data["profile-picture"]);

    }
    else{
        document.getElementById("navbar").classList.add("hidden");
        document.getElementById("navbar-guest").classList.remove("hidden");
    }
}

async function logout(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/account/logout.php");
    if(jsonInfo["status"] === "success"){
        checkUserLoggedIn();
    }
}
function submitQuery(){
    const query = document.querySelector(".search-input").value;
    if(query === "") return;
    
    window.location.href = "search.php?query=" + encodeURIComponent(query);
}



