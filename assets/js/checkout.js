function initPage() {

    document.querySelector(".checkout-button").addEventListener("click", checkout);
}
async function checkout() {
    const formData = new FormData();
    // get all info from the page
    const info = document.querySelector(".checkout-info");
    
    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;
    const email = document.getElementById("email").value;
    const address = document.getElementById("address").value;
    const city = document.getElementById("city").value;
    const zip = document.getElementById("zip").value;

    // Append name
    const name = firstName + " " + lastName;
    formData.append("name", name);

    // Append email, address, city, and zip
    formData.append("email", email);
    formData.append("address", address);
    formData.append("city", city);
    formData.append("zip", zip);

    // Append card info
    const cardNumber = document.querySelector("#cardNumber").value;
    const expirationDate = document.querySelector("#expirationDate").value;
    const cvv = document.querySelector("#cvv").value;
    const cardholderName = document.querySelector("#cardholderName").value;

    if (!validateCheckout(info, name, email, address, city, zip, cardNumber, expirationDate, cvv, cardholderName)){
        return;
    }


    // Append card information to formData
    formData.append("cardNumber", cardNumber);
    formData.append("cardholderName", cardholderName);

    // checkout is valid
    const csrf = document.querySelector(".csrf").value;
    
    const jsonInfo = await ajaxClient.fetchData("../../actions/checkout/action_checkout.php", "POST",formData, {
        "X-CSRF-Token" : csrf
    });

    if(jsonInfo["status"] === "error"){
        info.innerHTML = "<span style='color:red;'>" + jsonInfo["message"] + "</span>";
        return;
    }
    info.innerHTML = "<span style='color:green;'>" + jsonInfo["message"] + "</span>";
    setTimeout(() => {
        window.location.href = "mainpage.php";
    }, "3000");
}


window.addEventListener("load", initPage);