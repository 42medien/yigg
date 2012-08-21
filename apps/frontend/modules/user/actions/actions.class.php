<?php
class userActions extends yiggActions
{
  /**
   * sets the slot for sponsoring (mein Yigg)
   * @return unknown_type
   */
  public function preExecute()
  {
    parent::preExecute();
    if($this->getRequest()->getParameter("sf_format") === "atom")
    {
      return;
    }
    $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 7 ,'limit' => 1)));
  }

  /**
   * Action that handles settings for the user
   */
  public function executeSettings($request)
  {
    $this->user = $this->session->getUser();

    $this->settings_form = new FormUserSettings();
    $this->notification_form = new FormUserNotifications();
    $this->password_form = new FormUserChangePassword();
    $this->delete_form = new FormUserDelete();

    switch($request->getParameter("view"))
    {
      case "none":
          break;

      case "general":
        if(true !== $this->settings_form->processAndValidate())
        {
          break;
        }
        $this->user->getConfig()->add($this->settings_form->getValues(), "settings");
        $this->user->privacy = (boolean) $this->settings_form['privacy']->getValue();
        $this->user->save();
        $this->session->setFlash('success_msg', 'Deine allgemeinen Einstellungen wurden geändert!');
      break;

      case "notifications":
        if(true !== $this->notification_form->processAndValidate())
        {
          break;
        }

        $results = $this->notification_form->getValues();
        $this->user->getConfig()->add($results, "settings/notification");
        $this->user->save();
        $this->session->setFlash('success_msg','Deine Benachrichtigungseinstellungen wurden übernommen.');
      break;

      case "password":
        if(true !== $this->password_form->processAndValidate())
        {
          break;
        }

        if(false === $this->user->checkPassword( $this->password_form['currentPassword']->getValue()))
        {
          $this->session->setFlash('error_msg', 'Das derzeitige Passwort war falsch.	');
          break;
        }

        $this->user->password = $this->password_form['newPassword']->getValue();
        $this->user->setSaltAndPassword();
        $this->user->save();

        $this->session->login( $this->user , $this->session->hasRememberKey() );
        $this->session->setFlash('success_msg','Dein Passwort wurde geändert!');
      break;


    case "delete":
          $this->delete_form->getWidgetSchema()->setLabels(array('bbcode' => 'Ich gehe weil:'));
          $this->setTemplate("selfDelete");

          if( true === $this->delete_form->processAndValidate() )
          {
            $this->getMailer()->sendEmail(
              sfConfig::get("app_mail_admin"),
              sprintf("[Deleted User]: The useraccount: %s was deleted on its own behalf.", $this->user->username),
              "REASON: ".$this->delete_form->getValue("bbcode"));

            $this->user->delete();

            $this->session->logout();
          }
      break;

    default:
      $this->forward404();
      break;
  }

    $defaults = array_merge($this->user->getConfig()->getAll("settings"), array("privacy" => (bool)$this->user->privacy));
    $this->settings_form->setDefaults($defaults);
    $this->notification_form->setDefaults($this->user->getConfig()->getAll("settings/notification"));
    return sfView::SUCCESS;
  }


  /**
   * Action for the user-centered-view
   */
  public function executeMyYigg($request)
  {
    $this->user = $this->session->getUser();

    $this->following = UserFollowingTable::getOnlineFollowedUsers($this->user->id);

   $query = Doctrine_Query::create()
      ->from("Story s")
      ->leftJoin("s.StoryTag st")
      ->where("st.tag_id IN (SELECT ut.tag_id FROM UserTag ut WHERE ut.user_id = ?)
      OR s.user_id IN (SELECT uf.following_id FROM UserFollowing uf WHERE uf.user_id = ?)
      OR s.domain_id IN (SELECT ud.domain_id FROM UserDomainSubscription ud WHERE ud.user_id = ?)
      ",
        array($this->user->id, $this->user->id, $this->user->id))
      ->addWhere("s.created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)")
      ->orderBy("s.created_at DESC");    $this->stories = $this->setPagerQuery($query)->execute();


    $this->storyCount = count($this->stories);
    return sfView::SUCCESS; 
  }
    
  /**
   * Action for the profile-settings
   */
  public function executeMyProfile($request)
  {
    $this->user = $this->session->getUser();
    $this->profile_form  = new FormUserEditProfile();

    switch ($request->getParameter('view'))
    {
      case "profil":
        if(true === $this->profile_form->processAndValidate()) 
        { 
          $validatedFile = $this->profile_form->getValue("avatar");
          if( !empty($validatedFile) && $validatedFile->getSize() > 0 )  
          {
            try 
            {
              $file = File::createFromValidatedFile( $validatedFile, "avatars","avatar-". $this->user->username );

            }
            catch(Exception $e)
            {
              $this->logMessage(sprintf("Adding/Changing Avatar failed for %s. Error: %s", $this->user->username, $e->getMessage()));
            }

            if( isset($file) && $file->id )
            {
              $this->user->createAvatar( $file );
            }
              $this->profile_form->offsetUnset("avatar"); 
          }

          $this->user->merge(
            array(
              "gender" => $this->profile_form->getValue("gender")
            )
          );

          if($this->profile_form->getValue("email") !== $this->user->email)
          {
            $this->user->getConfig()->set("email", $this->profile_form->getValue("email"), "new_email");
            $this->user->getConfig()->set("secret", sha1(uniqid("234hasjkdh8", true)), "new_email");

            $result = $this->getMailer()->sendEmail(
              $this->profile_form->getValue("email"), 
              '[YiGG] - Bitte bestätige Deine neue E-Mail-Adresse', 
              $this->getPartial("system/mailChangeEmailHtml", array("user" => $this->user))
            );
            $this->session->setFlash('note','Bestätige bitte den Link in der Mail, die wir geschickt haben, damit Deine neue E-Mail Adresse übernommen wird.');
          }

          $this->profile_form->batchUnsetOffsets(array("Tags", "email", "gender")); 
          //$this->user->getConfig()->add($this->profile_form->getValues(), "profile"); 
          $this->user->save();

          $this->session->setFlash('success_msg','Dein Profil wurde erfolgreich aktualisiert.');      
        }
      break;

      case 'none':
        break;

      default:
        return $this->forward404();
    }

    $this->profile_form->setDefaults(
     array_merge(
       $this->user->getConfig()->getAll("profile"),
       array(
        'birthday' => $this->user->getConfig()->has("birthday", "profile") ? strftime("%e.%m.%G", strtotime($this->user->getConfig()->get("birthday",null, "profile"))) : false,
        'email' => $this->user->email
       )
      )
    );

    return sfView::SUCCESS;
  }

  /**
   * Excutes the public profile shown on my yigg.
   * @param yiggWebRequest
   * @return sfView
   */
  public function executePublicProfile( $request )
  {
    $username = yiggTools::stringToUsername( $request->getParameter('username') );
    $this->user = Doctrine::getTable("User")->findOneByUsername($username);
    $this->forward404Unless( $this->user, sprintf( "user::publicprofile could not find the user %s", $username ));

    if( $this->user->privacy == 1)
    {
      if(true === $this->session->hasUser() )
      {
        $isFriend = $this->user->follows( $this->session->getUser() );
        if( (false == $isFriend) && ($this->user->id != $this->session->getUser()->id) && (false === $this->session->isAdmin()) )
        {
          $this->setTemplate('privacy');
          return sfView::SUCCESS;
        }
      }
      else
      {
        $this->setTemplate('privacy');
        return sfView::SUCCESS;
      }
    }

    // Sets a robots-meta-tag that prevents crawling of profiles of inactive users or users that choose to be private
    if( false === $this->user->isActive() || $this->user->privacy == 1)
    {
      //$this->getResponse()->addMeta('robots',  'noindex, nofollow' );
    }

    $sf = yiggStoryFinder::create()
         ->sortByDate();


    $sf->confineWithUsers($this->user);

    $this->stories = $this->setPagerQuery($sf->getQuery())->execute();

    $this->storyCount = count($this->stories);

    $this->followedUsers = Doctrine::getTable("UserFollowing")
                       ->getFollowedUsersQueryByUserId($this->user->id)
                       ->limit(16)
                       ->execute();

    $this->followingUsers = Doctrine::getTable("UserFollowing")
                   ->getFollowingUsersQueryByUserId($this->user->id)
                   ->limit(16)
                   ->execute();

    $request->setParameter("rss",true);

    $this->fb_friends = $request->getParameter("fb_friends");

    return sfView::SUCCESS;
  }

  /**
   *  Add / Remove from the friend list
   *
   * @param yiggWebRequest request
   * @return sfView
   *
   */
  public function executeModifyFriends( $request )
  {
    $friend_name = yiggTools::stringToUsername($request->getParameter("username"));
    if(empty($friend_name))
    {
      $this->session->setFlash("error_msg",'Dieser Benutzername ist leider nicht bekannt');
      return $this->redirect("@user_welcome");
    }

    $friend = Doctrine::getTable("User")->findOneByUsername($friend_name);

    if(false === $friend)
    {
      $this->session->setFlash("error_msg",'Dieser Benutzername ist leider nicht bekannt');
    }

    // I'm sure you are friends with yourself.
    $user = $this->session->getUser();
    if( $user->id === $friend->id )
    {
      $this->session->setFlash("success_msg",'Du kannst dich nicht selbst zu deiner Freundesliste zufügen.');
      $this->getResponse()->setStatusCode(406);
      return $this->redirect("@user_welcome");
    }

    switch( $request->getParameter("list") )
    {
      case "add":
        $following = new UserFollowing();
        $following->user_id = $user->id;
        $following->following_id = $friend->id;
        try{
          $following->save();
        }catch(Exception $e)
        {
          $this->session->setFlash("success_msg", 'Ihr seid immer noch Freunde ;)');
          break;
        }

        $this->session->setFlash("success_msg",'Dein Freund wurde erfolgreich zugefügt');
        break;
      case "remove":
        $query = Doctrine_Query::create()
                    ->select("id")
                    ->from("UserFollowing")
                    ->where("following_id = ?", $friend->id)
                    ->addWhere("user_id = ?", $user->id);

        $follower = $query->fetchOne();

        if($follower !== false)
        {
          $follower->delete();
        }

        $this->session->setFlash("error_msg", 'Dein Freund wurde erfolgreich gelöscht');
        break;
      default:
        $this->getResponse()->setStatusCode(401);
        return sfView::ERROR;
    }

    return $this->redirect("@user_welcome");
  }

    /**
     * This creates a new user and logs them in
     */
    public function executeFbRegister($request)
    {

        if( true === $this->session->hasUser() )
        {
            return $this->redirect("@user_welcome");
        }

        require_once sfConfig::get('sf_lib_dir') . '/vendor/facebook-php-sdk/src/facebook.php';

        $facebook = new Facebook(array(
            'appId' => sfConfig::get('app_facebook_app_id'),
            'secret' => sfConfig::get('app_facebook_secret'),
        ));

        $form_array = array();

        $facebook_user = $facebook->getUser();

        if ($facebook_user)
        {
            try
            {
                $facebook_user_profile = $facebook->api('/me');
                if(!is_null($facebook_user_profile['email']))
                {
                    $form_array['facebook_id'] = $facebook_user_profile['id'];
                    $form_array['email'] = $facebook_user_profile['email'];
                    $form_array['username'] = $facebook_user_profile['first_name'].$facebook_user_profile['last_name'];
                }
            }catch (FacebookApiException $e)
            {
                error_log($e);
                $facebook_user = null;
            }
        }

        $this->form = new FormUserFbRegister(null, $form_array);

        if( true === $this->form->processAndValidate() )
        {
            $ip_address = $request->getRemoteAddress();
            $connection = Doctrine::getConnectionByTableName("User");

            try
            {
                $this->user = new User();
                $this->user->fromArray($this->form->getValues());

                //create avatar
                $avatar_content = file_get_contents('http://graph.facebook.com/'.$facebook_user.'/picture');
                if ($avatar_content)
                {
                    $tmpfname = tempnam(sfConfig::get('sf_upload_dir'), "SL");
                    $handle = fopen($tmpfname, "w");
                    fwrite($handle, $avatar_content);
                    fclose($handle);

                    $validatedFile = new sfValidatedFile('image.jpeg', 'image/jpeg', $tmpfname, filesize($tmpfname));

                    if( !empty($validatedFile) && $validatedFile->getSize() > 0 )
                    {
                        try
                        {
                            $file = File::createFromValidatedFile( $validatedFile, "avatars","avatar-". $this->user->username );
                        }
                        catch(Exception $e )
                        {
                            $this->logMessage(sprintf("Adding/Changing Avatar failed for %s. Error: %s", $this->user->username, $e->getMessage()));
                        }


                        if( isset($file) && $file->id )
                        {
                            $this->user->createAvatar( $file );
                        }
                    }
                }
                $this->user->setStatus(1);
                $this->user->save();

                $this->getUser()->login($this->user);

                $this->fb_friends = true;

                return $this->redirect("@user_public_profile?view=livestream&fb_friends=1&username=".$this->user->username);

            }
            catch( Exception $e)
            {
                $this->logMessage("User::register, could not create user : " . $e->getMessage() . $e->getTraceAsString(), "CRIT");
                return sfView::ERROR;
            }

        }
        return sfView::SUCCESS;
    }

    public function executeFbLogin($request)
    {
        //Creating a new Facebook object from the Facebook PHP SDK
        require_once sfConfig::get('sf_lib_dir') . '/vendor/facebook-php-sdk/src/facebook.php';

        $facebook = new Facebook(array(
            'appId' => sfConfig::get('app_facebook_app_id'),
            'secret' => sfConfig::get('app_facebook_secret'),
        ));

        $facebook_user = $facebook->getUser();

        if ($facebook_user)
        {
            try
            {
                $facebook_user_profile = $facebook->api('/me');
                if(!is_null($facebook_user_profile['email']))
                {

                    $user_table = Doctrine::getTable("User");
                    $user_id = $user_table->emailExists($facebook_user_profile['email']);

                    if($user_id){
                        $user = $user_table->retrieveById($user_id);
                        $facebook_id = $user->getFacebookId();

                        if(!$facebook_id){

                            $user->setFacebookId($facebook_user_profile['id']);
                            //
                            $user->save();
                        }

                        $this->getUser()->login($user);
                        return $this->redirect("@best_stories");
                    }else{
                        return $this->redirect("@user_fb_register");
                    }
                }

            }catch (FacebookApiException $e)
            {
                error_log($e);
                $facebook_user = null;
            }
        }
    }

    public function executeFbAddFriends($request)
    {

        return sfView::SUCCESS;
    }

    public function executeLogin($request)
    {
    $this->getResponse()->setStatusCode(401, 'Forbidden');

    $this->form = new FormUserLogin();
    if( true === $this->form->processAndValidate() )
    {
      $user = Doctrine::getTable("User")->findOneByUsername($this->form->getValue("username"));
      if(false === $user)
      {
        $this->getUser()->setFlash("error_msg", "Benutzername oder Passwort falsch.");
        return sfView::SUCCESS;
      }

      if($user->status == 0)
      {
        $this->getUser()->setFlash("error_msg", "Dein Account wurde noch nicht freigeschaltet.");
        return sfView::SUCCESS;
      }

      if(false === $user->checkPassword($this->form->getValue("password")))
      {
        $this->getUser()->setFlash("error_msg", "Benutzername oder Passwort falsch.");
        return sfView::SUCCESS;
      }
      
      $this->getUser()->login($user, $this->form->getValue("remember"));


      if( false !== $request->getReferer() && false === strpos($request->getReferer(),"login") && '' !== $request->getReferer() )
      {
        return $this->redirect( $request->getReferer() );
      }

      return $this->redirect("@best_stories");
    }
    return sfView::SUCCESS;
  }

  public function executeLogout($request)
  {
    $this->session->logout();
    $this->redirect( $request->getReferer() ? $request->getReferer() : "@best_stories");
  }

  /**
   * Removes the Avatar from the selected user.
   */
  public function executeRemoveAvatar($request)
  {
     $user = $this->session->getUser();
     $user->createAvatar( null );
     $user->save();

     return $this->redirect($user->getEditProfileLink());
  }

  /**
   * Sends an email to users who have forgotten credentials..
   */
  public function executeResetPassword($request)
  {
    $this->form = new FormUserResetPassword();
    if(true === $this->form->processAndValidate() )
    {
      $this->user = UserTable::getTable()->findOneByUsername($this->form->getValue("username"));

      if( false === $this->user || false === ($this->user->email === $this->form->getValue("email") ))
      {
        $this->action_message = 'Benutzername und/oder E-Mail Adresse wurde nicht gefunden.';
        return sfView::SUCCESS;
      }

      $resetPasswordKey = ResetPasswordKey::create( $this->user );

      $vars = array(
        'ResetPasswordKey'  => $resetPasswordKey->reset_key,
        'ResetUser'         => $this->user
      );

      $partial = new sfPartialView( sfContext::getInstance(), "system","mailResetPasswordHtml", NULL);
      $partial->setPartialVars($vars);

      $result = $this->getMailer()->sendEmail(
        $this->user->email,
        '[YiGG] - Link zum zurücksetzen Deines vergessenen Passworts.',
        $partial->render()
      );

      if(false !== $result)
      {
        $this->session->setFlash("success_msg", "Wir haben dir einen Link zum zurücksetzen des Passworts gemailt.");
      }
    }
    return sfView::SUCCESS;
  }

  /**
   * Changes the users password, without being logged in, but requires a md5
   * key generated and sent to the oringal users email address, which is
   * unique based on time
   */
  public function executeChangePasswordWithKey( $request )
  {
    $responseMethod = $request->getMethod();
    $resetKey = $request->getParameter('ResetKey');
    $this->key = ResetPasswordKeyTable::findByKey( $resetKey );
    if(!empty($this->key) && !$this->key->isExpired() )
    {
      if( sfRequest::GET == $responseMethod )
      {
        $this->form = new FormUserResetPasswordWithKey();
        return sfView::SUCCESS;
      }
      else if( sfRequest::POST == $responseMethod )
      {
        $this->form = new FormUserResetPasswordWithKey();
        if(true===$this->form->processAndValidate())
        {
          $user = UserTable::getTable()->findOneById( $this->key->user_id );
          $user->password = $this->form->getValue("newPassword");
          $user->setSaltAndPassword();
          $user->save();

          $this->key->delete();
          $this->session->setFlash('msg:login','Dein Passwort wurde erfolgreich geändert. Du kannst Dich ab sofort damit anmelden.');
          return $this->redirect( '@user_login' );
        }
      }
    }
    return sfView::SUCCESS;
  }

  /**
   * This creates a new user and logs them in
   */
  public function executeRegister($request)
  {
    if( true === $this->session->hasUser() ) 
    {
      return $this->redirect("@user_welcome");
    }

    $this->form = new FormUserRegister();
    if( true === $this->form->processAndValidate() )
    {
      $ip_address = $request->getRemoteAddress();
      $connection = Doctrine::getConnectionByTableName("User");

      try
      {
        $this->user = new User();
        $this->user->fromArray($this->form->getValues());
        $this->user->save();
      }
      catch( Exception $e)
      {
        $this->logMessage("User::register, could not create user : " . $e->getMessage() . $e->getTraceAsString(), "CRIT");
        return sfView::ERROR;
      }

      $text = new sfPartialView( sfContext::getInstance(), 'user', '_mailConfirmEmailAccount', '');
      $key = Doctrine::getTable('AuthUserKey')->findOneByUserId($this->user->id);
      $text->setPartialVars( array("user" => $this->user, "key" => $key->user_key ));

      $this->result = 1 === $this->getMailer()->sendEmail($this->user->email,sprintf('Bestätigung Deiner Anmeldung bei YiGG',$this->user->username),$text->render(),"text/plain");
      if(false === $this->result)
      {
        $this->logMessage("User::register, could not send email for user : {$this->user->username}", "CRIT");
        return sfView::ERROR;
      }
    }

    return sfView::SUCCESS;
  }

  /**
   * Checks the key and enables the user.
   * They are required to login
   */
  public function executeAuthenticateUser( $request )
  {
    if(true === $this->session->hasUser())
    {
      return $this->redirect("@user_welcome", 301);
    }

    $key = yiggTools::parseString(($request->getParameter("key",false)));
    $authedKey = Doctrine::getTable("AuthUserKey")->findOneByUserKey($key);
    if( false !== $authedKey )
    {

      $authedKey->User->status = true;
      // so they can't keep reactivating the account, we remove their key.
      $authedKey->user_key = yiggTools::getDbDate(null,time());
      $authedKey->save();

      $this->session->setAttribute("referer", "@user_welcome");
      return $this->redirectLoginWithMessage("Glückwunsch! Dein Account bei YiGG wurde aktiviert. Du kannst Dich nun mit Deinem Benutzernamen und deinem Passwort anmelden.");
    }
    return sfView::ERROR;
  }

  public function executeTwitterUserProfile($request)
  {
  	$this->username = $request->getParameter("username");
  	
  	$this->tweets = Doctrine_Query::create()
  	                 ->from("Tweet")
  	                 ->where("username = ?", $this->username)
  	                 ->orderBy("id DESC")
  	                 ->limit(5)
  	                 ->execute();
  	if(count($this->tweets) === 0)
  	{
  		return $this->forward404();
  	}
  	
  	$query = Doctrine_Query::create()
  	                 ->from("Story s")
  	                 ->innerJoin("s.Tweets st")
  	                 ->where("st.username = ?", $this->username)
  	                 ->orderBy("s.id DESC");
                    
    $this->stories = $this->setPagerQuery($query)->execute();
  	return sfView::SUCCESS;
  }

  /**
   * removes a user.
   * @param $request yiggWebRequest
   * @return sfView
   */
  public function executeDelete($request)
  {
    $parameterHolder = $request->getParameterHolder();
    $username = $parameterHolder->get('username');

    $this->user = Doctrine::getTable("User")->findOneByUsername($username);
    $this->forward404Unless( $this->user, sprintf( "Sorry we could not find the user %s", $username ));

    $this->form = new FormUserDelete();
    $partial = new sfPartialView( sfContext::getInstance(), "user", "_deleteDefaultText", '');
    $partial->setPartialVars(array("user" => $this->user));

    $defaults = array(
      "bbcode" => $partial->render(),
     );

    $this->form->setDefaults($defaults);
    if( true === $this->form->processAndValidate() )
    {
      $this->user->delete();

      $this->result = 1 === $this->getMailer()->sendEmail(
        $this->user->email,
        sprintf('[YiGG-System] - Dein Account %s auf YiGG wurde gesperrt.', $this->user->username),
        $this->form['bbcode']->getValue()
      );
    }

    return sfView::SUCCESS;
  }

  /**
   * Verifies a changed e-mail adress
   *
   * @param $request An request
   */
  public function executeVerifyChangedEmail($request)
  {
    $user = Doctrine::getTable("User")->findOneByMclientSalt($request->getParameter("hash"));
    if(false === $user || false === $user->getConfig()->has("email", "new_email"))
    {
      return sfView::ERROR;
    }
    if($request->getParameter("secret") !== $user->getConfig()->get("secret", null, "new_email"))
    {
      $this->logMessage("Wrong secret provided for verifyChangedEmail: user #{$user->id}");
      return sfView::ERROR;
    }
    $user->email = $user->getConfig()->get("email", null, "new_email");
    $user->getConfig()->removeNamespace("new_email");
    $user->save();
    return $this->redirectLoginWithMessage("Dein Passort wurde erfolgreich geändert");
  }
}
