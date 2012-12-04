(function(){
//	var u = yigg_url ? yigg_url : YIGG_URL;
        var u = document.URL;
	document.write("<iframe src='http://yigg.de/nachrichten/flatbutton?url=" + escape(u) + "' height='74' width='100' frameborder='0' scrolling='no'></iframe>");
})()
