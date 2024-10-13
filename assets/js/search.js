
function initPage(){
    retrieveProducts();
}

async function retrieveProducts(){
    const queryString = window.location.search;
    const params = new URLSearchParams(queryString);
    const query = decodeURIComponent( params.get("query"));
    
    const formData = new FormData();
    formData.append("query", query);
    const jsonInfo = await ajaxClient.fetchData("../../actions/search/query.php", "POST", formData);
    const search_container = document.createElement("div");
    search_container.classList.add("search-container");

    if(jsonInfo["status"] === "error" || jsonInfo["products"].length === 0){
        // couldn't find any products
        search_container.innerHTML += '<i class="fa-regular fa-face-frown-open"></i>';
        search_container.innerHTML += `<h2>No search results for \"${query}\"</h2>`;
    }else{
        const cardsContainer = document.createElement("div");
        cardsContainer.classList.add("cards-container");

        search_container.innerHTML += `<h2>Search results for: \"${query}\"</h2>`;
        const products = jsonInfo["products"];
        products.forEach((product) => {
            drawProducts(cardsContainer, product);
        });
        search_container.appendChild(cardsContainer);
    }

    document.querySelector("main").appendChild(search_container);
}


window.addEventListener("load", initPage);