<?php

require_once(sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . 'captcha'. DIRECTORY_SEPARATOR .'recaptchalib.php');

class yiggWidgetFormInputCaptcha extends sfWidgetForm
{

  /**
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $captcha_Public = isset($options['publicKey']) ? $options['publicKey'] : sfConfig::get('sf_captcha_Public');
    $captcha_useSSL = isset($options['useSSL']) ? $options['useSSL'] : sfConfig::get('sf_captcha_useSSL');

    $this->addOption('publicKey', $captcha_Public );
    $this->addOption('useSSL');
  }

  /**
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    ob_start();
    echo '<div id="recaptcha_div"></div>';
    if( true == sfContext::getInstance()->getRequest()->isXmlHttpRequest() )
    {
      echo recaptcha_get_html(
        $this->getOption('publicKey'),
        $error = null,
        $this->getOption('useSSL')
      );

    }
    else
    {
      echo recaptcha_get_noscriptHTML(
        $this->getOption('publicKey'),
        $error = null,
        $this->getOption('useSSL')
      );
    }
    return ob_get_clean();
  }
}