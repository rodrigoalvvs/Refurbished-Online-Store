function initPage(){

    document.getElementById("account-edit").addEventListener("click", toggleEdit);

    
    document.getElementById("submit-info").addEventListener("click", handleSubmission);

    document.getElementById("user-avatar").addEventListener("change", handlePictureSubmission);
    
    styleProfilePicture();
    retrieveUsersInfo();
}

function styleProfilePicture(){
    
    const parent = document.getElementById("profile-picture-div");
    const pp = document.getElementById("profile-picture");
    const label = document.getElementById("avatar-label");

    parent.addEventListener("mouseenter", () => {
        pp.style.filter = "brightness(0.2)";
        label.classList.add("active");
    });

    parent.addEventListener("mouseleave", () => {
        pp.style.filter = "brightness(1)";
        label.classList.remove("active");
    });
}

async function retrieveUsersInfo(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/account/retrieveUserInfo.php", "POST");
    const info = JSON.parse(jsonInfo.data);
    for (const key in info) {
        if (info.hasOwnProperty(key)) {
            const value = info[key];

            if(key == "profile-picture"){
                const path = value === "default-profile-picture.jpg" ? "../../assets/img/" : "../../database/uploads/ProfilePictures/";

                document.getElementById("profile-picture-navbar").src = path + value;
                document.getElementById("profile-picture").src = path + value;
            }else{
                const elements = document.getElementsByName(key);
                if (elements.length > 0) {
                    elements[0].placeholder = value;
                }
            }
        }
    }   
}

function toggleEdit(){
    document.querySelectorAll("input").forEach((node) =>{
        node.toggleAttribute("readOnly");
    });
}

async function handlePictureSubmission() {
    const info = document.querySelectorAll(".changes-status")[0];
    const csrf = document.querySelector(".csrf").value;
    const fileInput = document.getElementById("user-avatar");
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append("user-avatar", file);

    const jsonInfo = await ajaxClient.fetchData("../../actions/account/changeProfilePicture.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    
    ajaxClient.displayJSONToUser(jsonInfo, info);
    retrieveUsersInfo();
}

function validateForm(){
    const email = document.getElementById("user-email").value;
    const postalCode = document.getElementById("zip").value;
    const phone = document.getElementById("phone").value;

    if(email != "" && !validateEmail(email)){
        return {"status" : "error", "message" : "Invalid email!"};
    }
    if(postalCode != "" && !validateZIP(postalCode)){
        return {"status" : "error", "message" : "Invalid postal code (XXXX-XXX)!"};
    }
    if(phone != "" && !validatePhoneNumber(phone)){
        return {"status" : "error", "message" : "Invalid phone number (9 digits)"}
    }
    return {"status" : "success", "message" : ""};
}

async function handleSubmission(){

    const formData = ajaxClient.encodeFormForAjax(document.querySelectorAll(".account-change"));
    if(formData == "") return;
    const info = document.querySelectorAll(".changes-status")[0];

    const valid = validateForm();
    if(valid.status == "error"){
        info.innerHTML = "<span style='color:red'>" + valid.message + "</span>";
        return;
    }
    const csrf = document.querySelector(".csrf").value;

    const jsonInfo = await ajaxClient.fetchData("../../actions/account/changeAccount.php", "POST", formData, 
    {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-Token' : csrf
    });
    ajaxClient.displayJSONToUser(jsonInfo, info);
}




window.addEventListener("load", initPage);
