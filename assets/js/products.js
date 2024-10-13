const categories = {};
let filesUploaded = false;
let csrf;

function initPage() {
    csrf = document.querySelector(".csrf").value;
    retrieveTempPictures();    
    document.getElementById("imageUpload").addEventListener("change", uploadPictures);
    document.getElementById("product-category").addEventListener("change", updateSizes);
    document.querySelectorAll(".add-product")[0].addEventListener("click", addProduct);
    document.querySelectorAll(".newproduct-bttn")[0].addEventListener("click", () =>{
        document.querySelectorAll(".new-product-form")[0].classList.toggle("hidden");
    });
    getCategories();
    getConditions();
    getUserProducts();

}
async function destructPage(){
    const images = document.querySelectorAll(".temp-image");
    const promises = [];

    images.forEach((image) => {
        const id = image.id;
        promises.push(removeImageWithId(id));
    });

    await Promise.all(promises);
}

function styleImages() {
    const mainImage = document.querySelectorAll(".main-image")[0];
    const images = document.querySelectorAll(".temp-image");
    let prevSelected = 0;

    images.forEach((image, index) =>{
        image.addEventListener("click", () => {
            mainImage.src = image.src;
            
            images[prevSelected].style.border = "0";
            image.style.border = "2px solid #0b7189";
            prevSelected = index;
        });
    })
}

async function getConditions(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getConditions.php", "POST", "", {});
    const conditions = [];
    if(jsonInfo["status"] === "success"){
        const conditions_ = jsonInfo["conditions"];

        conditions_.forEach((condition) =>{
            conditions[condition["conditionId"]] = condition;
        });
    }
    const condition_input = document.getElementById("product-condition");
    for(let conditionId in conditions){
        let newCondition = document.createElement("option");
        newCondition.value = conditions[conditionId]["conditionId"];
        newCondition.textContent = conditions[conditionId]["name"];
        condition_input.appendChild(newCondition);
    }
}

async function getCategories(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getCategories.php", "POST", "");
    if(jsonInfo["status"] === "success"){
        const categories_ = jsonInfo["categories"];

        categories_.forEach((category) =>{
            categories[category["categoryId"]] = category;
        });
    }
    displayCategories();
}

function displayCategories(){
    const category_input = document.getElementById("product-category");
    for(let categoryId in categories){
        let newCategory = document.createElement("option");

        newCategory.value = categories[categoryId]["categoryId"];
        newCategory.textContent = categories[categoryId]["categoryName"];
        category_input.appendChild(newCategory);
    }
}

function updateSizes(){
    const category_input = document.getElementById("product-category");
    const sizes_input = document.getElementById("product-size");
    sizes_input.innerHTML = "<option value='' selected disabled>Please select an option</option>";
    const sizes = categories[category_input.value]["sizes"].split(",");
    if(sizes.length === 0) return;
    sizes.forEach((size) =>{
        const newSize = document.createElement("option");
        newSize.value = size;
        newSize.textContent = size;
        sizes_input.appendChild(newSize);
    });
}


async function retrieveTempPictures(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/addProductPictures.php", "POST", "", {
        "X-CSRF-Token" : csrf
    });

    const imagesContainer = document.querySelectorAll(".all-images")[0];
    imagesContainer.innerHTML ="";
    for(let index in jsonInfo["images"]){
        // each image must be created inside "all-images"
        const imageContainer = document.createElement("div");
        imageContainer.classList.add("image-container");
        const newChild = document.createElement("img");
        newChild.setAttribute("id", jsonInfo["images"][index]);
        newChild.classList.add("temp-image");

        // Delete image button
        const delButton = document.createElement("i");
        delButton.classList.add("fa-solid");
        delButton.classList.add("fa-xmark");
        delButton.classList.add("del-bttn");
        delButton.setAttribute("id", jsonInfo["images"][index]);
        // -------------------

        newChild.src = "../database/uploads/temp/" + jsonInfo["uid"] + "/" + jsonInfo["images"][index];

        imagesContainer.appendChild(imageContainer);
        imageContainer.appendChild(delButton);
        imageContainer.appendChild(newChild); 

        delButton.addEventListener("click", ()  => {
            const id = delButton.id;
            // call php file to remove the id image
            removeImageWithId(id);
            imagesContainer.removeChild(delButton.parentNode);
        });
    }
    styleImages();
}

async function uploadPictures(){
    filesUploaded = true;
    const files = Array.from(document.querySelectorAll(".imageUpload")[0].files);
    const info = document.querySelectorAll(".output-info")[0];
    const formData = new FormData();
    files.forEach((file, index) => {
        formData.append("image-" + index, file);
    });
    formData.append("image-len", files.length);
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/addProductPictures.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    ajaxClient.displayJSONToUser(jsonInfo, info);
    retrieveTempPictures();
}

async function removeImageWithId(id){
    const formData = new FormData();
    formData.append("image", id);
    await ajaxClient.fetchData("../../actions/product/removeImageFromTemp.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
}


async function addProduct(){
    const name = document.querySelectorAll(".product-name")[0].value;
    const desc = document.querySelectorAll(".product-desc")[0].value;
    const size = document.querySelectorAll("#product-size")[0].value;
    let gender;
    document.getElementsByName("gender").forEach((gender_) =>{
        if(gender_.checked) gender = gender_.value;
    });
    const price = document.querySelectorAll(".price")[0].value;
    const discount = document.querySelectorAll(".discount")[0].value;
    const category = document.querySelectorAll("#product-category")[0].value;
    const status = document.querySelectorAll(".output-info")[0];
    const condition = document.querySelectorAll("#product-condition")[0].value;
    
    if(condition == ""){
        status.innerHTML = "<span style='color:red'>Incorrect value on condition!</span>";
        return;
    }
    if(category === "" ){
        status.innerHTML = "<span style='color:red'>Incorrect value on category!</span>";
        return;
    }
    if(discount < 0 || discount > 100){
        status.innerHTML = "<span style='color:red'>Please enter a valid discount! [0-100 range]</span>";
        return;
    }
    if(gender == undefined){
        status.innerHTML = "<span style='color:red'>Please select a gender!</span>";
        return;
    }
    if(price < 0 ){
        status.innerHTML = "<span style='color:red'>Price cannot be negative!</span>";
        return;
    }
    const formData = new FormData();
    formData.append("name", name);
    formData.append("description", desc);
    formData.append("size", size);
    formData.append("gender", gender);
    formData.append("price", price);
    formData.append("discount", discount);
    formData.append("category", category);
    formData.append("condition", condition);

    const jsonInfo = await ajaxClient.fetchData("../../actions/product/addProduct.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    const color = jsonInfo["status"] === "success" ? "green" : "red";

    status.innerHTML = "<span style='color:" + color +";'>" + jsonInfo["message"] + "</span>";
    if(jsonInfo["status"] === "success") location.reload();
}

async function getUserProducts(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getUserProducts.php", "POST", "", {
        "X-CSRF-Token" : csrf
    });
    
    const parent = document.querySelectorAll(".products-table")[0];
    jsonInfo["products"].forEach((product) => {
        const newProduct = document.createElement("div");
        const photo = document.createElement("img");
        photo.src = "../database/uploads/product/" + product["productId"] + "/" + product["photos"][0];

        const price = document.createElement("span");
        
        price.innerHTML = product["basePrice"] + " €";


        const name = document.createElement("a");
        name.innerHTML = product["name"];
        name.setAttribute("href", "product.php?productId=" + product["productId"]);

        
        const buttonContainer = document.createElement("label");
        buttonContainer.classList.add("round-switch");
        buttonContainer.classList.add(product["productId"]);
        const switch_ = document.createElement("i");

        const delBttn = document.createElement("div");
        delBttn.setAttribute("id", product["productId"]);
        delBttn.classList.add("delete-product-container");
        delBttn.innerHTML = '<i class="fa-regular fa-trash-can"></i>';
        
        delBttn.addEventListener("click", () => {
            deleteProduct(delBttn.id);
            document.querySelector(".products-table").removeChild(delBttn.parentElement);
        })

        if(product["visible"]){
            switch_.classList.toggle("switch-active");
        }

        switch_.addEventListener("click", () =>{
            switch_.classList.toggle("switch-active"); 
            toggleProductActive(product["productId"]); 
        });
        switch_.classList.add("fa-circle");
        switch_.classList.add("fa-solid");
        switch_.classList.add("active-bttn");

        
        const button = document.createElement("input");
        button.type = "checkbox";
        button.classList.add("active-bttn");
        buttonContainer.appendChild(button);
        buttonContainer.appendChild(switch_);
        
        newProduct.appendChild(photo);
        newProduct.appendChild(name);
        newProduct.appendChild(price);
        newProduct.appendChild(buttonContainer);
        newProduct.appendChild(delBttn);

        newProduct.classList.add("product");
        
        
        parent.appendChild(newProduct);
    });
}

async function deleteProduct(productId){
   const formData = new FormData();
   formData.append("productId", productId);
   const jsonInfo = await ajaxClient.fetchData("../../actions/product/deleteProduct.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });

}

async function toggleProductActive(productId){
    const formData = new FormData();
    formData.append("productId", productId);
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/toggleProductActive.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
}


window.addEventListener("load", () => (initPage()));
window.addEventListener('beforeunload', function(event) {
    if(filesUploaded){
        event.preventDefault();
        destructPage();
    }
});