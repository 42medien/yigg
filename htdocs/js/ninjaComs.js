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

    // Make sure were in debug mode or only display FATAL errors
    if ( 1 == this.debug || fatalError === true) {

      // Create a DOM element
      var errorMessage = new Element('h3', {
        'class': 'error_message',
        'style': 'position:absolute; top:0px; margin:auto;'
      });

      // add the error message, and append to document
      errorMessage.update(message);
      $('Layout').appendChild(errorMessage);
      Effect.Shake(errorMessage);
    }
  }
});