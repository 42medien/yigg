<?php
class yiggMailer extends sfMailer
{
  /**
   * Composes mails, adds our email-adress, sets content type and sends it
   * @param $to
   * @param $message
   * @return int|false The number of sent emails
   */
  public function sendEmail($to, $subject, $message, $content_type="text/html")
  {
    $message = $this->compose('test@yopeso.com',$to,$subject, $message);   //no-reply@yigg.de
    $message->setContentType($content_type);
    return $this->send($message);
  }
}
?>