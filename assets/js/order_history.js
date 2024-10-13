function initPage(){
    getSoldProducts();
}

async function getSoldProducts(){
    const jsonInfo = await ajaxClient.fetchData("../../actions/account/getSoldProducts.php", "POST", "");


    const products = jsonInfo["products"];

    if(products.length === 0) displayEmpty();

    products.forEach((product) => {
        drawSoldProduct(product);
    });

}

function displayEmpty(){
    document.querySelector(".order-table").style.display = "none";
    document.querySelector(".orders-container").innerHTML = 
    `
        <i class="fa-regular fa-face-frown-open"></i>
        <span>You haven't sold any items yet. Keep going, your first sale is just around the corner!</span>
    `;
}

function drawSoldProduct(product){
    const parent = document.querySelector(".orders");
    const firstPhoto = "../database/uploads/product/" + product["productId"] + "/" + product["photos"][0];

    const el = 
    `
    <div class="order-item">
        <img src="${firstPhoto}">
        <span>${product["name"]}</span>
        <span>${product["customer_name"]}</span>
        <span>${product["email"]}</span>
        <a href="receipt.php?productId=${product["productId"]}"><i class="fa-solid fa-receipt"></i></a>
    </div>
    
    `;

    parent.innerHTML += el;

}

window.addEventListener("load", initPage);