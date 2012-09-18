<?php

/**
 * yiggNinjaUpdater Class reperesents the data saved in session, along with session
 * information and, after login: user data record .
 *
 * @package   yigg
 * @subpackage   user
 */
class yiggNinjaUpdater
{
  protected $actions;
 

  /**
   * Updates a form field value specified.
   * @return
   * @param $id Object
   * @param $value Object
   */
  public function updateFormField($id, $value)
  {
    $this->actions[] = array(
      "className" => "NinjaAction",
      "action" => "updateField",
      "elementId" => $id,
      "value" => $value,
    );
  }

  /**
   * Replaces the content of a field (innerHTML) via
   * $(el).update()
   *
   * @param unknown_type $id
   * @param unknown_type $value
   */
  public function updateFormFieldContent($id, $value )
  {
    $this->actions[] = array(
      "className" => "NinjaAction",
      "action" => "replaceContent",
      "elementId" => $id,
      "content" => $value,
    );
  }

  /**
   * disables a form element with by id
   * $(el).disable()
   *
   * @param unknown_type $id
   */
  public function disableFormField($id)
  {
    $this->actions[] = array(
      "className" => "NinjaAction",
      "action" => "disableField",
      "elementId" => $id
     );
  }

  /**
   * Removes an element from the page by ID
   * @return
   * @param $id Object
   */
  public function removeElement($id)
  {
    $this->actions[] = array(
      "className" => "NinjaAction",
      "action" => "removeElement",
      "elementId" => $id
    );
  }

  /**
   * Adds the JSON header and ninjaActions to the request.
   *
   * @param unknown_type $response
   * @return unknown
   */
  public function attachJSONNinjaHeader( $response )
  {
    return $response->setHttpHeader("X-JSON", json_encode( $this->actions ) );
  }
}