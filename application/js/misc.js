function getCookie(c_name)
{
    var i, x, y, ARRcookies = document.cookie.split(";");

    for(i = 0; i < ARRcookies.length;i++)
    {
        x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x = x.replace(/^\s+|\s+$/g,"");

        if(x==c_name)
        {
            return unescape(y);
        }
    }
}

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays === null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}

function activateTab(id)
{
    $(".navigation a").removeClass("nav-active");
    $(".navigation a#nav-"+id).addClass("nav-active");

    $(".groups .group").hide();
    $(".groups #expansion-"+id).show();

    $("body").removeClass("expansion-0");
    $("body").removeClass("expansion-1");
    $("body").removeClass("expansion-2");

    $("body").addClass("expansion-"+id);

    return false;
}

/**
 * Setup ajax calls.
 */
$.ajaxSetup({
    error: function(xhr) {
        if (xhr.readyState != 4)
            return false;

        if (xhr.getResponseHeader("X-App") == "login") {
            location.reload(true);
            return false;
        }

        if (xhr.status) {
            switch (xhr.status) {
                case 301:
                case 302:
                case 307:
                case 403:
                case 404:
                case 500:
                case 503:
                    //location.reload(true);
                    return false;
                    break;
            }
        }

        return true;
    }
});