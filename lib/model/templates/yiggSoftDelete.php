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
class yiggSoftDelete extends Doctrine_Template
{
  /**
   * Array of SoftDelete options
   *
   * @var array
   */
  protected $_options = array(
    'deleted' =>  array(
      'name'          =>  'deleted_at',
      'alias'         =>  null,
      'type'          =>  'timestamp',
      'format'        =>  'Y-m-d H:i:s',
      'disabled'      =>  false,
      'expression'    =>  false,
      'options'       =>  array()
    )
  );

  /**
   * __construct
   *
   * @param string $array
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Set table definition for SoftDelete behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->hasColumn(
      $this->_options['deleted']['name'],
      $this->_options['deleted']['type'],
      null,
      $this->_options['deleted']['options']
    );
    $this->addListener( new yiggSoftDeleteListener($this->_options) );
  }
}
