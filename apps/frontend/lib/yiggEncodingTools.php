<?php
/**
 * This class has encoding type functions which support the use of the HTTP request.
 * 
 * 
 */

class yiggEncodingTools extends yiggTools {
		
	/**
	 * Fixes the encoding type of the content to utf-8,
	 * just in case it was set wrong, or is wrong
	 * 
	 * @return String the utf-8 content
	 * @param $content Body or content you wish to check
	 * @param $headers HTTP headers
	 */
	public static function fixEncodingType( $content , $headers)
	{
		// Detect encoding type and change to utf8
		$charset = self::findEncodingType( $content, $headers);		
		mb_substitute_character ('none');
		$content = mb_convert_encoding ( $content, 'utf8', false !== $charset ? $charset : 'UTF-8');
		return $content;
	}
	
	
	/**
	 * Finds and returns the correct encoding type from the request headers, and/or body.
	 * 
	 * @return string Character encoding type. 
	 * @param $content String you wish to test
	 * @param $headers The Request reposonse header array (from yiggHttpClient). 
	 */
	public static function findEncodingType($content, $headers){
		
		// Try headers
		$charset = self::findCharsetFromHeaders( $headers );
		if( false === $charset ){

			// Try meta
			$charset = self::findCharsetFromMeta( $content );
			if( false === $charset ){
				
				// Try the title string
				if(preg_match ('/<title[^>]*>(.+)<\/title>/is', $content, $title)){
				
					$charset = self::findCharsetManually( $title[1] );
					if( false == $charset ){
						// We seriously tried everything.
						return false;
					}
				}
			}
		}
		return $charset;
		
	}
	
	/**
	 * finds the character set from a request header using regular expressions.
	 * @return the character set.
	 * @param $contentheader Array, The request response header array from yiggHTTPClient
	 */
	public static function findCharsetFromHeaders( $headers )
	{
		if( array_key_exists('content-type', $headers) ){
			return self::findCharsetFromContent( $headers['content-type'] );
		}
		
		return false;
	}
	
	/**
	 * finds the character set from the meta information.
	 * @return String characterset or false if it could not be found
	 * @param $content String content including <head>
	 */
	public static function findCharsetFromMeta( $content )
	{
		if ( preg_match ('/<meta.*http-equiv="content-type".*content="([^"]+)".*>/is', $content, $register) ){
			return self::findCharsetFromContent( $register[1] );	
		}
		return false;
        
	}
	
	/**
	 * finds the charset from the php function for detecting encoding
	 * @return String the charset
	 * @param $content Object
	 */
	public static function findCharsetManually( $content )
	{
		return mb_detect_encoding($content);
	}
	
	/**
	 * Finds the appropriate character set from a ; seperated string of values
	 * like in the header, or meta tags via regular expression.
	 * @return String characterset or false if it could not be found
	 * 
	 * @param $content Object
	 */
	public static function findCharsetFromContent( $contentString )
	{
		// Nasty regular expression for finding and seperating on the ;
		if ( preg_match ('/^[^\/]+\/[^;]+;(.+)$/ism', $contentString, $register) ){

	        $paramList = explode (';', $register[1] );
			foreach ($paramList as $param){
				
				// only return the matching charset value
	            if (preg_match ('/charset\s*=\s*([^\s]+)/i', $param, $register)){
	                return  $register[1] ;
				}
			}
	    }

		// No Charset found.
		return false;
	}	
}
