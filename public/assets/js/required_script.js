// Checking if application is in iframe to redirect to application url page //
window.addEventListener("DOMContentLoaded", () => {
    getParameter = (key) => {
        // Address of the current window
        address = window.location.search;
        // Returns a URLSearchParams object instance
        parameterList = new URLSearchParams(address);
        // Returning the respected value associated
        // with the provided key
        return parameterList.get(key);
    };

    if(getParameter("shop") != ''){
        if(window.self !== window.top || window.location !== window.parent.location){
            document.querySelector("html").style.background = "#fff";
            document.querySelector("body").style.display = "none";

            let application_url = ""; // Change this to your application url without / on the end in url

            let hmac = getParameter("hmac") != '' ? getParameter("hmac") : "";
            let host = getParameter("host") != '' ? getParameter("host") : "";
            let locale = getParameter("locale") != '' ? getParameter("locale") : "";
            let session = getParameter("session") != '' ? getParameter("session") : "";
            let shop = getParameter("shop") != '' ? getParameter("shop") : "";
            let timestamp = getParameter("timestamp") != '' ? getParameter("timestamp") : "";
            
            let url = `${application_url}/?hmac=${hmac}&host=${host}&locale=${locale}&session=${session}&shop=${shop}&timestamp=${timestamp}`;

            if(application_url != "" && hmac != "" && host != "" && locale != "" && session != "" && shop != "" && timestamp != ""){ 
                window.history.go(-1);
                window.open(url, '_blank');
            }else{
                window.location.reload();
            }
        }

        if(window.location.href.indexOf('shop')) {
            window.history.pushState(null, null, window.location.pathname);
        }
    }
});