let messages = new Map();
let userId;
let selectedProduct;
let receiverId;

function initPage() {
    loadSidebar();
    document.querySelector(".message-box").addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    document.querySelector(".push-sidebar").addEventListener("click", pushSidebar);

    document.querySelector(".send-message-icon").addEventListener("click", () => {
        sendMessage();
    });
}
function pushSidebar(){
    document.querySelector(".messages-sidebar").classList.toggle("messages-hidden");
    document.querySelector(".push-sidebar").classList.toggle("sidebar-hidden");
    document.querySelector(".push-icon").classList.toggle("fa-arrow-right");
    document.querySelector(".push-icon").classList.toggle("fa-arrow-left");
}

async function loadNewMessage(){
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    if(!params.has("productId")) return;
    // if productId is set generate new message
    const productId = params.get("productId");
    
    
    // Build the message layout
    const formData = new FormData();
    formData.append("productId", productId);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getProductWithId.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });

    if(jsonInfo["status"] === "error") return;
    const product = jsonInfo["product"];
    const price = (product["productInfo"]["basePrice"] * ( 1 - (product["productInfo"]["discount"] / 100))).toFixed(2);
    
    // new message to seller
    selectedProduct = productId;
    receiverId = product["productInfo"]["sellerId"];
    
    if(document.querySelector("#product-" + productId.toString()) != null){
        // conversation already exists
        createMessageContainer(productId);
        return;
    }else{
        displayMessageHeader(productId, product["productPhotos"][0], product["productInfo"]["name"], price);
        displaySendInput();
    }

}



// Load sidebar that gets all the messages and displays rectangles with (photo of the product - name of the product - last message)
async function loadSidebar() {
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/messages/getUserMessages.php", "POST", "", {
        "X-CSRF-Token" : csrf 
    });
    const userInfo = await ajaxClient.fetchData("../../actions/account/retrieveUserInfo.php");

    userId = JSON.parse(userInfo["data"])["userid"];

    jsonInfo["messages"].forEach((message) => {
        messages.set(message["productId"], message);
    });

    const sidebar = document.querySelector(".messages-sidebar");
    if (jsonInfo["messages"].length === 0) {
        sidebar.innerHTML = "<span>No messages to show.</span>"
    }

    let promises = [];
    messages.forEach((value, key) =>{
        promises.push(createMessageCard(value));
    });

    Promise.all(promises).then(() => {
        loadNewMessage();
    }).catch((error) => {
        console.error("Error loading messages:", error);
    });
}

async function createMessageCard(message) {
    const formData = new FormData();
    const productId = message["productId"];
    formData.append("productId", productId);
    const csrf = document.querySelector(".csrf");
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getProductWithId.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    
    const title = jsonInfo["product"]["productInfo"]["name"];
    const photo = jsonInfo["product"]["productPhotos"][0];

    const element = 
    `
    <div id="product-${productId}" class="message-container">
        <img src="../../database/uploads/product/${productId}/${photo}">
        <span class="product-title">${title}</span>
        <span class="product-last-message">${message["senderId"] === userId ? "You:" : ""}${message["content"]}</span>
        <span class="product-last-time">${message["time"].slice(0, 5)}</span>
    </div>
    `;
    
    document.querySelector(".messages-sidebar").innerHTML += element;
    document.querySelectorAll(".message-container").forEach((container) =>{
        container.addEventListener("click", () => {
            if(selectedProduct == container.id.substring(8)) return;
            createMessageContainer(container.id.substring(8));
        });
    });
}

function displaySendInput(){
    document.querySelector(".send-container").classList.remove("hidden");
}

function setActiveCard(productId) {
    if(document.getElementById("product-" + selectedProduct) != null){
        document.getElementById( "product-" + selectedProduct).classList.remove("active");
    }
    document.getElementById("product-" + productId).classList.add("active");
}

async function createMessageContainer(productId){

    // Info about the product
    const formData = new FormData();
    formData.append("productId", productId);
    const csrf = document.querySelector(".csrf");
    const jsonInfo = await ajaxClient.fetchData("../../actions/product/getProductWithId.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });
    const photo = jsonInfo["product"]["productPhotos"][0];
    const name = jsonInfo["product"]["productInfo"]["name"];
    const price = (jsonInfo["product"]["productInfo"]["basePrice"] * (1 - (jsonInfo["product"]["productInfo"]["discount"] / 100))).toFixed(2);
    setActiveCard(productId); 
    selectedProduct = productId;
    
    // This function is responsible for displaying all the messages
    displayMessageHeader(productId, photo, name, price);
    displayMessages(productId);
    displaySendInput();
}

function removeLastMessage(){
    const messageDiv = document.querySelector(".messages-div");
    
    if(messageDiv === null){
        const newMessageDiv = document.createElement("div");
        newMessageDiv.classList.add("messages-div");
        document.querySelector(".message-body-container").appendChild(newMessageDiv);
    }
    messageDiv.innerHTML = "";   
}


function displayMessageHeader(productId, photo, name, price){
    // remove lastMessage
    removeLastMessage();
    const container = document.createElement("div");
    container.classList.add("new-message-container");
    const element = 
    `
    <div class="new-message-header">
        <img src="../database/uploads/product/${productId}/${photo}"> 
        <span>${name}</span>
        <span>${price} €</span>
    </div> 
    `;
    container.innerHTML = element;
    document.querySelector(".messages-div").appendChild(container);
}

async function displayMessages(productId) {
    // Load messages for exchange clientId - productId
    const formData = new FormData();
    formData.append("productId", productId);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/messages/getMessagesWithId.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });

    const messageBody = document.querySelector(".messages-div");
    const messageContainer = document.createElement("div");
    messageContainer.classList.add("messageContainer");
    

    messageBody.appendChild(messageContainer);
    for (let i = jsonInfo["messages"].length - 1; i >= 0; i--) {
        const message = jsonInfo["messages"][i];
        displayMessage(message["content"], message["time"], message["date"], message["senderId"] != userId);
    }

    messageContainer.scrollTop = messageContainer.scrollHeight;
}

function displayMessage(message, time, date, received){
    const element = 
    `
        <span class=${received ? "received" : "sent"}>${message}</span>
        <span class="dateSpan ${received ? "received" : "sent"}">${date}@${time.substring(0,5)}</span>
    `;
    const parent = document.querySelector(".messageContainer");
    if(parent === null){
        
        document.querySelector(".messages-div").innerHTML += "<div class='messageContainer'></div>";
    }
    const parent_ = document.querySelector(".messageContainer");
    parent_.innerHTML += element;
    parent_.scrollTop = parent_.scrollHeight;
}

function getCurrentDate() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); 
    const day = String(now.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`; 
}

function getCurrentTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`; 
}


async function sendMessage() {
    if (selectedProduct == null){
        return;
    }

    const productId = selectedProduct;
    const messageBox = document.querySelector(".message-box");
    const data = messageBox.value;
    if (data.length <= 0) return;

    const formData = new FormData();

    formData.append("productId", productId);
    formData.append("senderId", userId);
    formData.append("date", getCurrentDate());
    formData.append("time", getCurrentTime());
    
    
    let endpoint;
    if(!messages.has(productId)) endpoint = receiverId;
    else{
        endpoint = messages.get(Number(selectedProduct))["receiverId"] != userId ? messages.get(Number(selectedProduct))["receiverId"] : messages.get(Number(selectedProduct))["senderId"];
    }
    

    formData.append("receiverId", endpoint); 
    formData.append("data", data);
    const csrf = document.querySelector(".csrf").value;
    const jsonInfo = await ajaxClient.fetchData("../../actions/messages/sendMessage.php", "POST", formData, {
        "X-CSRF-Token" : csrf
    });


    if (jsonInfo["status"] == "success") {
        displayMessage(jsonInfo["data"]["content"], jsonInfo["data"]["time"], jsonInfo["data"]["date"], false);
        messageBox.value = "";
    }
}

window.addEventListener("load", initPage);