(function(){
	var u = yigg_url ? yigg_url : document.URL;
        if (yigg_url.substring(0, 1) == "/" || yigg_url.substring(0,3) == "%2F") {
            u = window.location.protocol + "//" + window.location.host + yigg_url;
        }
	document.write("<iframe src='http://yigg.de/nachrichten/button?flat=true&url=" + escape(u) + "' height='27' width='110' frameborder='0' scrolling='no'></iframe>");
})()