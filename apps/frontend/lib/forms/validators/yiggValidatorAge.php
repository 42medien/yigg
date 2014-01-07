<?php

class yiggValidatorAge extends sfValidatorBase
{
/**
 * Configures the current validator.
 *
 * @see sfValidator
 */
	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);

		$this->addMessage('format',"Dieses Datum hat ein falsches Format (dd.mm.yyyy)");
		$this->addMessage('young_error', "Du bist zu jung. Irgendwas stimmt da nicht..");
		$this->addMessage('old_error', "Bitte gib dein wahres oder kein Alter ein.");
	}

	protected function doClean($value)
	{
		//Does the string look damned strange?
		if(false == preg_match('/[0-3][0-9].[01][0-9].[12][0-9]{3}/',$value) )
		{
			throw new sfValidatorError($this, 'format');
		}
		
		$date_elements  = explode('.',$value);
		list($day, $month, $year) = $date_elements;    	
    	
        if (false === checkdate($month, $day, $year) )
        {
	    	throw new sfValidatorError($this, 'format');
        }    

    	$min_age = strtotime('-10 YEARS');
    	$max_age = strtotime('-108 YEARS');
    	$birth_date = strtotime( $year."-".$month."-".$day );

    	if ($birth_date === false)
	    {
	    	throw new sfValidatorError($this, 'format');
    	}
    	else if($birth_date > $min_age)
    	{
    		throw new sfValidatorError($this, 'young_error');
    	}
    	else if($birth_date < $max_age)
    	{
    		throw new sfValidatorError($this, 'old_error');
    	}
    	else
    	{
    		return yiggTools::getDbDate(null, mktime(0, 0, 0, $month, $day, $year));
    	}

	}
}


?>