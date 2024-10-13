// SIngleton pattern for a AJAXClient

class AJAXClient {

    constructor() {
        if (!AJAXClient.instance) {
            AJAXClient.instance = this;
        }
        return AJAXClient.instance;
    }

    async fetchData(url, method = "POST", body = "", headers = {}) {
        try {
            const response = await fetch(url, {
                method: method,
                body: body,
                headers: headers
            });
            const jsonData = await response.json();
            return jsonData;
        } catch (error) {
            return "";
        }
    }

    displayJSONToUser(jsonData, element){
        if(jsonData.message == "") return;
        if(jsonData.status == "success"){
            element.innerHTML = "<span style='color:green;'>" + jsonData.message  +"</span>";
            return;
        }
        element.innerHTML = "<span style='color:red;'>" + jsonData.message  +"</span>";
    }
    
    encodeFormForAjax(data) {
        return Array.from(data)
            .filter(el => el.value.trim() !== "") // Filter out elements with empty values
            .map(el => encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value.trim()))
            .join('&');
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new AJAXClient();
        }
        return this.instance;
    }
}