<?php

require_once dirname(__FILE__).'/../lib/userGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/userGeneratorHelper.class.php';

/**
 * user actions.
 *
 * @package    yigg
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends autoUserActions
{
    public function executeListStatistics(sfWebRequest $request){
        $this->user = Doctrine::getTable("User")->findOneById($request->getParameter('id'));
        $this->last_story_date = Story::getUserLastStoryDate($this->user);
        $this->last_comment_date = Comment::getUserLastCommentDate($this->user);
        $this->stats = $this->user->getUserStats();
        $this->stats->updateStats();
        $this->yigged_news = $this->stats->yiggs_total;
        $this->follower = $this->stats->friends_total;
        $this->followees = $this->stats->external_friends_total;
        $this->published_news = $this->stats->storys_total;
    }

    public function executeDelete(sfWebRequest $request){
        try
        {
            if ($this->getRoute()->getObject()->delete())
            {
                $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
            }
        }catch ( Exception $e)
        {
            $this->logMessage("User::register, could not delete user : " . $e->getMessage() . $e->getTraceAsString(), "CRIT");
            return sfView::ERROR;
        }

        $text = new sfPartialView( sfContext::getInstance(), 'user', '_mailDeleteUserAccount', '');
        $text->setPartialVars( array("user" => $this->getRoute()->getObject() ));

        $this->result = 1 === $this->getMailer()->sendEmail($this->user->email,sprintf('Bestätigung Deiner Anmeldung bei YiGG',$this->user->username),$text->render(),"text/plain");
        if(false === $this->result)
        {
            $this->logMessage("User::register, could not send email for user : {$this->user->username}", "CRIT");
            return sfView::ERROR;
        }

        $this->redirect('user');
    }
}
