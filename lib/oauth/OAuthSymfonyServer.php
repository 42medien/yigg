<?php //vim: foldmethod=marker
//require_once("OAuth.php");

/**
 * Enter description here...
 *
 * @author Matthias Pfefferle
 */
class OAuthSymfonyServer extends OAuthServer {
  /**
   * returns all signature methods
   *
   * @return array
   */
  public function get_signature_methods() {
    return $this->signature_methods;
  }

  /**
   * constructor to set the signatures
   */
  public function __construct() {
    $this->add_signature_method(new OAuthSignatureMethod_HMAC_SHA1());

    parent::__construct(new OAuthSymfonyStore());
  }

  /**
   * store the request into the session
   *
   * @param sfWebRequest $pRequest
   */
  public function storeRequestToSession( $oauth_request = null ){
    if (!isset($oauth_request)) {
      sfContext::getInstance()->getUser()->setAttribute('remembered_oauth_request', null );
    } else {
      sfContext::getInstance()->getUser()->setAttribute('remembered_oauth_request', serialize($oauth_request) );
    }
  }

  /**
   * load the request from the session1
   *
   * @return sfWebRequest | null
   */
  public function loadRequestFromSession(){
    if( $req = sfContext::getInstance()->getUser()->getAttribute('remembered_oauth_request') ){
      return unserialize($req);
    }
    return null;
  }
}
?>
