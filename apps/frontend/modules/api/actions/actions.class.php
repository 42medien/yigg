<?php

/**
 * api actions.
 *
 * @package    yigg
 * @subpackage api
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request) {
    $this->forward('default', 'module');
  }
  
  /**
   * Executes opensearch action
   *
   * @param sfRequest $request A request object
   */
  public function executeOpensearch(sfWebRequest $request) {
    $this->setLayout(false);
    
    $response = $this->getResponse();
    $response->setContentType('application/opensearchdescription+xml');
  }
  
  /**
   * Executes oexchange action
   *
   * @param sfRequest $request A request object
   */
  public function executeOexchange(sfWebRequest $request) {
    $this->setLayout(false);
    
    $response = $this->getResponse();
    $response->setContentType('application/xrd+xml');
  }
   
  /**
   * Executes host-meta action
   *
   * @param sfRequest $request A request object
   */
  public function executeHostmeta(sfWebRequest $request) {
    $this->setLayout(false);
    sfContext::getInstance()->getConfiguration()->loadHelpers( 'Url' );
    
    $format = $request->getParameter("format", "xrd");
    $response = $this->getResponse();
    
    $this->host_meta = array("links" => array(
                                          array("rel" => "search", "type" => "application/opensearchdescription+xml", "href" => url_for("@opensearch", true)),
                                          array("rel" => "http://oexchange.org/spec/0.8/rel/resident-target", "type" => "application/xrd+xml", "href" => url_for("@oexchange", true))
                                        )
                            );
    
    if ($format == "xrd") {
      $response->setContentType('application/xrd+xml');
      $this->setTemplate("hostmeta");
    } else {
      $response->setContentType('application/json');
      return $this->renderText(json_encode($this->host_meta));
    }
  }
}
