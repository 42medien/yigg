/**
 * @author caffeine
 */
Spy = Class.create({

  spyInterval : 10,          // time between spy updates in seconds.
  animateInterval : 3,       // time between animations.

  updater: false,            // The spy updater
  animater:false,            // The animation animator

  timestamp: false,          // The current event timestamp
  activeStory : false,       // Do we have a story open
  loadingStory: false,       // Are we loading a story?
  toggleText : false,
  storyLimit: 50,            // Total number of stories allowed in the stack.

  stories: [],               // The current stories
  img_dir:(window.parent.document.location.host.match(/www\.yigg\.de$/)  ? window.parent.document.location.protocol + '//' + 'static.yigg.de/v6/' : '') + 'images/',
  ajax_img: 'ajaxindicator.gif',
  cancel_img: 'cancel.png',
  startText : "Fortsetzen",
  stopText : "Anhalten",

  /**
   * Loads all the spy javascript behaviours.
   */
  initialize: function(){
    if( true ===  $('NodeList').hasClassName("twit"))
    {
      this.storyLimit = 4;
    }

    if( !NinjaCommander.spy)
    {
      this.startUpdater();
      NinjaCommander.spy = true;
    }
    this.setupStories();
    Event.observe(document,'keypress',
      function(event)
      {
       this.keyPressHandler(event);
      }.bind(this)
   );
    this.toggleText = $('toggleAnimation');
    this.toggleText.href = 'javascript:void(0);';
    this.toggleText.innerHTML = this.stopText;

    Event.observe( this.toggleText,'click',
      function(event)
      {
        var el = event.element();
        if(el.stopped === true)
        {
          el.stopped = false;
          this.stopAnimator();
          this.stopUpdater();
          this.startAnimator();
          this.startUpdater();
          el.innerHTML = this.stopText;

        }else{
          el.stopped = true;
          el.innerHTML = this.startText;
          this.stopAnimator();
          this.stopUpdater();
        }
      }.bind(this)
   );
  },

  /**
   * Put some key handlers to monitor esacape.
   */
  keyPressHandler: function(event)
  {
    var key = event.which || event.keyCode;
    if(key === Event.KEY_ESC)
    {
      this.toggleText.stopped = true;
      this.toggleText.innerHTML = this.startText;
      this.stopAnimator();
      this.stopUpdater();
    }
  },

  /**
   * Applies behaviours to the node objects in the list.
   * Called on instansiation.
   */
  setupStories: function(){
    this.stories = [];
    $$('#NodeList li.spyNode').each(
      function(node){
        if( this.stories.size() === 0){
          this.timestamp = node.down('.node').id;
        }
        this.stories.push(node);
        this.prepareNode(node);
      }.bind(this)
   );
  },

  /**
   * stops the spy's updater and animator function
   * @param {Object} event the event from the filter form behaviour.
   */
  preUpdate: function(e)
  {
    if(this.updater)
    {
      this.updater.stop();
    }

    if(this.activeStory)
    {
      this.reanimate();
      this.activeStory = false;
    }
  },

  /**
   * start updater and animator after upadates
   */
  postUpdate: function()
  {
    this.setupStories();
  },

    /**
   * Adds behaviours to each story node
   * @param {Object} node
   */
  prepareNode: function(node){

    var link = node.down('a.storylink');
    if( undefined !== link && !link.storylink)
    {
      // change the links so no one can click the originals
      link.storylink = link.href;
      link.href = "javascript:void(0);";
      link.node = node;

      // observe the clicks
      this.openSpyStory.bindAsEventListener(this);
      Event.observe( link, "click",
        function(e){
          if( Effect.Queues.get('showstory').size() === 0){
            var el = e.element();
            if(!this.loadingStory){
              this.loadingStory = true;
              this.openSpyStory(el.storylink, el.node);
            }
          }else{
            return false;
          }

        }.bind(this)
     );
    }
  },

  /**
   * Spy Updater makes a request for new nodes.
   *
   */
  startUpdater : function(){
    // Stop any if there is one running
    this.stopUpdater();
    this.startAnimator();
    this.updater = new PeriodicalExecuter( function(pe){this.fetchNewNodes();}.bind(this), this.spyInterval);
  },

  /**
   * Stops the Spy Updater.
   */
  stopUpdater : function(){
    if(this.updater) {
      this.updater.stop();
      this.stopAnimator();
    }
  },

  /**
   * Creates the updater for firing animations.
   */
  startAnimator : function(){
    this.stopAnimator();
    this.animator = new PeriodicalExecuter(
      function(pe) {
        if(this.activeStory === false)
        {
          var node;
          var newNodes = $$('#NodeList li.new');
          if( newNodes.size() >= 0 && Effect.Queues.get('cyclestories').size() < 1)
          {
            while( newNodes.size() > 0 && Effect.Queues.get('cyclestories').size() === 0 && this.activeStory === false)
            {
              node =(true === newNodes.last().next().hasClassName('odd')) ? newNodes.pop() : newNodes.pop().addClassName('odd');
              this.stories.unshift( node);
              this.animateNode(node);
            }
          }
        }
      }.bind(this),
      this.animateInterval);
  },

  /**
   * Stops the animation process.
   */
  stopAnimator: function(){
    if(this.animator){
      this.animator.stop();
    }
  },

  /**
   * Opens a new spy story by making a ajax call, and animating
   *
   * @param {Object} link the url you wish to fetch
   * @param {Object} storyNode the node at which you wish to open.
   */
  openSpyStory : function( link, storynode){
    this.stopUpdater();
    this.fetchStory( link, this.createStoryNode( storynode));
  },

  /**
   * Closes the spy story. and starts the updater again.
   *
   */
  closeSpyStory: function(){
    this.reanimate();
    this.activeStory = false;
    if( this.spyInterval > 10)
    {
      this.spyInterval = 10;
    }
    this.startUpdater();
  },

  /**
   * Creates the story node, for ajax poplation
   * behaves like a static function
   * @param {Object} holder
   */
  createStoryNode : function(holder)
  {
    var story = holder.down(".story");
    if(!story)
    {
      story = new Element('div', {'class': 'story', style: 'display:none;' });
      holder.appendChild(story);
    }
    // remove content, for new insertion.
    story.update("");
    return story;
  },

  /**
   * Makes a request to see if there are any new nodes about from the latest timestamp
   *
   */
  fetchNewNodes : function(){
    if(($$('#NodeList li.new').size() < 5) &&(this.loadingStory === false))
    {
      if(!this.ajaxIndicator)
      {
        this.ajaxIndicator = new Element('img', { 'src': this.img_dir + this.ajax_img, 'alt': 'laden', 'id':'ajaxIndicator'});
        this.toggleText.parentNode.insert( this.ajaxIndicator, { position:top });
      }
      this.ajaxIndicator.show();
      if(!this.coms){ this.coms = new NinjaComs(); }
      this.coms.request(
          'spy', $('pageFilter').action + "/latestNodes",
          $H( {timestamp : this.timestamp}).merge( $('pageFilter').serialize({hash:true})),
          'POST',
          function(transport,json)
          {
            this.processNewNodes(transport,json);
            this.ajaxIndicator.hide();
          }.bind(this),
        true // window.location is absolute.
     );
    }
  },

  /**
   * Processes the new nodes returned by the spy filters.
   */
  processNewNodes: function( transport, json){
    if( transport.status != 304 && transport.status == 200)
    {
      var nodeList = $('NodeList');
      var newNodes = transport.responseText;

      nodeList.insert({ top : newNodes });
      nodeList.select('li.new').each(
        function(node){
          this.prepareNode( node);
        }.bind(this)
     );
      this.timestamp = nodeList.down('.node').id;
      if( this.spyInterval > 10)
      {
        this.spyInterval = 10;
        this.startUpdater();
      }
    }
    else if( transport.status == 304)
    {
      this.spyInterval =  this.spyInterval +(this.spyInterval * 0.2);
      this.startUpdater();
    }
  },

  /**
   * gets the story via an Ajax Updater
   * @param {Object} link
   * @param {Object} storyHolder
   */
  fetchStory : function( link, storyHolder)
  {

    // if there is a story open already, close it.
    if( this.activeStory !== false && storyHolder != this.activeStory)
    {
      this.reanimate();
    }

    // Pass success function for firing show and hide elements.
    if(!this.coms){ this.coms = new NinjaComs(); }

    this.activeStory = storyHolder;
    this.coms.updater( "spy", link, storyHolder, {}, 'get',
      function( el){
        this.animate();
        this.loadingStory = false;
      }.bind(this)
   );
  },

  /**
   * The success function for the ajax updater.
   * this makes sure we don't show anything we shouldn't before we have to.
   */
  animate : function(){

    var closeLink = new Element('a', { 'class': 'closelink','title': 'Schießen' }).update(
      new Element('img', {'src': this.img_dir + this.cancel_img, 'alt': 'Schließen' })
   );

    Event.observe( closeLink, 'click',
      function(e){
        if(Effect.Queues.get('showstory').size() === 0) {
          this.closeSpyStory();
          Event.stop(e);
        }
      }.bind(this)
   );

    this.activeStory.appendChild( closeLink);

    var effect = new Effect.Parallel(
       [
        new Effect.BlindUp( this.activeStory.parentNode.down(".node"), { sync: true }),
        new Effect.BlindDown( this.activeStory, { sync: true }),
        new Effect.Appear( closeLink,  { sync: true })
      ],
      {queue: {position:'end', scope: 'showstory', limit:2 } }
   );

    // Applies behaviour back onto the forms.
    NinjaCommander.initialize();
  },

  /**
   * Animates the closing of the element.
   */
  reanimate: function(){
    var effect = new Effect.Parallel(
      [
        new Effect.BlindUp( this.activeStory, {
          sync: true,
          afterFinishInternal: function(effect)
          {
            $(effect.element).remove();
          }
        }),
        new Effect.BlindDown(
          this.activeStory.parentNode.down(".node"),
          { sync: true }
        )
      ],
      {queue: {position:'front', scope: 'showstory', limit:2 } }
   );

  },

  /**
   * Animates the opening of a single node.
   * @param {Object} node
   */
  animateNode: function( nodeHolder)
  {
    var node = nodeHolder.down('.node');
    // add animation class(to ensure its not animated twice.
    node.addClassName('animating');
    nodeHolder.addClassName('animating');

    // remove new class.
    node.removeClassName('new');
    nodeHolder.removeClassName('new');

    var oldNode = this.stories.pop().addClassName('old');

    var effect = new Effect.Parallel(
     [
      new Effect.BlindDown( nodeHolder, { sync: true }),
      new Effect.BlindDown( nodeHolder.down('.node'), { sync: true }),
      new Effect.BlindUp( oldNode, { sync: true }),
      new Effect.BlindUp( oldNode.down('.node'), { sync: true })
     ],
      {
        queue: {position:'end', scope: 'cyclestories', limit:1 },
        afterFinish: function(animation)
        {
          // clean up classes.
          var node = animation.effects[0].element;
          $(node).removeClassName("animating");
          var nodeHolder = animation.effects[1].element;
          $(nodeHolder).removeClassName("animating");
          // remove old node
          var oldNode = animation.effects[2].element;
          oldNode.remove();
        }
      }
   );
  },

  /**
   * Reanimates the removal of a single node.
   * @param {Object} node
   */
  reanimateNode: function( nodeHolder){
   var effect = new Effect.Parallel(
      [
        new Effect.BlindUp( nodeHolder, { sync: true }),
        new Effect.BlindUp( nodeHolder.down('.node'), { sync: true })
      ],
      { queue: {position:'back', scope: 'deletestories', limit:2 }}
   );
  }
});
document.fire("Spy:loaded",{"updater" : new Spy()});