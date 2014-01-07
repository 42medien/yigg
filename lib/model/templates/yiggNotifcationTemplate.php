<?php

/**
 * Doctrine_Template_SoftDelete
 *
 * @package     Doctrine
 * @subpackage  Template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class yiggNotifcation extends Doctrine_Template
{
  /**
   * __construct
   *
   * @param string $array
   * @return void
   */
  public function __construct(array $options = array())
  {
  }

  /**
   * Set table definition for SoftDelete behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->addListener( new yiggNotifcationListener() );
  }
  
  public function deleteAssociatedNotifications()
  {
  	Doctrine_Query::create()
  	  ->update("NotificationMessage")
  	  ->set("status", "?", "send")
  	  ->where("ref_object_type = ?", get_class($this->getInvoker()))
  	  ->addWhere("ref_object_id = ?", $this->getInvoker()->id)
  	  ->execute();
  }
}
