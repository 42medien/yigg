<?php

class sendExpiredSponsoringEmailsTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addArgument('application', sfCommandArgument::REQUIRED, 'Changes the application context of the task');
    $this->namespace        = 'project';
    $this->name             = 'sendExpiredSponsoringEmails';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sendExpiredSponsoringEmails|INFO] task does things.
Call it with:

  [php symfony sendExpiredSponsoringEmails|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // Database initialization
    $databaseManager = new sfDatabaseManager($this->configuration);
    $databaseManager->loadConfiguration();

    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

    sfContext::createInstance($configuration);

    /**
     * cronjob to send expiration email to sponsor if sponsoring has been expired
     */
    $sponsorings = Doctrine::getTable('Sponsoring')->findByDql("payed = 1 AND expires < DATE_ADD( NOW(), INTERVAL 4 DAY ) AND status IN (1,2)");

    $sendingemail = array();

    foreach($sponsorings as $sponsoring)
    {
      if($sponsoring->Deal->payed == 1 && $sponsoring->Deal->sponsor_deal_id == Null )
      {
        # if expiration date in the past, or up to 1 day in the future, send email.
        $time = time() ; // seconds
        $expires = $sponsoring->getExpirationDate(false); // seconds

        //expires must be inbetween time + 3 days && only send the email once.
        if( ($expires >= $time) && ($expires <= ($time + ( 60*60*60*24*3))) && (Sponsoring::ACTIVE === (int) $sponsoring->status))
        {
          $mailTemplate =  '_mailExpire3MonthInformation';
          $sponsoring->status = Sponsoring::REMINDED;
        }
        elseif( $expires >= $time && Sponsoring::REMINDED === (int) $sponsoring->status )
        {
          // it's expired so send them the final reminder
          $mailTemplate =  '_mailExpireInformation';
          $sponsoring->status = Sponsoring::FINAL_REMINDER;
        }
        else
        {
          continue;
        }

        // update the status.
        $sponsoring->save();

        $user = $sponsoring->Deal->User;

        // send notify mail to user (who purchased the sponsorings);
        $partial = new sfPartialView( sfContext::getInstance(), 'sponsoring', $mailTemplate ,'');

        $expiresEmail = yiggMailer::prepareEmail(
          $user->email,
          'Notify: Sponsoring läuft aus',
          $partial->render(),
          'html'
        );
        $expiresEmail->setFrom("sponsorings@yigg.de");
        $sendingemail[$sponsoring->id] = $expiresEmail->send() ? "sent successfully for sponsoring #" .  $sponsoring->id : "failed to send #" . $sponsoring->id;
       }
    }

    if(count($sendingemail) > 0)
    {
      $notifyEmail = yiggMailer::prepareEmail(
        'gow@yigg.de',
        'Sponsorings daemon sent ' . count($sendingemail) .' expired email' . (count($sendingemail) ==1 ? "" : "s"),
        "Sponsorings has sent " .  count($sendingemail) ." emails for expired sponsorings.\n" . implode(", \n", $sendingemail )
      );
      $notifyEmail->send();
    }
  }
}