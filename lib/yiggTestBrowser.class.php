<?php

require_once('xhtmlValidator.class.php');

/**
 * yiggTestBrowserClass extends the symfony test browser and offers some custom tools, that we simplifys the 
 * functional tests of YiGG V6
 * 
 * @author     Robert Curth <curth@yigg.de>
 * @version YiGG V6 FunkyAPE
 * @package YiGG V6
 * @subpackage APETests

 */

class yiggTestBrowser extends sfTestBrowser
{
	protected $validator = null;
	
  /**
   * Initializes the browser tester instance.
   *
   * @param string Hostname
   * @param string Remote IP address
   * @param array  Options
   */
  public function initialize($hostname = null, $remote = null, $options = array())
  {
    parent::initialize($hostname, $remote, $options);
	$this->validator = new XhtmlValidator();	
  }
	
	/**
	 * Validates the sourcecode of the current page
	 * @return boolean If valid or not
	 */
	
	public function isValidXhtml()
	{		
		$this->test()->ok($this->validator->validate($this->getResponse()->getContent()),'response is valid XHTML-Code');
		$this->validator->flushErrors();
		return $this; 		
	}
	
	/**
	 * Checks if all required elements are on the page
	 * @return boolean If valid or not
	 */
	
	
	public function isYiGG()
	{
		$this->	
			isStatusCode(200)->
			isResponseHeader('content-type', 'text/html; charset=utf-8', 'Charset is UTF8')->
			checkResponseElement('title','!/This is a temporary page/')->
			checkResponseElement('#Navigation')->
			checkResponseElement('#Header')->
			checkResponseElement('#Footer');
			
			return $this;			
	}
	
	/**
	 * Extracts csrf-key from a form and returns it.
	 * @return string csrftoken
	 */
	
	public function gettoken($url)
	{
		$this->get($url);
		return $this->extracttoken();
	}
	
	/**
	 * Checks if target of redirection matches url
	 * @param string target URL
	 */
	
	public function isRedirectedTo($url){
	
		return $this->test()->is($url, $this->getResponse()->getHttpHeader('Location'),sprintf('target of redirection matches %s', $url));
		
	}
	
	/**
	 * Checks if the response contain a given string.
	 * @param string String to be checked
	 */
	
	public function responseContainsNot($text){

    	$this->test->unlike($this->getResponse()->getContent(), '/'.preg_quote($text, '/').'/', sprintf('response does not contain "%s"', substr($text, 0, 40)));

    	return $this;
 
	}
		
	/**
	 * Extracts csrf-key from a form and returns it.
	 * @return string csrftoken
	 */
	
	private function extracttoken()
	{
		preg_match_all('/value=("([^"]*)")/siU', $this->getResponse()->getContent(), $matches, PREG_SET_ORDER);
				
			if (isset($matches[0][2])) return $matches[0][2];	
			
			else return $this->test->fail('no token was extracted');
		
		
	}
}

?>