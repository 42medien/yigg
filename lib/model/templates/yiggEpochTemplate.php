<?php

/**
 * 
 * @author shostakovich
 *
 */

class yiggEpoch extends Doctrine_Template
{
  protected $_options = array(
    'epoch' =>  array(
      'name'          =>  'epoch_time',
      'type'          =>  'float',
      'options'       =>  array()
    )
  );
  
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Set table definition for yiggEpoch behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->addListener( new yiggEpochListener() );
    $this->hasColumn(
      $this->_options['epoch']['name'],
      $this->_options['epoch']['type'],
      null,
      $this->_options['epoch']['options']
    );
  }
}
