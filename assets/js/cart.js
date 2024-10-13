function initPage() {
    getProducts();
}

async function getProducts() {
    let cartTotal = 0;
    document.querySelector(".cart-content").innerHTML = "";
    const jsonInfo = await ajaxClient.fetchData("../../actions/cart/retrieveCart.php", "POST");

    if(jsonInfo["status"] === "error" || jsonInfo["cart"].length === 0) {
        document.querySelector(".cart-empty").classList.remove("hidden");
        document.querySelector(".card-total-container").classList.add("hidden");
        document.querySelector(".cart-footer").classList.add("hidden");
        document.querySelector(".cart-content").classList.add("hidden");
    }
    for (index in jsonInfo["cart"]) {

        const cartItem = jsonInfo["cart"][index].productId;
        const formData = new FormData();
        formData.append("productId", cartItem);
        const response = await ajaxClient.fetchData("../../actions/product/getProductWithId.php", "POST", formData);
        if (response["status"] === "error") return;

        const productInfo = response["product"]["productInfo"];
        const productPhoto = response["product"]["productPhotos"][0];
        const price = Math.round((productInfo["basePrice"] * (1 - (productInfo["discount"] / 100)) * 100)) / 100;
        cartTotal += price;
        
        generateCartItem(cartItem, productPhoto, productInfo["name"], price, productInfo["discount"], index);
    }
    document.querySelector(".card-price").innerHTML = cartTotal.toFixed(2) + "  €";
}

async function eliminateProduct(idx) {
    const csrf = document.querySelector(".csrf").value;
    const formData = new FormData();
    formData.append("idx", idx);
    const jsonInfo = await ajaxClient.fetchData("../../actions/cart/removeItemFromCart.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    if (jsonInfo["status"] === "error") {
        console.log(jsonInfo["status"]);
        return;
    }
    getProducts();
}

function generateCartItem(productId, img, name, price, discount, idx) {
    const parent = document.querySelectorAll(".cart-content")[0];

    let cartItemDiv = document.createElement('div');
    cartItemDiv.classList.add('cart-item');

    let productImage = document.createElement('img');
    productImage.src = '../database/uploads/product/' + productId + '/' + img;
    cartItemDiv.appendChild(productImage);

    let itemInfoDiv = document.createElement('div');
    itemInfoDiv.classList.add('item-info');

    let productNameSpan = document.createElement('span');
    productNameSpan.textContent = name;
    itemInfoDiv.appendChild(productNameSpan);

    let eliminateSpan = document.createElement('span');
    eliminateSpan.textContent = 'Eliminate ';
    eliminateSpan.classList.add('item-eliminate');
    eliminateSpan.id = idx;

    eliminateSpan.addEventListener("click", () => {
        eliminateProduct(eliminateSpan.id);
    });

    let trashIcon = document.createElement('i');
    trashIcon.classList.add('fa-regular', 'fa-trash-can');

    eliminateSpan.appendChild(trashIcon);

    itemInfoDiv.appendChild(productNameSpan);
    itemInfoDiv.appendChild(eliminateSpan);
    cartItemDiv.appendChild(itemInfoDiv);

    let discountSpan = document.createElement('span');
    discountSpan.classList.add("item-discount");
    discountSpan.textContent = discount + " %";

    cartItemDiv.appendChild(discountSpan);

    let itemPriceSpan = document.createElement('span');
    itemPriceSpan.classList.add('item-price');
    itemPriceSpan.textContent = price + ' €';


    cartItemDiv.appendChild(itemPriceSpan);

    parent.appendChild(cartItemDiv);
}

window.addEventListener("load", initPage);