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

    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
    {
        $rowFormat = strtr($this->getRowFormat(), array(
            '%label%'         => $label,
            '%field%'         => $field,
            '%error%'         => $this->formatErrorsForRow($errors),
            '%help%'          => $this->formatHelp($help),
            '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ));

        echo $this->getWidgetSchema();

        return $rowFormat;
    }
}
