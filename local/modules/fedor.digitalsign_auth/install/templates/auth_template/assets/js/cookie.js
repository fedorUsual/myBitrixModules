function setCookie(name, value,days, MAIN_DOMAIN) {
    try {
        let cookie_date = new Date();
        cookie_date.setMonth(cookie_date.getMonth() + 1);

        if(typeof MAIN_DOMAIN  === 'undefined'){
            MAIN_DOMAIN = '/';
        }

        const expires = "expires=" + cookie_date.toUTCString();
        const domain = "domain=" + encodeURIComponent(MAIN_DOMAIN);
        const path = "path=/";

        let cookie_string = name + '='+ value +';' + expires + '; ' + domain + ';'+ path +';';
        document.cookie = cookie_string;
        //console.info(getCookie(name), '(OK) set cookie '+ name)
    } catch (e) {
        console.error(e.toString())
    }
}

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");

    if (parts.length === 2) return parts.pop().split(";").shift();
}