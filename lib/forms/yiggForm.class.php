<?php


/**
 * yigg form
 *
 * @package    yigg
 * @subpackage  helpers
 *
 */
class yiggForm extends sfForm
{
  protected static
    $CSRFFieldName  = '_timestamp';

  public function configure()
  {
    $this->widgetSchema->addFormFormatter('yigg', new yiggWidgetFormSchemaFormatter( $this->getWidgetSchema() ));
    $this->widgetSchema->setFormFormatterName('yigg');
  }

  /**
   * basic setup for all yigg forms setting formatter and
   */
  public function setup()
  {
    $this->widgetSchema->setNameFormat('yiggForm[%s]');
  }

  /**
   * Transforms a regular Doctrine_Collection into
   * array syntax suitable for use in a sfFormWidgetSelect
   *
   * @return array
   */
  protected function collectionToChoices( $collection, $key , $isCollection = true)
  {
    return array_map( create_function('$a','return $a["'. $key.'"];'), ($isCollection ? $collection->toArray() : $collection) );
  }

  /**
   * process form binding and validation with request values
   *
   * @param     $request
   * @return     boolean
   */
  public function processRequest()
  {
    // Get the fields from the request.
    $request = sfContext::getInstance()->getRequest();
    $fields = $this->getFieldsFromRequest( $request );
    if( null !== $fields )
    {
      // check form values
      if( true === $this->isMultipart() )
      {
        $this->bind( $fields, $request->getFiles( $this->name ) );
      }
      else
      {
        $this->bind( $fields );
      }
    }
    return $this;
  }

  /**
   * Check if the request was post, put values into form, validate - if all was fine return true elsewise false
   * @return boolean Valid
   */
  public function processAndValidate()
  {
    $request = sfContext::getInstance()->getRequest();
    return ((true === $request->isMethod('post')) && (true === $this->processRequest()->isValid()));
  }

  /**
   * process form binding and validation with array includes
   * mulitpart form support.
   *
   * @param   array $values
   * @return  yiggForm
   */
  public function processArray( $values )
  {
    if( true === $this->isMultipart() )
    {
      $this->bind(
        $values,
        sfContext::getInstance()->getRequest()->getFiles( $this->name )
      );
    }
    else
    {
      $this->bind( $values );
    }
    return $this;
  }

  /**
   * Enter description here...
   *
   * @param String $field
   * @param String $value
   * @return sfWidgetFormField
   */
  public function processField( $field, $value )
  {
    $values = array($field => $value);
    $form = $this->processArray($values);
    return $form[$field];
  }

  /**
   * get fields from request from current form widget schema name
   *
   * @param   $request
   * @return   array
   */
  public function getFieldsFromRequest( $request )
  {
    return $request->getParameter( $this->getWidgetNamePrefix() );
  }

  /**
   * get widget name for current form
   *
   * @return unknown
   */
  public function getWidgetNamePrefix()
  {
    $format = $this->widgetSchema->getNameFormat();
    return  substr($format, 0, (strpos( $format, '%' ) - 1));
  }

  /**
   * Returns the encoding token used for the CSRF protection.
   * Allows JSON data to be encoded as this token is passed over
   * @return
   */
  public function getSecurityToken()
  {
    return $this[$this->getCSRFFieldName()]->getValue();
  }

  public function batchUnsetOffsets($names)
  {
  	foreach($names as $name)
  	{
  		$this->offsetUnset($name);
  	}
  }

}