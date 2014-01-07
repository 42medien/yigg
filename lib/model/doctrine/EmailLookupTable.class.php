<?php

/**
 * 
 * @package     yigg
 * @subpackage  email
  */
class EmailLookupTable extends Doctrine_Table
{
	/**
	 * count all email lookups for specific ip address
	 * by default ignores deleted
	 *
	 * @param 	Doctrine_Query	$criteria
	 * @param 	boolean 		$withDeleted
	 * @return	integer
	 */
	public static function getCountByIp($ip_address)
	{
		$query = Doctrine_Query::create()
                    ->select('COUNT(*) as num_rows')
				    ->from('EmailLookup')
                    ->where('ip_address = ?', $ip_address);
		
		return (int) $query->fetchOne()->num_rows;
	}

}