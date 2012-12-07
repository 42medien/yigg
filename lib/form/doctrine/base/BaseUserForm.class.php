<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    yigg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'username'       => new sfWidgetFormInputText(),
      'salt'           => new sfWidgetFormInputText(),
      'password'       => new sfWidgetFormInputText(),
      'status'         => new sfWidgetFormInputCheckbox(),
      'block_post'     => new sfWidgetFormInputCheckbox(),
      'last_login'     => new sfWidgetFormDateTime(),
      'email'          => new sfWidgetFormInputText(),
      'privacy'        => new sfWidgetFormInputCheckbox(),
      'last_ip'        => new sfWidgetFormInputText(),
      'failed_logins'  => new sfWidgetFormInputText(),
      'avatar_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Avatar'), 'add_empty' => true)),
      'award_comment'  => new sfWidgetFormChoice(array('choices' => array('bronze' => 'bronze', 'silver' => 'silver', 'gold' => 'gold'))),
      'award_story'    => new sfWidgetFormChoice(array('choices' => array('bronze' => 'bronze', 'silver' => 'silver', 'gold' => 'gold'))),
      'mclient_salt'   => new sfWidgetFormInputText(),
      'configuration'  => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'deleted_at'     => new sfWidgetFormDateTime(),
      'following_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'User')),
      'followers_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'User')),
      'tags_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tag')),
      'domains_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Domain')),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'username'       => new sfValidatorString(array('max_length' => 32)),
      'salt'           => new sfValidatorString(array('max_length' => 128)),
      'password'       => new sfValidatorString(array('max_length' => 128)),
      'status'         => new sfValidatorBoolean(array('required' => false)),
      'block_post'     => new sfValidatorBoolean(array('required' => false)),
      'last_login'     => new sfValidatorDateTime(array('required' => false)),
      'email'          => new sfValidatorEmail(array('max_length' => 255, 'required' => false)),
      'privacy'        => new sfValidatorBoolean(array('required' => false)),
      'last_ip'        => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'failed_logins'  => new sfValidatorInteger(array('required' => false)),
      'avatar_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Avatar'), 'required' => false)),
      'award_comment'  => new sfValidatorChoice(array('choices' => array(0 => 'bronze', 1 => 'silver', 2 => 'gold'), 'required' => false)),
      'award_story'    => new sfValidatorChoice(array('choices' => array(0 => 'bronze', 1 => 'silver', 2 => 'gold'), 'required' => false)),
      'mclient_salt'   => new sfValidatorString(array('max_length' => 64)),
      'configuration'  => new sfValidatorString(array('max_length' => 100000, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
      'deleted_at'     => new sfValidatorDateTime(array('required' => false)),
      'following_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'User', 'required' => false)),
      'followers_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'User', 'required' => false)),
      'tags_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Tag', 'required' => false)),
      'domains_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Domain', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'User', 'column' => array('username')))
    );

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['following_list']))
    {
      $this->setDefault('following_list', $this->object->Following->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['followers_list']))
    {
      $this->setDefault('followers_list', $this->object->Followers->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['tags_list']))
    {
      $this->setDefault('tags_list', $this->object->Tags->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['domains_list']))
    {
      $this->setDefault('domains_list', $this->object->Domains->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveFollowingList($con);
    $this->saveFollowersList($con);
    $this->saveTagsList($con);
    $this->saveDomainsList($con);

    parent::doSave($con);
  }

  public function saveFollowingList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['following_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Following->getPrimaryKeys();
    $values = $this->getValue('following_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Following', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Following', array_values($link));
    }
  }

  public function saveFollowersList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['followers_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Followers->getPrimaryKeys();
    $values = $this->getValue('followers_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Followers', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Followers', array_values($link));
    }
  }

  public function saveTagsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['tags_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Tags->getPrimaryKeys();
    $values = $this->getValue('tags_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Tags', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Tags', array_values($link));
    }
  }

  public function saveDomainsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['domains_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Domains->getPrimaryKeys();
    $values = $this->getValue('domains_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Domains', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Domains', array_values($link));
    }
  }

}
