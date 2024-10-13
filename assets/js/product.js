
function initPage() {
    styleImages();
    messageButtonInit();
    getSimilarProducts();
    

    document.querySelectorAll(".product-add-bttn")[0].addEventListener("click", addProduct);
}

function initArrows() {
    const itemsContainer = document.querySelector(".items-container");
    const scrollLeftBttn = document.querySelector(".fa-angle-left");
    const scrollRightBttn = document.querySelector(".fa-angle-right");
    const length = itemsContainer.childNodes[0].length;
    let currentIdx = 0;

    scrollLeftBttn.addEventListener("click", () => {
        currentIdx--;
        if (currentIdx < 0) currentIdx = 0;
        console.log(itemsContainer.children[currentIdx].clientWidth);
        itemsContainer.scrollLeft -= itemsContainer.children[currentIdx].clientWidth - 10;
    });

    scrollRightBttn.addEventListener("click", () => {
        currentIdx++;
        if (currentIdx >= length) currentIdx = length - 1;
        itemsContainer.scrollLeft += itemsContainer.children[currentIdx].clientWidth + 10;

    });

}

function styleImages() {
    const images = document.querySelectorAll(".miniature-img");
    const main = document.querySelectorAll(".main-img-container > img")[0];
    images.forEach((image) => {
        image.addEventListener("click", () => {
            const src = image.querySelector("img").src;
            main.src = src;
        });
    });
}

async function addProduct() {
    const formData = new FormData();
    const el = document.querySelector(".product-page").id;
    formData.append("productId", el);
    const csrf = document.querySelector(".csrf").value
    const jsonInfo = await ajaxClient.fetchData("../../actions/cart/addProductToCart.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });

    const info = document.querySelector(".buy-info");
    if (jsonInfo["status"] == "success") {
        info.innerHTML = "<span style='color:green;'>" + jsonInfo["message"] + "</span>";
    }
    else {
        info.innerHTML = "<span style='color:red;'>" + jsonInfo["message"] + "</span>"
    }
}

async function getSimilarProducts() {
    const queryString = window.location.search;
    const params = new URLSearchParams(queryString);
    const productId = params.get("productId");

    const formData = new FormData();
    formData.append("productId", productId);
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getSimilarProducts.php", "POST", formData);

    if (jsonInfo["status"] === "error" || jsonInfo["products"].length === 0) {
        document.querySelector(".alike-items-empty").classList.toggle("hidden");
        document.querySelector(".alike-items-container").style.display = "none";
        return;
    }

    const products = jsonInfo["products"];
    const parent = document.querySelector(".items-container");
    products.forEach((product) => {
        drawProducts(parent, product);
    });
    initArrows();
}

function messageButtonInit() {
    const messageButton = document.querySelector(".product-start-message");
    const productId = document.querySelector(".product-page").id;
    messageButton.addEventListener("click", () => {
        window.location.href = "messages.php?productId=" + productId;
    });
}

window.addEventListener("load", initPage);