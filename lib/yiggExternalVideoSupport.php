<?php

/**
 * Base helper class for external video support implementations
 *
 * @package    yigg
 * @subpackage video
 */
class yiggExternalVideoSupport {
	private $providers = array();
  private $width = 750;
  private $height = 600;

	/**
	 * Constructor
	 *
	 * @uses apply_filters() Filters a list of pre-defined oEmbed providers.
	 */
	function __construct($width = 750, $height = 600) {
    $this->width  = $width;

		// List out some popular sites that support oEmbed.
		// The WP_Embed class disables discovery for non-unfiltered_html users, so only providers in this array will be used for them.
		// Add to this list using the wp_oembed_add_provider() function (see its PHPDoc for details).
		$this->providers = array(
			'#https?://(www\.)?youtube\.com/watch.*#i'           => array( 'http://www.youtube.com/oembed',                     true  ),
			'http://youtu.be/*'                                  => array( 'http://www.youtube.com/oembed',                     false ),
			'http://blip.tv/*'                                   => array( 'http://blip.tv/oembed/',                            false ),
			'#https?://(www\.)?vimeo\.com/.*#i'                  => array( 'http://vimeo.com/api/oembed.{format}',              true  ),
			'#https?://(www\.)?dailymotion\.com/.*#i'            => array( 'http://www.dailymotion.com/services/oembed',        true  ),
			'http://dai.ly/*'                                    => array( 'http://www.dailymotion.com/services/oembed',        false ),
      '#https?://(www\.)?jest.com/(video|embed)/.*#i'      => array( 'http://www.jest.com/oembed.{format}',               true  ),
      '#https?://(www\.)?vine.co/v/.*#i'                   => array( 'http://api.embed.ly/1/oembed',                      true  ),
      '#https?://(www\.)?speakerdeck.com/.*/.*#i'          => array( 'https://speakerdeck.com/oembed.{format}',           true  ),
      //'#https?://(www\.)?polleverywhere.com/(multiple_choice_polls|free_text_polls)/.*#i'    => array( 'http://www.polleverywhere.com/services/oembed',    true  ),
			//'#https?://(www\.)?flickr\.com/.*#i'                 => array( 'http://www.flickr.com/services/oembed/',            true  ),
			//'http://flic.kr/*'                                   => array( 'http://www.flickr.com/services/oembed/',            false ),
      //'#https?://(www\.)?mlg.tv/.*#i'                      => array( 'http://tv.majorleaguegaming.com/oembed',            true  ),
      //'http://tv.majorleaguegaming.com/*'                  => array( 'http://tv.majorleaguegaming.com/oembed',            true  ),
      '#https?://(www\.)?kickstarter\.com/projects/.*#i'   => array( 'http://www.kickstarter.com/services/oembed',        true  ),
      '#https?://(www\.)?ted\.com/(index.php/)?talks/.*#i' => array( 'http://www.ted.com/talks/oembed.{format}',          true  ),
      '#https?://(www\.)?huffduffer\.com/.*/.*#i'          => array( 'http://huffduffer.com/oembed',                      true  ),
      #'#https?://(www\.)?meetup(.com|.ps)/.*#i'            => array( 'https://api.meetup.com/oembed',                     true  ),
      '#https?://(.+\.)?ted.com/(index.php/)?talks/lang/.*/.*#i'  => array( 'http://www.ted.com/talks/oembed.{format}',   true  ),
			'#https?://(.+\.)?smugmug\.com/.*#i'                 => array( 'http://api.smugmug.com/services/oembed/',           true  ),
			'#https?://(www\.)?hulu\.com/watch/.*#i'             => array( 'http://www.hulu.com/api/oembed.{format}',           true  ),
			'#https?://(www\.)?viddler\.com/.*#i'                => array( 'http://lab.viddler.com/services/oembed/',           true  ),
      '#https?://(.+\.)?deviantart.com/art/.*#i'           => array( 'http://backend.deviantart.com/oembed',              true  ),
      '#https?://(.+\.)?deviantart.com/(.*)/d.*#i'         => array( 'http://backend.deviantart.com/oembed',              true  ),
			'http://qik(.com|.ly)/*'                             => array( 'http://qik.com/api/oembed.{format}',                false ),
			'http://revision3.com/*'                             => array( 'http://revision3.com/api/oembed/',                  false ),
			'http://i*.photobucket.com/albums/*'                 => array( 'http://photobucket.com/oembed',                     false ),
			'http://gi*.photobucket.com/groups/*'                => array( 'http://photobucket.com/oembed',                     false ),
			'#https?://(www\.)?scribd\.com/.*#i'                 => array( 'http://www.scribd.com/services/oembed',             true  ),
			'http://wordpress.tv/*'                              => array( 'http://wordpress.tv/oembed/',                       false ),
			'#https?://(.+\.)?polldaddy\.com/.*#i'               => array( 'http://polldaddy.com/oembed/',                      true  ),
			'#https?://(www\.)?funnyordie\.com/videos/.*#i'      => array( 'http://www.funnyordie.com/oembed',                  true  ),
      '#https?://(www\.)?collegehumor\.com/(video|embed)/.*#i'     => array( 'http://www.collegehumor.com/oembed.{format}',       true  ),
			'#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i'=> array( 'http://api.twitter.com/1/statuses/oembed.{format}', true  ),
 			'#https?://(www\.)?soundcloud\.com/.*#i'             => array( 'http://soundcloud.com/oembed',                      true  ),
			'#https?://(www\.)?slideshare\.net/*#'               => array( 'http://www.slideshare.net/api/oembed/2',            true  ),
			//'#http://instagr(\.am|am\.com)/p/.*#i'               => array( 'http://api.instagram.com/oembed',                   true  ),
			'#https?://(www\.)?rdio\.com/.*#i'                   => array( 'http://www.rdio.com/api/oembed/',                   true  ),
			'#https?://rd\.io/x/.*#i'                            => array( 'http://www.rdio.com/api/oembed/',                   true  ),
			'#https?://(open|play)\.spotify\.com/.*#i'           => array( 'https://embed.spotify.com/oembed/',                 true  ),
		);
	}

  /**
   * Tries to locate the ID of a video by appling a regular expression to it
   *
   * @param string $url The URL to match on
   */
  public function match_url($url) {
		$provider = false;

    foreach ( $this->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;

			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}
      
			if ( preg_match( $matchmask, $url ) ) {
				$provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				return $provider;
			}
		}
  }

  public function get_html($url) {
    $profider = $this->match_url($url);
    
    if (!$profider) {
      return false;
    }
    
    $data = $this->fetch($profider, $url);
    
    if (!$data) {
      return false;
    }
    
    return $this->data2html($data, $url);
  }
  
  public function fetch($provider, $url) {
    $url = $provider . "?format=json&maxwidth=".$this->width."&lang=de&maxheight=".$this->height."&url=" . urlencode($url);
    
    $response_body = yiggUrlTools::do_get($url);
    
    return ( ( $data = json_decode( trim( $response_body ) ) ) && is_object( $data ) ) ? $data : false;
  }
  
	/**
	 * Converts a data object from {@link WP_oEmbed::fetch()} and returns the HTML.
	 *
	 * @param object $data A data object result from an oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @return bool|string False on error, otherwise the HTML needed to embed.
	 */
	public function data2html( $data, $url ) {
		if ( ! is_object( $data ) || empty( $data->type ) )
			return false;

		$return = false;

		switch ( $data->type ) {
			case 'photo':
				if ( empty( $data->url ) || empty( $data->width ) || empty( $data->height ) )
					break;
				if ( ! is_string( $data->url ) || ! is_numeric( $data->width ) || ! is_numeric( $data->height ) )
					break;

				$title = ! empty( $data->title ) && is_string( $data->title ) ? $data->title : '';
				$return = '<a href="' . $url . '"><img src="' . $data->url . '" alt="' . $title . '" width="' . $data->width . '" height="' . $data->height . '" /></a>';
				break;

			case 'video':
			case 'rich':
				if ( ! empty( $data->html ) && is_string( $data->html ) )
					$return = $data->html;
				break;

			case 'link':
				if ( ! empty( $data->title ) && is_string( $data->title ) )
					$return = '<a href="' . $url . '">' . $data->title . '</a>';
				break;

			default:
				$return = false;
		}

		// You can use this filter to add support for custom data types or to filter the result
		return $return;
	}
}