
async function drawProducts(parent, product){
    const formData = new FormData();
    formData.append("productId", product["productId"]);
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getProductWithId.php", "POST", formData);
    const productPhoto = jsonInfo["product"]["productPhotos"][0];

    drawCard(parent, product["productId"], productPhoto, product["basePrice"], product["discount"], product["name"]);
}

function drawCard(parent, productId, firstPhoto, basePrice, discount, name){
    const container = document.createElement("div");
    const price = Math.round(basePrice * (1 - discount / 100) * 100) / 100;

    container.classList.add("card-container");
    container.setAttribute("id", productId);
    container.addEventListener("click", () => {
        window.location.href = "product.php?productId=" + productId;
    });

    const img = document.createElement("img");
    img.src = "../database/uploads/product/" + productId + "/" + firstPhoto;

    const discountTag =document.createElement("span");
    discountTag.classList.add("discount-tag");
    discountTag.innerHTML = discount + " % OFF";

    const title = document.createElement("span");
    title.classList.add("card-price");
    title.innerHTML = price + "€";
    const subtitle = document.createElement("span");
    subtitle.classList.add("card-title");
    subtitle.innerHTML = name;

    container.appendChild(img);
    container.append(subtitle);
    if(discount != 0) container.appendChild(discountTag);
    container.appendChild(title);
    parent.appendChild(container);
}