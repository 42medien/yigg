<?php 
class yiggTimeTools
{
	/**
	 * Returns the current epoch time (a super exact timestamp)
	 * @return integer
	 */
	public static function getCurrentEpochTime()
	{
      list ($seconds, $mseconds) = explode (' ', microtime ());
      return round( (float) $seconds + (float) $mseconds, 5);
	}
}
