/**
 * Ninjitsu Form library.
 * @author Simon Gow. http://simongow.com
 * @copyright YiGG.de
 * packed by http://dean.edwards.name/packer/
 * Ninjitsu 1.8
 */
/**
 * NinjaCommander:
 * Handles and sets up all the coms' whilst out in the field.
 * Delegates communications to NinjaComs'
 * pageActions to ninjaAction
 * and Form battles to ninjaForm.
 */

NinjaCommander = Class.create({

    /**
     * Loads all the javascript behaviours.
     */
    initialize: function(){
        this.startForms();
        this.startUpdaters();
        this.startActions();
    },

    /**
     * Creates behaviours for the actions like storyclick.
     */
    startActions: function(){
        // Setup the ninjaActions
        $$('a.ninjaAction').each(function(el)
        {
          if (!el.id)
          {
            el.identify();
          }

          // do not reassign
          if (el.ninjaAction)
          {
              return;
          }

          el.ninjaAction = NinjaAction;

          // Add event listeners for ninja Actions
          el.observe("click",
            function(e)
            {
              this.ninjaAction.executeAction(e);
              Event.stop(e);
            }.bind(el)
          );
        });
    },
    /**
     * Adds behaviour for ninjaForms.
     */
    startForms: function(){

        // Setup the ninjaValidation
        $$('form.ninjaForm').each(
          function(el)
          {
            if (el.id && typeof el.ninjaValidator != 'object')
            {
                // Add the validator to the DOM element for use of this in context.
                el.ninjaValidator = NinjaValidator;
                el.ninjaValidator.formName = el.id;
                token = $(el.id + '__csrf_token');
                if (token) {
                    el.ninjaValidator.csrfToken = $F(token);
                }

                // Setup the event listeners for required attributes
                el.requiredElements = $$("#" + el.id + ' .ninjaRequired');
                if (el.requiredElements) {
                    el.requiredElements.invoke("observe", "blur", function(e){
                        this.ninjaValidator.requiredCheck(e);
                    }.bind(el));
                }

                // Setup the event listeners for validate attributes
                el.validationElements = $$("#" + el.id + ' .ninjaValidate');
                if (el.validationElements) {
                    el.validationElements.invoke("observe", "blur", function(e){
                        this.ninjaValidator.validateField(e);
                    }.bind(el));
                }

                // Setup the event listeners for form submit element.
                el.observe("submit", function(e)
                {
                  if(typeof tinyMCE === "object")
                  {
                    tinyMCE.triggerSave();
                  }
                  this.ninjaValidator.preSubmitCheck(e);
                }.bind(el));
            }
            el.enable();
        });
    },

    /**
     * create updater for any element (a,form) with classname ninjaUpdater
     * may create link updaters, form updaters and filters (which needs additional
     * classname ninjaFilter).
     *
     * also there might be some options like ninjaConfirm (shows confirm-alert before action)
     * or ninjaCallback[Xyz] to act as callback, this is loaded from /js/ninjaCallback[Xyz].js
     * automatically, the target for the callback is set by adding the target id as
     * classname to the updater element, otherwise element will be replaced by result
     *
     * example: <form action="ajax/me" class="ninjaUpdater ninjaForm ninjaFilter targetDivId">
     *       <link href="ajax/me" class="ninjaUpdater ninjaConfirm targetDivId">update</a>
     *       <form action="ajax/me" class="ninjaUpdater ninjaForm targetDivId">
     */
    startUpdaters: function(){
        $$(".ninjaUpdater").each(function(el){
            if (!el.ninjaUpdater) {
                el.ninjaUpdater = new NinjaUpdater(el);
            }
        });
    }
});

/**
 * @author caffeine
 */

/**
 * NinjaComs:
 * This class handles all the communication between the client and the server.
 * It also translates responses and executes javascript based on the response.
 *
 */
NinjaComs = Class.create({

  debug : 0,


  /**
   * Make a Ajax Request
   * @param {String} callMethod - The URL you wish to submit too.
   * @param {Object} param - The parameters you want to submit
   */
  request : function( connectionToken, callMethod, param, method, onCompleteFunction, absoluteURL ){

    var url = callMethod;
    if(!absoluteURL)
    {
      url = window.location.toString();
      if( url.indexOf("?") > 0)
      {
        var getparams = url.substring( url.indexOf("?"), url.length);
        url = url.substring( 0, url.indexOf("?"));
        url = url.substring( url.length -1 , url.length ) == "/" ?  url + callMethod : url + '/' + callMethod  + getparams;
      }
      else
      {
        url = url.substring( url.length -1 , url.length ) == "/" ?  url + callMethod : url + '/' + callMethod;
      }
    }

    var request = new Ajax.Request(
      url,
      {
        parameters: param,
        onComplete: onCompleteFunction,
        onSuccess: this.handleResponse.bind(this),
        onFailure: this.handleResponse.bind(this),
        evalJS: false,
        sanitizeJSON: true,
        requestHeaders: {
            "Content-Token": connectionToken
        }
      }
    );

  },

  updater : function( connectionToken, callMethod, element, param , methodType, onCompleteFunction )
  {
    if(!methodType)
    {
      methodType = 'post';
    }

    var updater =  new Ajax.Updater(
      element,
      callMethod,
      {
        parameters : param,
        onComplete: onCompleteFunction,
        method: methodType,
        sanitizeJSON: true,
        requestHeaders: {
          "Content-Token": connectionToken
        }
      }
    );
  },

  /**
   * Sorts out what to do with valid responses from our server.
   *
   * @param {Object} transport
   * @param {Object} json
   */
  handleResponse: function( transport, json)
  {
    // Make sure we recieved a valid response from the server.
    if( 304 == transport.status )
    {
      return;
    }
    else if( 200 == transport.status )
    {
      try
      {
        if(json)
        {
          json.each(
            function(el)
            {
               // get the class and action from the repsonse
              var className = el.className;
              var action = el.action;

              // Check to make sure it's a valid class.
              if( false === Object.isUndefined( className ))
              {
                // compile the class and action handler from the response.
                var call = eval( className + "." + action );
                if( true === Object.isFunction( call )  )
                {
                  call( el );
                }
              }
            }
          );
        }
      // We haven't got a correct response from the server.
      } catch( exception )
      {
        // removed the ugly errors for now.
      }

    // Other HTTP code recived, display error.
    }else{
      this.throwComsError( "Server Response Error: "+ transport.statusText + " Server Response incorrect" , true);
    }
  },

  /**
   * Throws a communication exception rendered as a h3 error message appended to the document.
   *
   * @param {String} message to display
   * @param {Object} fatalError - force display of errors.
   */
  throwComsError: function ( message, fatalError){
  }
});

/**
 * Initalized on the page load, this class applies itself to each form,
 * and searches for any required or elements which require validation.
 *
 * it then posts via JSON to the URI : /form.URI + / check{fieldname}
 * and awaits response. This repsonse is automatically appended to
 * the required message, or if the message doesn't exist, the field
 * is assumed to be in order.
 *
 * You need to add the class names "ninjaRequired" or "ninjaValidate"
 * to your fields to be added to the Event execution list.
 *
 * All fields must have the same ID and name
 */
NinjaValidator = ({

  // Some configuration
  requiredText : ' wird benötigt.',
  img_dir: (window.parent.document.location.host.match(/yigg\.de$/)  ? window.parent.document.location.protocol + '//' + 'yigg.de/' : '') + 'images/',
  validating_img: 'ajaxindicator.gif',
  errors: 0,
  target: null,


  /**
   * Checks to see if each required element has any content using
   * prototypes $F helper function
   * @param {Object} el - the event (if triggered from the form execution)
   * or element in question.
   */
  requiredCheck : function(el){
    if(el.type == "blur"){
      el = Event.element(el);
    }
    var currentParent = el.parentNode;
    this.resetFieldHolder(currentParent);

    if( $F(el) ){

      this.addValidatedSpan(el,currentParent);
      return true;

    }else{

      this.addMessageSpan(el,currentParent);
      return false;
    }
  },

  /**
   * Submits a request to the server to see if this field has valid content.
   *
   * @param {Object} el - the event (if triggered from the form execution)
   * or element in question.
   */
  validateField : function(el){

    // Grab the element if this is triggered from an event
    if(el.type == "blur"){
      el = Event.element(el);
    }

    // Reset the holder.
    var currentParent = el.parentNode;
    this.resetFieldHolder(currentParent);

    // Check the value / if it's optional
    var value = $F(el);
    var optional = el.hasClassName("optional");

    if(value && !this.isSubmitting)
    {
      this.addValidatingMsg(currentParent);

      var param = {};
      param[el.id] = value;

      if( !this.ninjaComs ){
        this.ninjaComs = new NinjaComs();
      }

      this.ninjaComs.request( this.csrfToken, "check" + el.id.capitalize(), param );

    }else if(optional || (value && this.isSubmitting) ){

      el.removeClassName("validated");
      el.removeClassName("error");

    }else{
      this.addMessageSpan(el,currentParent);
      return false;
    }
  },

  /**
   * Checks all the fields in the form before submission.
   *
   * @param {Object} e
   */
  preSubmitCheck : function(e)
  {
    this.isSubmitting = true;
    this.errors = 0;
    var el = Event.element(e);
    this.target = false;

    // process form validation
    if( false === this.doFormValidationCheck(el))
    {
      Event.stop(e);
      return;
    }

    // submission has no errors, so continue
    if( true === el.hasClassName('ninjaAjaxSubmit') )
    {
      var params = $(el).serialize(true);
      el.disable();

      if( !this.ninjaComs )
      {
        this.ninjaComs = new NinjaComs();
      }

      // see if we have a extra target.
      $w( $(el).className ).each(
        function( className )
        {
           if( !className.startsWith('ninja') && $(className) )
           {
             this.target = $(className);
           }
        }.bind(this)
      );

      var updater =  new Ajax.Updater(
        this.target ? this.target : $(el),
        $(el).action,
        {
          parameters : params,
          onComplete: function(transport)
          {
            NinjaCommander.initialize();
          },
          onFailure: function(transport)
          {
            var response = transport.responseText;
            $(el).replace(response);
            NinjaCommander.initialize();
          }.bind(el),
          method: 'post',
          sanitizeJSON: true,
          requestHeaders: {
            "Content-Token": this.csrfToken
          }
        }
      );
      Event.stop(e);
    }
    else
    {
      // for non ajaxyupdate forms.
      this.isSubmitting = false;
    }
  },

  /**
   * Only checks the required elements, as the form is submitting and will be
   * checked anyway.
   */
  doFormValidationCheck: function(el)
  {
    el.requiredElements.each(
      function(el){
        var hasValue = this.requiredCheck(el);
        if(!hasValue){
          if(el.hasClassName("error")){
            this.errors++;
            Effect.Shake(el);
          }
        }
      }.bind(this)
    );
    return this.errors === 0 ? true : false;
  },

  /**
   * Adds a validation message unobtrusively
   *
   * @param {Object} el
   * @param {Object} parentNode
   */
  addValidatedSpan: function(el,parentNode){

    el.removeClassName("error");
    el.addClassName("validated");
    this.resetFieldHolder(parentNode);
    var validatedspan  = new Element('span', { 'class' : 'validated_message' } );
    parentNode.removeClassName('field_error');
    parentNode.addClassName('field_validated');
    parentNode.appendChild( validatedspan );

  },
  /**
   * Adds the validation image to show the page is doing 'something'
   *
   * @param {Object} parentNode
   */
  addValidatingMsg : function(parentNode){
    this.resetFieldHolder(parentNode);
    var img = new Element(
      "img",
      {
        'src' : this.img_dir + this.validating_img,
        'alt' : "Überprüfung läuft.",
        'class' : 'validating'

      }
    );
    parentNode.appendChild(img);
  },

  /**
   * add a message to the element and holders.
   *
   * @param {Object} el
   * @param {Object} parentNode
   * @param {Object} message
   */
  addMessageSpan: function(el,parentNode,message){

    if(!message){
      message = NinjaValidator.unCamelize(el.id).capitalize() + this.requiredText;
    }
    el.removeClassName("validated");
    el.addClassName("error");
    this.resetFieldHolder(parentNode);

    var errorspan = new Element('span',
      {
        'class' : 'error_message',
        'id' : 'error_for_' + el.id
      }
    );
    errorspan.update(message);

    parentNode.removeClassName('field_validated');
    parentNode.addClassName('field_error');
    parentNode.appendChild( errorspan );
  },

  /**
   * Remove all the styling and attributes from an element and holder.
   *
   * @param {Object} parentNode
   */
  resetFieldHolder: function(parentNode){
    var currentErrors = $(parentNode).getElementsBySelector('.error_message','.validated_message','.validating').invoke("remove");
  },

  /**
   * Updates a ninjaForm based on the response from the server.
   *
   * @param {Object} json
   */
  updateForm : function (json){

    var el = $( json.elementId );
    if( true === Object.isElement(el) ){

      if( json.type == "error" ){
        NinjaValidator.addMessageSpan(el, el.parentNode, json.error);

      }else{
        NinjaValidator.addValidatedSpan(el,el.parentNode);
      }

    }else if( json ){

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }

  },

  unCamelize: function(value) {
    return value.replace(
      /[a-zA-Z][A-Z0-9]|[0-9][a-zA-Z]/g,
      function(match) {
        return match.charAt(0) + ' ' + match.charAt(1);
       }
     );
   }
});

/**
 * @author titus, caffeine
 * Automated callback class handling fired from ninjaforms on steroids.
 */

NinjaUpdater = Class.create({

  type:         '',
  action:       '',
  callback:     '',
  element:      '',
  target:       '',

  in_process:   false,
  current_event:    '',

  // set script file settings
  script_dir: (window.parent.document.location.host.match(/www\.yigg\.de$/)  ? window.parent.document.location.protocol + '//' + 'yigg.de/' : '')  + 'js/',
  script_suffix: '.js',
  script_prefix: 'ninjaCallback',
  script_replace:'ninjaCallback',

   // Sets up the actions for this class.
  initialize: function(el)
  {
    // set id if not set so far
    el.identify();

    // transfer element
    this.element = el;

    // set target
    this.initializeTarget();

    // setup actions
    this.initializeAction();

    this.coms = new NinjaComs();
  },

  // figures out what to update, and adds callbacks
  initializeTarget: function()
  {
    $w( this.element.className ).each(
      function( className )
      {
        // find and apend all callbacks.
        if ( className.startsWith('ninjaCallback') )
        {
          var script = new Element('script',
            {
              'id': 'script_' + className,
              'type': 'text/javascript',
              'src': this.script_dir + className.replace('ninjaCallback', this.script_prefix) + this.script_suffix
            }
          );

          className = className.replace( this.script_replace, '');

          document.observe( className + ":loaded",
            function(e){
              this.callback = e.memo.updater;
            }.bind(this)
          );

          // insert script after last script in the source
          Element.insert( $A( document.getElementsByTagName('script')).last(), { 'after': script });
        }


        // ignore all ninja... classes as target
        if(className.startsWith('ninja'))
        {
          return;
        }

        // if classname is found as element id, set as target
        if( $(className) )
        {
          this.target = $(className);
        }
      }.bind(this)
    );

    // set default target because none was provided (so current obj will be replaced itself)
    if( ! this.target )
    {
      this.target = this.element;
    }
  },

  // figures out what the updater should do to get the response
  initializeAction: function()
  {
    // link updater
    if( this.element.hasAttribute('href') )
    {
      // set type
      this.type   = 'LINK';

      // set action observer
      this.action = this.element.href;
      this.element.href = 'javascript:void(0);';
      this.initializeObserver('click', 'GET');
      return;
    }

    // terminate if not a form (only forms and links might be an updater)
    if( ! this.element.hasAttribute('action') )
    {
      return;
    }

    if ( this.element.hasClassName('ninjaFilter') )
    {
      // set type
      this.type       = 'FILTER';
      observer     = 'change';
      // form updater
    }
    else
    {
      // set type
      this.type       = 'FORM';
      observer      = 'submit';
    }

    // set action as formaction.
    this.action = this.element.action;

    // set action observer
    this.initializeObserver(observer, 'POST');
  },

  // applies the observers for firing the action
  initializeObserver: function( type, method )
  {
    // set action observer
    Event.observe( this.element, type,
      function(e)
      {
        this.current_event = e;
        Event.stop(e);

        // if confirm is requested check for
        if( this.element.hasClassName('ninjaConfirm'))
        {
          if( false === confirm('Sicher?'))
          {
            return;
          }
        }
        var content = ( method == 'POST') ?  this.element.serialize(true) : '';
        return this.executeEvent( method, content );
      }.bind(this)
    );
  },

  // Executes the updaters.
  executeEvent: function(method, content, event)
  {
    // update is currently in progress, so ignore event until current update is finished
    if(true === this.in_process )
    {
      return;
    }

    // if callback has own update handler, use this (overrides local handler)
    if( this.callback && Object.isFunction(this.callback.executeUpdate) )
    {
      this.callback.executeUpdate(this);
      return;
    }

    if( false === this.preUpdate() )
    {
      return;
    }

    this.target.hide();

    // process ajax update
    this.coms.updater( this.element.id, this.action, this.target, content, method,
      function()
      {
        this.target.show();
        this.postUpdate();
        this.target.appear({duration:3.0});
      }.bind(this)
    );
  },

  // executes callbacks or does the default callback before the update.
  preUpdate: function()
  {
    // check if form validation is available
    if( this.element.ninjaValidator )
    {
      if( false === this.element.ninjaValidator.doFormValidationCheck(this.element) )
      {
        return false;
      }
    }

    // prepare update or skip if preUpdate returns "false"
    if( this.callback && Object.isFunction(this.callback.preUpdate) && false ===  this.callback.preUpdate(this) )
    {
      return false;
    }

    // create lock
    this.in_process = true;

    // show loading instead of content
    if (this.element.hasClassName('ninjaPreloader'))
    {
      this.target.update('loading');
    }

    // disable filter elements
    if( this.type == 'FILTER' )
    {
      this.element.disable();
    }
  },

  // executes the callbacks or does the defautl after the update.
  postUpdate: function()
  {
    // process callback
    if( this.callback && Object.isFunction(this.callback.postUpdate) )
    {
      this.callback.postUpdate(this);
    }

    // release lock
    this.in_process = false;

    // disable filter elements
    if( this.type == 'FILTER' )
    {
      this.element.enable();
    }
    // reinit ninjaCommander
    NinjaCommander.initialize();
  }
});


/**
 * @author titus, caffeine
 * Automated callback class handling fired from ninjaforms on steroids.
 */
NinjaUpdater = Class.create({

  type:         '',
  action:       '',
  callback:     '',
  element:      '',
  target:       '',

  in_process:   false,
  current_event:    '',

  // set script file settings
  script_dir: (window.parent.document.location.host.match(/www\.yigg\.de$/)  ? window.parent.document.location.protocol + '//' + 'yigg.de/' : '')  + 'js/',
  script_suffix: '.js',
  script_prefix: 'ninjaCallback',
  script_replace:'ninjaCallback',

   // Sets up the actions for this class.
  initialize: function(el)
  {
    // set id if not set so far
    el.identify();

    // transfer element
    this.element = el;

    // set target
    this.initializeTarget();

    // setup actions
    this.initializeAction();

    this.coms = new NinjaComs();
  },

  // figures out what to update, and adds callbacks
  initializeTarget: function()
  {
    $w( this.element.className ).each(
      function( className )
      {
        // find and apend all callbacks.
        if ( className.startsWith('ninjaCallback') )
        {
          var script = new Element('script',
            {
              'id': 'script_' + className,
              'type': 'text/javascript',
              'src': this.script_dir + className.replace('ninjaCallback', this.script_prefix) + this.script_suffix
            }
          );

          className = className.replace( this.script_replace, '');

          document.observe( className + ":loaded",
            function(e){
              this.callback = e.memo.updater;
            }.bind(this)
          );

          // insert script after last script in the source
          Element.insert( $A( document.getElementsByTagName('script')).last(), { 'after': script });
        }


        // ignore all ninja... classes as target
        if(className.startsWith('ninja'))
        {
          return;
        }

        // if classname is found as element id, set as target
        if( $(className) )
        {
          this.target = $(className);
        }
      }.bind(this)
    );

    // set default target because none was provided (so current obj will be replaced itself)
    if( ! this.target )
    {
      this.target = this.element;
    }
  },

  // figures out what the updater should do to get the response
  initializeAction: function()
  {
    // link updater
    if( this.element.hasAttribute('href') )
    {
      // set type
      this.type   = 'LINK';

      // set action observer
      this.action = this.element.href;
      this.element.href = 'javascript:void(0);';
      this.initializeObserver('click', 'GET');
      return;
    }

    // terminate if not a form (only forms and links might be an updater)
    if( ! this.element.hasAttribute('action') )
    {
      return;
    }

    if ( this.element.hasClassName('ninjaFilter') )
    {
      // set type
      this.type       = 'FILTER';
      observer     = 'change';
      // form updater
    }
    else
    {
      // set type
      this.type       = 'FORM';
      observer      = 'submit';
    }

    // set action as formaction.
    this.action = this.element.action;

    // set action observer
    this.initializeObserver(observer, 'POST');
  },

  // applies the observers for firing the action
  initializeObserver: function( type, method )
  {
    // set action observer
    Event.observe( this.element, type,
      function(e)
      {
        this.current_event = e;
        Event.stop(e);

        // if confirm is requested check for
        if( this.element.hasClassName('ninjaConfirm'))
        {
          if( false === confirm('Sicher?'))
          {
            return;
          }
        }
        var content = ( method == 'POST') ?  this.element.serialize(true) : '';
        return this.executeEvent( method, content );
      }.bind(this)
    );
  },

  // Executes the updaters.
  executeEvent: function(method, content, event)
  {
    // update is currently in progress, so ignore event until current update is finished
    if(true === this.in_process )
    {
      return;
    }

    // if callback has own update handler, use this (overrides local handler)
    if( this.callback && Object.isFunction(this.callback.executeUpdate) )
    {
      this.callback.executeUpdate(this);
      return;
    }

    if( false === this.preUpdate() )
    {
      return;
    }

    // process ajax update
    this.coms.updater( this.element.id, this.action, this.target, content, method,
      function()
      {
        this.target.show();
        this.postUpdate();
      }.bind(this)
    );
  },

  // executes callbacks or does the default callback before the update.
  preUpdate: function()
  {
    // check if form validation is available
    if( this.element.ninjaValidator )
    {
      if( false === this.element.ninjaValidator.doFormValidationCheck(this.element) )
      {
        return false;
      }
    }

    // prepare update or skip if preUpdate returns "false"
    if( this.callback && Object.isFunction(this.callback.preUpdate) && false ===  this.callback.preUpdate(this) )
    {
      return false;
    }

    // create lock
    this.in_process = true;

    // show loading instead of content
    if (this.element.hasClassName('ninjaPreloader'))
    {
      this.target.update('loading');
    }

    // disable filter elements
    if( this.type == 'FILTER' )
    {
      this.element.disable();
    }
  },

  // executes the callbacks or does the defautl after the update.
  postUpdate: function()
  {
    // process callback
    if( this.callback && Object.isFunction(this.callback.postUpdate) )
    {
      this.callback.postUpdate(this);
    }

    // release lock
    this.in_process = false;

    // disable filter elements
    if( this.type == 'FILTER' )
    {
      this.element.enable();
    }
    // reinit ninjaCommander
    NinjaCommander.initialize();
  }
});

/**
 * @author caffeine
 * A quick hash for making and sending requests from small ninjaAction classes. (not forms)
 */
NinjaAction = ({

  executeAction: function(e){
    el = Event.element(e);

    var url =  NinjaValidator.unCamelize( el.id );
    url = url.replace(" ","/");
    if( !this.ninjaComs ){
      this.ninjaComs = new NinjaComs();
    }
    // Strictly we should have a different connection token..
    this.ninjaComs.request( url , url , { 'elementId' : el.id });
  },

  redirect : function(transport,json){
    //window.location = ( el.href );
    console.log('redirecting to '+  el.href);
    console.log('this really should get javascript back to know what to do ;)');
  },


  /**
   * Updates a field based on the response from the server.
   *
   * @param {Object} json
   */
  updateField : function (json){

    var el = $( json.elementId );
    if( true === Object.isElement(el) ){
      el.enable();
      el.value = json.value;

    }else if( json ){

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }
  },

  /**
   * Disables the form field.
   *
   * @param {Object} json Ninja Object
   */
  disableField : function (json){

    var el = $( json.elementId );
    if( true === Object.isElement(el) ){
      el.disable();
    }else if( json ){

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }
  },

  /**
   * Replaces the content of an element
   *
   * @param {Object} json Ninja Object
   */
  replaceContent: function (json)
  {
    var el = $( json.elementId );
    if( true === Object.isElement(el) ){
      el.enable();
      el.update(json.content);

    }else if( json ){

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }
  },

  /**
   * Removes the element in the predefined ninja Json object structure
   *
   * @param {Object} json Ninja Object
   */
  removeElement : function(json){

    var el = $( json.elementId );
    if( true === Object.isElement(el) ){
      Element.remove(el);
    }else if( json ){

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }
  }
});

Event.observe(window,'load',function(){ NinjaCommander=new NinjaCommander(); });