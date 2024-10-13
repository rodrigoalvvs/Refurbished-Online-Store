function validateEmail(email){
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email);
}
function validateZIP(zip){
    let postalCodePattern = /^[0-9]{4}-[0-9]{3}$/;
    return postalCodePattern.test(zip);
}
function validatePhoneNumber(phone){
    let phonePattern = /^[0-9]{9}$/;
    return phonePattern.test(phone);
}

// Password strength constraints    

function checkPasswordStrength(password, strengthMeter) {
    if(password == ""){
        strengthMeter.innerHTML = "";
        return false;
    }
    let strength = 0;
    let text = "";
    let valid = false;
    const combinations = [
        { regex: /.{8}/, value: 0 },
        { regex: /[A-Z]/, value: 1 },
        { regex: /[a-z]/, value: 2 },
        { regex: /[0-9]/, value: 3 },
        { regex: /[^A-Za-z0-9]/, value: 4 },
    ];

    combinations.forEach((combination) => {
        const isValid = combination.regex.test(password);
        if(isValid) strength++;
    });

    if (strength <= 1 && strength >= 0) {
        text = "<span class='weak' style='opacity: 50%; color:red;'>Password is Weak</span>";
    } else if (strength >= 2 && strength <= 3) {
        text = "<span class='medium' style='opacity: 50%; color:orange;'>Password is Medium</span>";
    } else {
        text = "<span class='strong' style='opacity: 50%; color:green;'>Password is Strong</span>";
        valid = true;
    }
    if(strengthMeter) strengthMeter.innerHTML = text;
    return valid;
}
function validateCreditCard(cardNumber, expirationDate, cvv) {
    // Remove chars that are not numbers
    cardNumber = cardNumber.replace(/\D/g, '');

    // Check if the card number is valid
    if (!isValidCreditCardNumber(cardNumber)) {
        return false;
    }

    // Check if the expiration date is valid
    if (!isValidExpirationDate(expirationDate)) {
        return false;
    }

    // Check if the CVV is valid
    if (!isValidCVV(cvv)) {
        return false;
    }

    return true;
}

function isValidCreditCardNumber(cardNumber) {
    return /^\d{16}$/.test(cardNumber);
}

function isValidExpirationDate(expirationDate) {
    let parts = expirationDate.split("/");
    const expMonth = parseInt(parts[0], 10);
    const expYear = parseInt(parts[1], 10) + 2000;

    const expDate = new Date(expYear, expMonth - 1, 1);
    expDate.setMonth(expDate.getMonth() + 1);
    expDate.setDate(expDate.getDate() - 1);

    let currentDate = new Date();
    return expDate > currentDate;
}

function isValidCVV(cvv) {
    return /^\d{3,4}$/.test(cvv);
}

function validateCheckout(info, name, email, address, city, zip, cardNumber, expiration, cvv, cardholder) {
    if (name.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please enter your name!</span>";
        return false;
    }
    if (email.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please enter your email!</span>";
        return false;
    }
    if (address.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please enter your delivery address!</span>";
        return false;
    }
    if (city.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please enter your city!</span>";
        return false;
    }
    if (zip.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please enter your zip code!</span>";
        return false;
    }
    if (cardNumber.trim() === "" || expiration.trim() === "" || cvv.trim() === "" || cardholder.trim() === "") {
        info.innerHTML = "<span style='color:red;'>Please fill in all credit card details!</span>";
        return false;
    }
    if (!validateEmail(email)) {
        info.innerHTML = "<span style='color:red;'>Email is not valid!</span>";
        return false;
    }

    if (!validateCreditCard(cardNumber, expiration, cvv)) {
        info.innerHTML = "<span style='color:red;'>Card is not valid!</span>";
        return false;
    }

    // All checks passed, return true
    return true;
}
