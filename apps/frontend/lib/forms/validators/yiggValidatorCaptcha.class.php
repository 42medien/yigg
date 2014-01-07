<?php

require_once(sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . 'captcha'. DIRECTORY_SEPARATOR .'recaptchalib.php');

/**
 * 
 * @package     yigg
 * @subpackage  helper
 */
class yiggValidatorCaptcha extends sfValidatorPass
{
    const CAPTCHA_INVALID       = 'invalid';

    const CHALLENGE_FIELD_NAME  = 'recaptcha_challenge_field';
    const RESPONSE_FIELD_NAME   = 'recaptcha_response_field';
    
    
    
    /**
     * configure captcha validator
     * possible errors:
     *  - captcha_error
     * 
	 * @param array $options
	 * @param array $messages
     */
    protected function configure($options = array(), $messages = array())
    {
        $privateKey = isset($options['privateKey']) 
                        ? $options['privateKey'] : sfConfig::get('sf_captcha_Private');
        
        $this->addOption('privateKey', $privateKey);
        
        $this->addMessage(self::CAPTCHA_INVALID, 'Captcha validation failed.');
    }
    
    
	/**
	 * process validation
	 *
	 * @param 	array 	$values
	 * @throws	sfValidatorErrorSchema		fields or login data invalid
	 * @throws	InvalidArgumentException	if no array is passed
	 * @return 	array
	 */
	public function doClean($value)
	{
	    $request    = sfContext::getInstance()->getRequest();
        $challenge  = $request->getParameter('recaptcha_challenge_field');
        $response   = $request->getParameter('recaptcha_response_field');
        
        // query recatcha
        $captchaResult = recaptcha_check_answer (
                            $this->getOption('privateKey'),
                            // there's no better way of doing this in symfony..
                            $_SERVER["REMOTE_ADDR"], 
                            $challenge,
                            $response
        );
	    
        if( false == $captchaResult->is_valid ){
            throw new sfValidatorError($this, self::CAPTCHA_INVALID);
        }
        
        return $value;
	}
    
}