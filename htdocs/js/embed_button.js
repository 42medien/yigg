(function(){
	var u = yigg_url ? yigg_url : document.URL;
        if (yigg_url.substring(0, 1) == "/" || yigg_url.substring(0,3) == "%2F") {
            u = window.location.origin + yigg_url;
        }
	document.write("<iframe src='http://yigg.local/nachrichten/flatbutton?url=" + escape(u) + "' height='74' width='100' frameborder='0' scrolling='no'></iframe>");
})()