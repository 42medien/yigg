_yigg_iframe=document.createElement('iframe');
_yigg_iframe.src='http://dev.yigg.de/frontend_dev.php/nachrichten/instant-create?url='+encodeURIComponent(location.href);
_yigg_iframe.width="500";
_yigg_iframe.height="500";
_yigg_iframe.className="yigglet";
document.getElementsByTagName('body')[0].appendChild(_yigg_iframe);