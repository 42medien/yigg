<?php
/**
 * create a form for editing user's notification settings
 *
 * @package   yigg
 * @subpackage  user
 */
class FormUserNotifications extends yiggForm
{
  public function setup()
  {
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'AuthorCommentEmail'        => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox')),
          'NewerCommentEmail'    => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox')),
          'AtMentionEmail'       => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox')),
          'UserFollowerEmail'    => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox')),
          'NotificationMessageEmail'  => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox')),
        )
      )
    );

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          'AuthorCommentEmail'       => new sfValidatorBoolean(),
          'NewerCommentEmail'   => new sfValidatorBoolean(),
          'AtMentionEmail'      => new sfValidatorBoolean(),
          'UserFollowerEmail'   => new sfValidatorBoolean(),
          'NotificationMessageEmail' => new sfValidatorBoolean(),
        )
      )
    );

    $this->widgetSchema->setNameFormat('editSettings[%s]');

    $this->widgetSchema->setLabels(
      array(
        'AuthorCommentEmail'         => "E-Mail, wenn mich jemand anspricht (Kommentar etc.)",
        'NewerCommentEmail'     => "E-Mail, bei Kommentar auf eine eigene Nachricht.",
        'AtMentionEmail'        => "E-Mail, wenn Nachricht via @Benutzername",
        'UserFollowerEmail'     => "E-Mail, wenn neues Freundschaftsangebot",
        'NotificationMessageEmail'   => "E-Mail, bei erhalt einer Privaten Mitteilung.",
      )
    );

    parent::setup();
  }
}