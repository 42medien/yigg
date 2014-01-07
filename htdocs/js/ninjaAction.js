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

      if (!el.value) {
        el.value = json.value;
      }

    } else if( json ) {
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
    } else if( json ) {
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
      el.update(json.content);
    } else if ( json ) {
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
    } else if( json ) {

      NinjaValidator.throwError("An Error was returned: " +  json.error + ", but there was no element found");
    }
  }
});
