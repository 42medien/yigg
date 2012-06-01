<?php
class sponsoringActions extends yiggActions
{
  public function executeList($request)
  {
    $this->user = $this->session->getUser();
    $this->transactions = DealTable::getAllForUser( $this->user->id );
    return sfView::SUCCESS;
  }

  public function executeShowSponsoringPlace($request)
  {
    $place = Doctrine::getTable('SponsoringPlace')->find( (int) $request->getParameter('id'));
    return $this->redirect( $place->intern_url );
  }

  public function executeEdit($request)
  {
    $this->sponsoring = Doctrine::getTable("Sponsoring")->findOneById($request->getParameter("id"));
    $this->forward404Unless($this->sponsoring);
    $this->forward404Unless($this->sponsoring->Deal->user_id === $this->session->getUserId());
    $this->form = new EditSponsoringForm();
    $this->form->setDefaults($this->sponsoring->toArray());
    if(true === $this->form->processAndValidate())
    {
      if($this->form->getValue("image") instanceof sfValidatedFile)
      {
        $this->sponsoring->Image = Sponsoring::createImage(
          $this->form->getValue("image"),
          Doctrine::getTable('SponsoringPlace')->findOneById(
            $this->sponsoring->place_id
          )
        );
      }
      unset($this->form["image"]);

      $this->sponsoring->fromArray(
        array_merge(
          $this->sponsoring->toArray(),
          $this->form->getValues()
        )
      );
      $this->sponsoring->save();
      $this->session->setFlash("Sponsoring:notice", "Das Sponsoring wurde erfolgreich bearbeitet.");
      return $this->redirect("@sponsoring");
    }
  }

  /**
  *  Executes order action
  *
  *  @param sfRequest $request A request object
  */
  public function executeOrder( $request )
  {
    $user = $this->session->getUser();

    $place_id = (int) $request->getParameter('id', false);

    $this->places = Doctrine::getTable('SponsoringPlace')->findByDql("SELECT *  FROM SponsoringPlace sp INDEXBY sp.id WHERE capacity != 0");

    // only temporary till form submission.
    $this->place =  0 !== $place_id && array_key_exists($place_id, $this->places->toArray()) ? $this->places[$place_id] : $this->places[0];

    $this->form = new FormSponsoring(
      array(
        'place_id' => $this->place->id,
        'sponsor_url' => 'http://'
      ),
      array(
        'places'=> $this->places
      )
    );

    if(false === $this->place->isAvailable() )
    {
      $this->session->setFlash('Sponsoring:error', "Entschuldigung, dieser Werbeplatzt ist momentan nicht erhältlich");
      return sfView::SUCCESS;
    }

    if(true === $this->form->processAndValidate())
    {
      // process the image first.
      $validatedFile = $this->form->getValue("image");
      if( !empty($validatedFile) && $validatedFile->getSize() > 0 )
      {
        // create the image and thumbnail.
        $this->image = Sponsoring::createImage(
          $validatedFile,
          Doctrine::getTable('SponsoringPlace')->findOneById(
            $this->form->getValue("place_id")
          )
        );
      }
      elseif( !isset($this->image) && ($this->form->getValue("image_id") || $request->hasParameter("image_id")) )
      {
        // Load the image from the form
        $image_id =  $request->getParameter("image_id", $this->form->getValue("image_id"));
        if( $image_id !== null )
        {
          $this->image = Doctrine::getTable('File')->findOneById( $image_id  );
        }
      }

      if( !isset($this->image) || $this->image === false )
      {
        // set error message too
        $this->session->setFlash('Sponsoring:error', 'Fehler! Dieses Bild konnte nicht hochgeladen werden. Versuchen Sie es bitte nocheinmal.');
        return sfView::SUCCESS;
      }

      // Save the updated values for the preview. (encapsulate the image)
      $request->setParameter("FormSponsoring",
        array_merge(
          $request->getParameter("FormSponsoring"),
          array(
            "image_id" => $this->image->id,
          )
        )
      );

      // set the values for the hidden fields again.
      $this->form->processRequest();

      // show the normal form for editing.
      if( true ===  $this->hasRequestParameter("edit") )
      {
        return sfView::SUCCESS;
      }

      // show the preview  && make sure we show the preview unless we have an order.
      if( true === $this->hasRequestParameter("preview") )
      {
        return $this->forward('sponsoring', 'orderPreview');
      }

      $discount =  sfConfig::get("app_sponsoring_discount");

      // get the place they selected from the form!
      $this->place = Doctrine::getTable("SponsoringPlace")->findoneById($this->form->getValue("place_id"));
      $price = (float) round( $this->place->price * $this->form->getValue("weeks") * $discount[ $this->form->getValue("weeks") ], 2);

      // remove image because it's hitting an auto fucntion
      $values = $this->form->getValues();
      unset($values['image']);


      // should be created from the form.
      $sponsoring = Sponsoring::create(
        array_merge(
          $values,
          array(
            "deal_id" => Deal::create( array( "user_id" => $user->id, "debit" => $price ))->id,
            "image_id" => $this->image->id
          )
        )
      );

      // populates $this->paypal
      $this->setupPayPal();
      // URL to go to if the cancel payment
      $this->paypal->setCancelURL( $this->getController()->genUrl( $sponsoring->getPaypalCancelLink(), true) );

      // URL to verify or charge transaction
      $this->paypal->setReturnURL( $this->getController()->genUrl( $sponsoring->getPaypalSuccessLink(), true) );

      $this->paypal->setTransactionTotal( number_format( $price , 2) );
      $this->paypal->setTransactionDescription(  $sponsoring->getTransactionDescription() );

      // Get a Paypal URL to goto
      $goto = $this->paypal->GetExpressUrl();


      // Display the error page with the error message and log it.
      if( false === $goto )
      {
        $this->getLogger()->log("YSponsorings payment failed via paypal with message:" . $this->paypal->getErrorString(), 1 );
        return sfView::ERROR;
      }
      // redirect to paypal.
      $this->redirect( $goto );
    }

    return sfView::SUCCESS;
  }

  /**
   *  Executes order preview action
   *
   *  @param sfRequest $request A request object
   */
  public function executeOrderPreview($request)
  {
    $user = $this->session->getUser();

    if( false === $request->isMethod('post') )
    {
       $this->session->setFlash('Sponsoring:error','Sponsoring-Fehler: Bitte schicke dieses Formular noch einmal ab.');
       return sfView::ERROR;
    }

    // if they want to edit, or order, forward em on.
    if( true === $this->hasRequestParameter('edit') || true === $this->hasRequestParameter('order'))
    {
      return $this->forward('sponsoring', 'order');
    }

    // populate the form from the request (forwarded here pre-processed).
    $this->places = Doctrine::getTable('SponsoringPlace')->findAll();
    $this->form = new FormSponsoring( array(),array('places' => $this->places));
    $this->form->processRequest();
    $this->discounts = sfConfig::get("app_sponsoring_discount");

    // redirect em back if something is wrong.
    if( false === $this->form->isValid() )
    {
      return $this->forward('sponsoring', 'order');
    }

    // load up all the information.
    $this->image = Doctrine::getTable('File')->findOneById( $this->form->getValue("image_id") );
    $this->place = Doctrine::getTable('SponsoringPlace')->findOneById( $this->form->getvalue('place_id') );

    return sfView::SUCCESS;

  }

  /**
   *  Executes order complete action
   *
   *  @param sfRequest $request A request object
   */
  public function executeOrderComplete($request)
  {
    $this->sponsoring = Doctrine::getTable('Sponsoring')->findOneById( (int) $request->getParameter('id') );
    $this->forward404Unless( $this->sponsoring, "The sponsoring could not be found.");
    if( (int) $this->sponsoring->Deal->user_id !== (int)$this->session->getUserId() )
    {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

  /**
   *  Executes sponsoring click action
   *
   *  @param sfRequest $request A request object
   */
  public function executeHandleCall($request)
  {
    $sponsoring = Doctrine::getTable('Sponsoring')->find( (int) $request->getParameter('id') );
    if(false === $sponsoring->isActive())
    {
      return sfView::ERROR;
    }

    $sponsoring->clicks = $sponsoring->clicks + 1;
    $sponsoring->save();
    $this->redirect($sponsoring->sponsor_url , 301 );
  }

  /**
   *  Executes paypal canceĺ action
   *
   *  @param sfRequest $request A request object
   */
  public function executePayPalCancel($request)
  {
    // TODO: may set new request on paypal
    $this->setTemplate('paypalCancel');
  }

  /**
    *  Executes paypal success action
   *
   *  @param sfRequest $request A request object
   */
  public function executePayPalSuccess($request)
  {
    $user = $this->session->getUser();
    $this->setTemplate('paypal');

    // find the sponsoring from the response.
    $this->sponsoring = Doctrine::getTable('Sponsoring')->findOneById( (int) $request->getParameter('id') );
    if( false === $this->sponsoring  )
    {
      $this->getLogger()->log(sprintf("YSponsorings: Checkout Details were not returned by paypal for sponsoring with the id: %s and user %s", (int) $request->getParameter('id') , $user->id), 2);
      return sfView::ERROR;
    }

    // populates $this->paypal
    $this->setupPayPal();

    $discount =  sfConfig::get("app_sponsoring_discount");
    $weeks = $this->sponsoring->getWeeks();
    $price = (float) round( (int) $this->sponsoring->SponsoringPlace->price * $weeks * (float) $discount[ $weeks ], 2);

    $this->paypal->setTransactionTotal( $price );
    $this->paypal->setTransactionDescription( $this->sponsoring->getTransactionDescription() );

    // Get Detail Information from PayPal
    $details = $this->paypal->getExpressCheckoutDetails( $request->getParameter('token') );
    if( false === $details )
    {
      // log if there is/was an error.
      $msg = sprintf("YSponsorings: Checkout Details were not returned by paypal for sponsoring %s for user # %s", $this->sponsoring->id, $user->id);
      $this->getLogger()->log($msg, 2, sfLogger::ALERT);
      return sfView::ERROR;
    }

    // insert details into deal
    $deal = Doctrine::getTable('Deal')->findOneById( $this->sponsoring->Deal->id );
    if( false === $deal )
    {
      // log if there is/was an error.
      $msg = sprintf("YSponsorings: Sponsoring Deal could not be found in response from paypal for sponsoring %s for user # %s", $this->sponsoring->id, $user->id);
      $this->getLogger()->log($msg, sfLogger::ALERT);
      return sfView::ERROR;
    }

    // Enforce the deal for this sponsoring is correct.
    if( $deal->user_id !== $user->id )
    {
      // log if there is/was an error.
      $msg = sprintf("YSponsorings: Deal user missmatch for sponsoring # %s for user # %s and deal # %s", $this->sponsoring->id, $user->id, $deal->id );
      $this->getLogger()->log($msg, sfLogger::ALERT);
      return sfView::ERROR;
    }

    // update the deal information with the response from paypal.
    $deal->sponsoring_id = $this->sponsoring->id;
    $deal->savePayPalDetails( $details );

    // execute payment
    if( false === $this->paypal->chargeExpressCheckout( $request->getParameter('token') ) )
    {
      // log if there is/was an error.
      $msg = sprintf("YSponsorings: paypal->chargeExpressCheckout() for sponsoring %s for user # %s failed", $this->sponsoring->id, $user->id);
      $this->getLogger()->log($msg, 2);
      $this->getLogger()->log($this->paypal->getErrorString(),1);

      ob_start();
      var_dump( $this->sponsoring->toArray() );
      $this->getLogger()->log(ob_get_clean(), sfLogger::ALERT);
      return sfView::ERROR;
    }

    // setup deal
    $deal->setHasPayed();
    $deal->save();

    // set sponsoring to payed
    $this->sponsoring->setHasPayed();

    $partial = new sfPartialView( sfContext::getInstance(),'sponsoring','_mailNotifyAdminText', '');
    $partial->setPartialVars(
      array(
        'deal' => $deal,
        'backendLink' => $this->sponsoring->getEditLink()
      )
    );

    $this->getMailer()->sendEmail(
      sfConfig::get('app_mail_admin'),
      "Notify: Sponsoring gebucht",
      $partial->render(),
      "text/plain"
    );

    // send notify mail to user (who purchased the sponsorings);
    $partial = new sfPartialView( sfContext::getInstance(),'sponsoring','_mailBillingText', '');
    $partial->setPartialVars(
      array(
        'deal' => $deal
      )
    );

    $this->getMailer()->sendEmail(
        $user->email,
        "Sponsoring gebucht",
        $partial->render()
    );

    $this->redirect( $this->sponsoring->getOrderCompleteLink() );
  }



  private function setupPayPal()
  {
    // execute PayPal connection
    $this->paypal = new sfPaypalDirect( sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'PayPal'.DIRECTORY_SEPARATOR.'lib');
    // Setup Api's credentials
    $this->paypal->setUserName(  sfConfig::get('app_paypal_username') );
    $this->paypal->setPassword(  sfConfig::get('app_paypal_password') );
    $this->paypal->setSignature( sfConfig::get('app_paypal_signature') );
    $this->paypal->setTestMode(  sfConfig::get('app_paypal_testmode') );
  }
}
