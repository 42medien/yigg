/**
 * @author titus
 */
NinjaBox = Class.create({

    boxNode: '',

  /**
   * create controller link for any div element with classname ninjaBox, uses title as link
   * title or if none is set
   */
    initialize: function(el)
    {
      this.boxNode = el;
      if ( ! this.boxNode.open )
      {
        var title = this.boxNode.title ?  this.boxNode.title : 'Anzeigen';
        var openLink = new Element('a', {'title': 'Öffnen', 'style':'cursor:pointer;'} ).update(title);

        Event.observe(openLink,'click',
          function() {
            this.open();
          }.bind(this)
        );

        Element.insert(this.boxNode, {'before': openLink});
        this.boxNode.open = openLink;

        // close on loading
        this.close();
       }
    },

    /**
   * open the box
   */
  open : function()
  {
    var closeLink = new Element('a', { 'class': 'closelink', 'style' : 'display:none;','title': 'Schließen' } ).update(
      new Element('img', {'src': 'images/cancel.png', 'alt': 'Schließen' })
    );

    Event.observe( closeLink, 'click',
      function(event){
        if( 0 === Effect.Queues.get('showbox').size()) {
          this.close();
          Event.stop(event);
        }
      }.bind(this)
    );

    if ( ! this.boxNode.close )
    {
      Element.insert(this.boxNode, {top: closeLink});
      this.boxNode.close = closeLink;
    }

    var main_effect = new Effect.Parallel (
       [
        new Effect.Fade ( this.boxNode.open ,  { sync: true } ),
        new Effect.BlindDown ( this.boxNode , { sync: true } ),
        new Effect.Appear ( this.boxNode.close ,  { sync: true } )
      ],
      {queue: {position:'end', scope: 'showbox', limit:2 } }
    );
  },

    /**
   * close the box
   */
  close: function()
  {
    var effect = new Effect.Parallel (
       [
        new Effect.Appear ( this.boxNode.open ,  { sync: true } ),
        new Effect.BlindUp ( this.boxNode , { sync: true } )
      ],
      {queue: {position:'end', scope: 'showbox', limit:2 } }
    );

    if( this.boxNode.close )
    {
      var other_effect = new Effect.Fade ( this.boxNode.close );
    }
  }

});