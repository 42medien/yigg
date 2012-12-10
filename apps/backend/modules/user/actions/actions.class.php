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

    public function executeDeletes(sfWebRequest $request){
        try
        {
            $this->user = $this->getRoute()->getObject();
            if ( true) //  $this->getRoute()->getObject()->delete())
            {
                $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
            }
        }catch ( Exception $e)
        {
            $this->getUser()->setFlash('notice', $this->user->username.', could not delete '  . $e->getMessage() );
        }

        try
        {
            $text = new sfPartialView( sfContext::getInstance(), 'user', '_mailDeleteUserAccount', '');
            $text->setPartialVars( array("user" => $this->user ));

            $this->result = $this->getMailer()->sendEmail(
                                                        $this->user->email,
                                                        sprintf('Bestätigung Deiner Anmeldung bei YiGG', $this->user->username),
                                                        $text->render(),
                                                        "text/plain"
                                                    );

            if(false === $this->result)
            {
                $this->getUser()->setFlash('notice', $this->user->username.',  could not send email ');
            }
        }catch ( Exception $e)
        {
            $this->getUser()->setFlash('notice', $this->user->username.', could not delete '  . $e->getMessage() );
        }

        $this->redirect('user');
    }
    
    public function executeExportToCsv(sfWebRequest $request) 
    {
             
        $this->users = UserTable::retrieveAllUsernames();

        
        //formate header
        $data = 'Username,Nr of Articles,Nr of Comments,Email,Website'."\n";
        
        $colums = array('username', 'story_count', 'comment_count', 'email', 'website');
        $users = $this->users;
        foreach($users as $key => $value)
        {
            foreach($value as $k => $v)
            {
                if($k == 'settings'){
                    $settings = unserialize($v);
                    if(isset($settings) && !empty($settings)){
                        if(isset($settings[1]['profile']['website']) && !empty($settings[1]['profile']['website'])){
                            $users[$key]['website'] = $settings[1]['profile']['website'];
                        }
                        else{
                            $users[$key]['website'] = '';
                        }
                    }
                    unset($users[$key][$k]);
                }
            }
        }
        
        //formate rows
        foreach($users as $u_key => $u_value)
        {
            $tmp_row = '';
            foreach($colums as $c_v)
            {
                if($c_v == 'website'){
                    $tmp_row .= $u_value[$c_v]."\n";
                }
                else{
                    $tmp_row .= $u_value[$c_v].",";
                }
            }
            $data .= $tmp_row;
        }
        
        $this->setlayout(FALSE);
        $this->getResponse()->clearHttpHeaders();
        
        $this->getResponse()->setHttpHeader('Content-Type', 'application/vnd.ms-excel');
        $this->getResponse()->setHttpHeader('Content-Disposition', "attachment; filename=export_from_".date("YmdHis").".csv");
        $this->data1 = $data;
    }
    
    public function executeSendNotification(sfWebRequest $request) {
        $user_id = $request->getParameter('id');
    }
}
