const categories = {};
const conditions = {};
let currentPage = 0;


function initPage() {
    initFilters();
    initSidebar();
    document.querySelector(".category-select").addEventListener("change", updateSizes);
    document.querySelectorAll(".show-more")[0].addEventListener("click", () => {
        getResults();
        currentPage++;
    });
    document.querySelector(".filter-bttn").addEventListener("click", () => {
        currentPage = 0;
        clearProducts();
        getResults();
        currentPage++;
    });

    document.querySelector(".clear-filter-bttn").addEventListener("click", resetFilters);

}

function initFilters() {
    const filters = document.querySelectorAll(".filter-item");
    const parent = document.querySelector(".filter-container");
    for (let i = 0; i < filters.length; i++) {
        const filter = filters[i];
        filter.addEventListener("click", () => {
            const dropdown = filter.querySelector("i");
            const filterContainer = parent.children[(i + 1) * 2 - 1];
            filterContainer.classList.toggle("hidden");
            dropdown.classList.toggle("inverted");
        });
    }
    getCategories();
    getConditions();
    getResults();
    currentPage++;
}

async function getCategories() {
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getCategories.php", "POST", "", {});
    if (jsonInfo["status"] === "success") {
        const categories_ = jsonInfo["categories"];

        categories_.forEach((category) => {
            categories[category["categoryId"]] = category;
        });
    }
    displayCategories();
}

function displayCategories() {
    const category_input = document.querySelector(".category-select");
    for (let categoryId in categories) {
        let newCategory = document.createElement("option");

        newCategory.value = categories[categoryId]["categoryId"];
        newCategory.textContent = categories[categoryId]["categoryName"];
        category_input.appendChild(newCategory);
    }
}

async function getConditions() {
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getConditions.php", "POST", "", {});
    console.log(jsonInfo);
    if (jsonInfo["status"] === "success") {
        const conditions_ = jsonInfo["conditions"];

        conditions_.forEach((condition) => {
            // for each condition i must create a option value
            const conditions_input = document.querySelector(".condition-select");
            let newCondition = document.createElement("option");
            newCondition.value = condition["conditionId"];
            newCondition.textContent = condition["name"];
            conditions_input.appendChild(newCondition);
        });
    }
}

function updateSizes() {
    const category_input = document.querySelector(".category-select");
    const sizes_input = document.querySelector(".size-select");
    sizes_input.innerHTML = "<option value='' selected>Please select an option</option>";
    const sizes = categories[category_input.value]["sizes"].split(",");

    sizes.forEach((size) => {
        const newSize = document.createElement("option");
        newSize.value = size;
        newSize.textContent = size;
        sizes_input.appendChild(newSize);
    });

}

async function getResults(){
    // To filter results i must create a ajax request 
    const category = document.querySelectorAll(".category-select")[0].value;
    const size = document.querySelectorAll(".size-select")[0].value;
    const minPrice = document.querySelectorAll(".min")[0].value;
    const maxPrice = document.querySelectorAll(".max")[0].value;
    const condition = document.querySelectorAll(".condition-select")[0].value;
    const formData = new FormData();
    formData.append("category", category);
    formData.append("size", size);
    formData.append("min", minPrice);
    formData.append("max", maxPrice);
    formData.append("condition", condition);
    formData.append("page", currentPage);
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getFilteredSearch.php", "POST", formData);
    
    // call draw drawCard() for each product
    const products = jsonInfo["products"];
    const container = document.querySelector(".products-container");
    products.forEach( async (product) => {
        const photos = jsonInfo["photos"][product["productId"]];
        drawCard(container, product["productId"], photos.length > 0 ? photos[0] : "undefined", product["basePrice"], product["discount"], product["name"]);
        
    });
}




function clearProducts(){
    document.querySelector(".products-container").innerHTML = "";
}

function initSidebar(){
    const sidebarToggle =  document.querySelector(".sidebar-hidden");
    sidebarToggle.addEventListener("click", () =>{
        const searchFilter = document.querySelector(".search-filter");
        searchFilter.classList.toggle("search-filter-sidebar");
        sidebarToggle.classList.toggle("inverted");
    });
}

function resetFilters(){
    const selects = document.querySelectorAll(".filter-select");
    const size = document.querySelector(".size-select");
    size.innerHTML = '<option class="default-option" value="" selected >Please select an option</option>';

    const priceContainer = document.querySelector(".price-container");
    priceContainer.childNodes.forEach((child) => {
        child.value = "";
    });
    selects.forEach((select) => {
        select.selectedIndex  = 0;
    });
    document.querySelector(".products-container").innerHTML = "";
    currentPage = 0;
    getResults();
    currentPage++;  
}



window.addEventListener("load", () => {
    initPage();
})