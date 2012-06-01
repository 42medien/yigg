/**
 * @author titus
 */
Friends = Class.create({
  // updater target
  target:     'listContent',

  /**
   * create ajax communicator for upadate
   */
  initialize: function()
  {
    this.coms = new NinjaComs();
  },

  /**
   * updates the content area "listContent" with resultlist
   * @param {Object} el
   */
  postUpdate: function( el )
  {
    content = '';
    method = 'GET';

    // process ajax update
    this.coms.updater(
      el.element.id, window.location.toString() , $(this.target), content, method,
        function(){
          NinjaCommander.initialize();
          el.element.reset();
        }.bind(el)
    );
  }
});
document.fire("Friends:loaded",{ 'updater' : new Friends() });