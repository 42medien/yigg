<?php

/**
 * YiGG-Testtools this file contains some tools which simplify the creation of unit anf functional Tests for YiGG
 * Author: Robert Curth (curth@yigg.de)
 *
 * Revision 1 (24.03.2008) - Tools for creation of good or bad dummytexts.
 */

class yiggTestHelper {

	
	 /**
	 * Function to generate Dummy-Text in the right lenght.
	 * @param array texts in different languages
	 * @param array dummytexts in the right lenght
	 */	 
	
	function getCleanText (&$source, $words, $parts = -1){
		$bigDummyString = NULL;
		 $text = array();
		 $i = NULL;
		foreach ($source as $singleLang){
			$bigDummyString = $bigDummyString.' '.$singleLang;
		}

		$singleWords = explode(' ',$bigDummyString);
		
		// Create enough samples 
	    while ($parts > (($i/$words)-2))
	    {		
			foreach ($singleWords as $singleWord)
				{			
					$part=round(++$i/$words);
					if(!array_key_exists($part,$text)) $text[$part];								
					$text[$part]=$text[$part].' '.$singleWord;
					if (($parts == -1)|($part > $parts))break;				
				}
			
		}
		
		return array_slice($text,1,-1);
	}
	
	/**
	 * Creates a bunch of XSS-Strings nested in normal text
	 * @param array XSS-Strings
	 * @param array array with dummyTexts
	 * @param int lenght of the text
	 * @param int position of the xss
	 * @return array dummytexts in the right lenght
	 */
	
	function getXSSTextArray (&$xss_source, &$dummy_texts, $length = 20, $position = 10){
		
		// Create some strings to nest the xss into		
		$nestStrings = $this->getCleanText ($dummy_texts, $length, count ($xss_source));
		$i=0;
						
		//Insert XSS-Values into the Array
		foreach ($xss_source as $string){
			$nest = NULL;			
			$nest = explode(' ',$nestStrings[$i++]);			
			$nest = $this->arrayInsert($nest, $string, $position);
			$badStrings[$i] = implode(' ',$nest);		
		}
		
		//Remove all unused nests and return array with nested  strings
		return $badStrings;						
	}
	
	/**
	 * Generates a small random String
	 * @param int length of the string
	 * @return string Randomstring
	 */
	
	function randomString($length)
	{
	    // Generate random 32 charecter string
	    $string = md5(time());

	    // Position Limiting
	    $highest_startpoint = 32-$length;

	    // Take a random starting point in the randomly
	    // Generated String, not going any higher then $highest_startpoint
	    $randomString = substr($string,rand(0,$highest_startpoint),$length);

	    return $randomString;
	}
	
	/*
	function createBadTags (){
		
	}
	
	function createBadCharArray(){
		
	}
	
	function createURLs(){
		
	}
	*/
	/**
	 * Insert strings into text
	 * @param array Strings for insertion
	 * @param array nest-strings
	 * @param int Number of words where the text should be included
	 */
	
	private function nestStrings ($nests, $strings, $words){
		
		 
	}
	
	/**
	 * Inserts an value to a specific key of a value
	 * @param array target-array
	 * @param mixed value for inclusion
	 * @param int position for insertion
	 * @return array changed array 
	 */
	
	private function arrayInsert($array, $insert, $position) {
		 $tmp = array();
	     $position = ($position == -1) ? (count($array)) : $position ;
	     if($position != (count($array))) {
	          $ta = $array;
	          for($i = $position; $i < (count($array)); $i++) {	               
	               $tmp[$i+1] = $array[$i];
	               unset($ta[$i]);
	          }
	          $ta[$position] = $insert;			  
	          $array = $ta + $tmp;
	     } else {
	          $array[$position] = $insert;          
	     }

	     ksort($array);
	     return $array;
	}
}