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
        this.target.appear({
          duration: 3.0
        });
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