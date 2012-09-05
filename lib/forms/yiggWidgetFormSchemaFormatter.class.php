<?php
class yiggWidgetFormSchemaFormatter extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       			= "<div class=\"field\">\n%label% %field% %help% %error%\n</div> %hidden_fields% \n",
    $errorListFormatInARow     	= "%errors%",
    $errorRowFormatInARow      	= "<span class=\"error_message\">%error%</span>",
    $namedErrorRowFormatInARow 	= "<span class=\"error_message\">%name%: %errors%</span>\n",
    $helpFormat      			= '%help%',
    $decoratorFormat 			= "%content%";
}
