

function initPage() {
    getCategories();

    document.querySelector(".elevate-user-bttn").addEventListener("click", elevateUser);
    document.querySelectorAll(".input-field").forEach((inputField) => {
        inputField.addEventListener("change", () => {
            inputField.classList.remove("invalid-input");
        });
    });

    document.querySelector(".add-condition").addEventListener("click", addCondition);
    document.querySelector(".add-category").addEventListener("click", addCategory);
    document.querySelector(".add-size").addEventListener("click", addSize);

}

async function elevateUser() {
    const email = document.querySelector(".email-input");
    const csrf = document.querySelector(".csrf").value;

    if (!validateEmail(email.value)) {
        email.classList.add("invalid-input")
        return;
    }

    // email is valid
    const formData = new FormData();
    formData.append("userEmail", email.value);
    const jsonInfo = await ajaxClient.fetchData("../../actions/admin/elevateUserToAdmin.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    if (jsonInfo["status"] === "error") {
        alert(jsonInfo["message"]);
        return;
    }
    // user was elevated
    document.querySelector(".elevate-check").classList.toggle("hidden");
    setTimeout(() => {
        document.querySelector(".elevate-check").classList.toggle("hidden");
    }, 3000);
    email.value = "";
}


async function addCondition() {
    const conditionName = document.querySelector(".condition-name");
    const conditionDesc = document.querySelector(".condition-desc");
    if (conditionName.value === "") {
        conditionName.classList.add("invalid-input");
        return;
    }
    if (conditionDesc.value === "") { 
        conditionDesc.classList.add("invalid-input"); 
        return;
    }


    const formData = new FormData();
    formData.append("conditionName", conditionName.value);
    formData.append("conditionDescription", conditionDesc.value);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/admin/addProductCondition.php", "POST", formData,{
        "X-CSRF-Token" : csrf
    });
    
    if (jsonInfo["status"] === "error") {
        alert(jsonInfo["message"]);
        return;
    }
    // user was elevated
    document.querySelector(".condition-check").classList.toggle("hidden");
    setTimeout(() => {
        document.querySelector(".condition-check").classList.toggle("hidden");
    }, 3000);

    conditionDesc.value = "";
    conditionName.value = "";
}

async function addCategory() {
    const categoryName = document.querySelector(".new-category");
    if(categoryName.value === ""){
        categoryName.classList.add("invalid-input");
        return;
    }
    const formData = new FormData();
    formData.append("categoryName", categoryName.value);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/admin/addProductCategory.php", "POST", formData,{
        "X-CSRF-Token" : csrf
    });
    if (jsonInfo["status"] === "error") {
        alert(jsonInfo["message"]);
        return;
    }
    document.querySelector(".category-check").classList.toggle("hidden");
    setTimeout(() => {
        document.querySelector(".category-check").classList.toggle("hidden");
    }, 3000);
    categoryName.value = "";
}

async function addSize() {
    const size = document.querySelector(".new-size");
    const categorySelect = document.querySelector(".category-select");
    if(categorySelect.value === ""){
        categorySelect.classList.add("invalid-input");
        return;
    }
    if(size.value === ""){
        size.classList.add("invalid-input");
        return;
    }

    const formData = new FormData();
    formData.append("newSize", size.value);
    formData.append("categoryId", categorySelect.value);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/admin/addSizeToCategory.php", "POST", formData,{
        "X-CSRF-Token" : csrf
    });
    if (jsonInfo["status"] === "error") {
        alert(jsonInfo["message"]);
        return;
    }
    document.querySelector(".size-check").classList.toggle("hidden");
    setTimeout(() => {
        document.querySelector(".size-check").classList.toggle("hidden");
    }, 3000);
    size.value = "";
}




function updateSizes(categories) {
    const categoryId = document.querySelector(".category-select").value;
    const sizes_input = document.querySelector(".sizes-available");
    const sizes = categories[categoryId]["sizes"].split(",");
    sizes_input.innerHTML = "<option disabled selected>Available sizes</option>";
    if(sizes.length === 0) return;
    sizes.forEach((size) => {
        sizes_input.innerHTML += `
            <option value=${size} disabled>${size}</option>
        `;
    })
}

async function getCategories() {
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getCategories.php", "POST", "", {});
    if (jsonInfo["status"] === "success") {
        const categories = jsonInfo["categories"];
        const categoriesMap = [];
        categories.forEach((category) => {
            categoriesMap[category["categoryId"]] = category;
        });

        displayCategories(categoriesMap);
    }
}
function displayCategories(categories) {
    const category_select = document.querySelector(".category-select");
    categories.forEach((category) => {
        category_select.innerHTML +=
            `
            <option value=${category["categoryId"]}>${category["categoryName"]}</option>
        `
            ;
    });
    category_select.addEventListener("change", () => {
        updateSizes(categories);
    }
    );
}


window.addEventListener("load", initPage);