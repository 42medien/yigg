<?php
/**
 * HttpClient Class.
 * This class represents any HTTP request made from the yigg platform to an external source.
 *
 * @author Risk
 * @author Caffeine -  Upgraded and refactored to v6.
 */
class yiggHttpClient
{
  public $statusTexts = array(
    '100' => 'Continue',
    '101' => 'Switching Protocols',
    '200' => 'OK',
    '201' => 'Created',
    '202' => 'Accepted',
    '203' => 'Non-Authoritative Information',
    '204' => 'No Content',
    '205' => 'Reset Content',
    '206' => 'Partial Content',
    '300' => 'Multiple Choices',
    '301' => 'Moved Permanently',
    '302' => 'Found',
    '303' => 'See Other',
    '304' => 'Not Modified',
    '305' => 'Use Proxy',
    '306' => '(Unused)',
    '307' => 'Temporary Redirect',
    '400' => 'Bad Request',
    '401' => 'Unauthorized',
    '402' => 'Payment Required',
    '403' => 'Forbidden',
    '404' => 'Not Found',
    '405' => 'Method Not Allowed',
    '406' => 'Not Acceptable',
    '407' => 'Proxy Authentication Required',
    '408' => 'Request Timeout',
    '409' => 'Conflict',
    '410' => 'Gone',
    '411' => 'Length Required',
    '412' => 'Precondition Failed',
    '413' => 'Request Entity Too Large',
    '414' => 'Request-URI Too Long',
    '415' => 'Unsupported Media Type',
    '416' => 'Requested Range Not Satisfiable',
    '417' => 'Expectation Failed',
    '500' => 'Internal Server Error',
    '501' => 'Not Implemented',
    '502' => 'Bad Gateway',
    '503' => 'Service Unavailable',
    '504' => 'Gateway Timeout',
    '505' => 'HTTP Version Not Supported',
  );

  // Configuration
  protected $timeout   = 5;
  protected $maxContentLength = 10000;
  protected $userAgent = 'YiGG HTTP Hampster';

  protected $_stream   = null;
  protected $header = null;
  protected $body = null;

  protected $addr = null;
  protected $host = null;
  protected $target = null;
  protected $url = null;

  /**
   * Returns the body of the response element of the selected http request
   *
   * @return String the reponse data.
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Returns the array of header information from the response of your request.
   *
   * @return Array Header information.
   */
  public function getheader()
  {
    return $this->header;
  }

  /**
   * gets the current content limit of the client
   * @return
   */
  public function getContentLimit()
  {
    return $this->maxContentLength;
  }

  /**
   * Changes the content limit of this client
   * @return
   * @param $limit Object
   */
  public function setContentLimit($limit)
  {
    $this->maxContentLength = $limit;
  }

  /**
   * Make a HTTP request to a given URL.
   *  - Follows redirections up to 5 times.
   *  - populates variables passed by reference.
   *
   * @return Boolean request successful
   *
   * @param $url String url you wish to request
   * @param $header any headers to add.
   * @param $body Body content of the request.
   * @param $max Object[optional]
   */
  public function get ( $url  = null )
  {
    if(!$url)
    {
      $url = $this->url;
    }

    // Parse the location and make sure it is valid.
    $validRequest = $this->_parseLocation( $url );

    if ( true === $validRequest )
    {

      // Loop and follow HTTP redirects up to 5 times.
      for ( $count = 0; $count <= 5 && true == $validRequest; $count++ )
      {

        // Make the connection to the stream using fsock && tcp
        $tcpAddr = sprintf ('tcp://%s', $this->addr);
        $this->_stream = @fsockopen ( $tcpAddr , $this->port, $errno, $errstr, $this->timeout );

        if( false ==  isset($this->stream) )
        {
          // Get the response and close the stream
          $putResponse = $this->_putRequest();
          $getResponse = $this->_getResponse();

          @fclose ($this->_stream);

          if( (true == ($putResponse && $getResponse)) && $this->header )
          {
            $httpStatus = (int) ( $this->header['http-status'] );
            switch( $httpStatus )
            {

              // HTTP: OK.
              case 200:
                return true;
              break;

              // We've got a redirect repsponse, follow the redirection and try and get a valid response.
              case 301:
              case 302:
              case 307:

                $target = $this->header['location'];
                $validRequest = $this->_parseLocation ($target);

              break;

              // We've got an invalid HTTP response.
              default:
                return false;
              break;
            }
          }
          else
          {
            // We haven't got a response so return false;
            return false;
          }
        }
        else
        {
          // We haven't got a valid stream so return false;
          return false;
        }
      }
    }

    // we havent got a valid request.
    return false;
  }

  /**
   * Parse location checks to make sure the location is valid before sending the request.
   *
   * @return Boolean
   * @param $location The url/location of the HTTP request
   * @param $addr the IP of the http request
   * @param $host the hostname of the http request
   * @param $port the port for the http request
   * @param $target the target of the URL for headers.
   */
  public function _parseLocation ( $location )
  {

    // A inproper URL will throw an error.
    try{

      // make the validator not check for unique URLs, and not to check the dns
      $configure = array( 'unique' => false, 'checkDNS' => false );
      $validator = new sfValidatorSchema(
        array(
          'externalUrl' => new yiggValidatorStoryURL( $configure ,  array())
        )
      );

      $location = $validator->clean( array( 'externalUrl' =>  $location ) );

      // if we're here the URL is fine, split up everything
      $url_info = parse_url( $location['externalUrl'] );

      // fix empty location path causing error in dev-mode
      if( false == isset($url_info['path']) )
      {
        $url_info['path'] = '';
      }

      if( array_key_exists( 'scheme',$url_info ) )
      {
        // set up the location information.
        $this->host = $url_info['host'];
        $this->addr = @gethostbyname( $this->host );

        array_key_exists('query',$url_info) ? $this->target  = $url_info['path'] . '?' .$url_info['query'] : $this->target = $url_info['path'];

        // Set the port based on the scheme
        switch ( $url_info['scheme'] )
        {
          // SSL
          case 'https':
            $this->port = 443;
          break;

          // Standard HTTP
          case 'http':
          default:
            $this->port = 80;
          break;
        }
        // URL is fine :)
        return true;
      }
      else
      {
        // we couldn't parse the URL properly.
        throw new Exception("yiggHttpClient::_parseLocation(): url Scheme not set");
      }
    }
    catch( Exception $e )
    {
      sfContext::getInstance()->getLogger()->log('yiggHTTPclient->parseLocation called, but couldnt parse the location: ' . $location , 4);
      return false;
    }
  }


  /**
   *
   * @return
   * @param $host Object
   * @param $target Object
   */
  private function _putRequest()
  {
    // Populate the request headers and make the connection
    $request = ""
            ."GET $this->target HTTP/1.1\r\n"
            ."Host: $this->host\r\n"
            ."User-Agent: $this->userAgent\r\n"
            ."Connection: close\r\n\r\n";
    return ( @fputs ($this->_stream, $request) );
  }

  /**
   * Returns the response from the socket.
   * I haven't refactored it yet. can you tell?
   *
   * @return
   */
  private function _getResponse()
  {

    @socket_set_timeout( $this->_stream, $this->timeout);
    $header = array ();

    // Populate headers from repsonse
    while (!@feof ($this->_stream))
    {
      if (($line = @fgets ($this->_stream, 1024)) === false)
      {
        return (false);
      }
      elseif( ($pos = strpos ($line, ':')) === false)
      {
        if (trim ($line) != '')
        {
          $header['http-status'] = substr ($line, 9, 3);
        }
        elseif (($line{0} == "\r") || ($line{0} == "\n"))
        {
          break;
        }
      }
      else
      {
        $key = substr ($line, 0, $pos);
        $val = substr ($line, $pos + 1);
        $header[strtolower ($key)] = trim ($val);
      }
    }

    // Fix for chunked encoding types
    if ( array_key_exists('transfer-encoding',$header) && $header['transfer-encoding'] == 'chunked')
    {
      if (@fgets ($this->_stream, 1024) === false)
      {
        return (false);
      }
    }

    // populate the headers
    $this->header = $header;

    // Get the content up to the limit
    $response = '';
    $content_length = 0;
    while (!@feof ($this->_stream))
    {

      $content = @fread ($this->_stream, 1024);
      if ($content)
      {
        // add the content to the response
        $response .= $content;
        $content_length += 1024;
      }
      else
      {
        // Something went wrong with the reading of the stream.
        return false;
      }

      // Stop if we're over the content limit.
      if ( $content_length >= $this->maxContentLength )
      {
        break;
      }
    }

    // Build and fix the encoding of the content it always returns utf-8
    if($response)
    {
      if ( array_key_exists('transfer-encoding',$header) &&  $header['transfer-encoding'] == 'chunked')
      {
        $response = substr ($response, 0, -7);
      }
      $this->body =  yiggEncodingTools::fixEncodingType( $response, $this->header );
    }
    return true;
  }

  /**
   * Checks to make sure we have a valid response.
   * @return boolean
   */
  public function hasResponse()
  {
    if(isset($this->body) && isset($this->header))
    {
      return true;
    }
    return false;
  }
}