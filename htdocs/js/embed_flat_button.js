(function(){
	var u = yigg_url ? yigg_url : document.URL;
        if (yigg_url.substring(0, 1) == "/" || yigg_url.substring(0,3) == "%2F") {
            u = window.location.origin + yigg_url;
        }
	document.write("<iframe src='http://yigg.de/nachrichten/button?url=" + escape(u) + "' height='20' width='77' frameborder='0' scrolling='no'></iframe>");
})()