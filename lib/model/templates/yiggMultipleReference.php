<?php

/**
 * Sets up the behaviour for only deleting references to tags,
 * instead of the tag itself.
 *
 */
class yiggMultipleReference extends Doctrine_Template
{
  /**
   * Set table definition for SoftDelete behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $this->addListener( new yiggMultipleReferenceListener() );
  }
}