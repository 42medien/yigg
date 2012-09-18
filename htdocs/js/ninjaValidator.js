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
  img_dir: (window.parent.document.location.host.match(/yigg\.de$/)  ? window.parent.document.location.protocol + '//' + 'static.yigg.de/v6/' : '') + 'images/',
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
    //if(el.type == "blur"){
      el = Event.element(el);
    //}
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
    //if(el.type == "blur"){
      el = Event.element(el);
    //}

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