function initPage() {
    document.getElementById("account-edit").addEventListener("click", toggleEdit);
    document.getElementById("submit-password").addEventListener("click", handleSubmission);
    retrieveUsersInfo();
}

let userInfo = null;

async function retrieveUsersInfo() {
    const jsonInfo = await ajaxClient.fetchData("../../actions/account/retrieveUserInfo.php", "POST");
    userInfo = JSON.parse(jsonInfo.data);
    for (const key in userInfo) {
        if (userInfo.hasOwnProperty(key)) {
            const value = userInfo[key];
            const elements = document.getElementsByName(key);
            if (elements.length > 0) {
                elements[0].placeholder = value;
            }
        }
    }
}

function toggleEdit() {
    document.querySelectorAll("input").forEach((node) => {
        node.toggleAttribute("readOnly");
    });
}


function validateForm() {
    const password = document.getElementById("user-password").value;
    const confirmPassword = document.getElementById("user-confirm-password").value;

    const info = document.querySelector(".changes-status");
    let valid = checkPasswordStrength(password, info);
    if (password !== confirmPassword) {
        info.innerHTML = "<span class='strong' style='opacity: 50%'>Passwords don't match!</span>";
        return false;
    }
    if (!valid) {
        info.innerHTML = "<span class='strong' style='opacity: 50%'>New password isn't strong enough!</span>";
        return false;
    }
    return true;
}

async function handleSubmission() {
    const oldPassword = document.getElementById("user-old-password").value;
    const newPassword = document.getElementById("user-password").value;

    const formData = "oldPassword=" + encodeURIComponent(oldPassword) + "&newPassword=" + encodeURIComponent(newPassword);

    const valid = validateForm();
    if (formData == "" || !valid) return;

    const info = document.querySelectorAll(".changes-status")[0];
    const token = document.querySelector(".csrf").value;

    const jsonInfo = await ajaxClient.fetchData("../../actions/account/changePassword.php", "POST", formData,
        {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token' : token
        });

    ajaxClient.displayJSONToUser(jsonInfo, info);
}

window.addEventListener("load", initPage);

