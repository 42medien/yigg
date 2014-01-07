<?php

class pageRankApi
{
  const PR_API_HOST = 'toolbarqueries.google.com';
  const PR_USERAGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5';

  function convertStringTo32BitInteger($Str, $Check, $Magic)
  {
    $Int32Unit = 4294967296;
    for ($i = 0; $i < strlen($Str); $i++)
    {
        $Check *= $Magic;
        if ($Check >= $Int32Unit)
        {
          $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
          $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
  }

  function getHashForURL($url)
  {
    $Check1 = $this->convertStringTo32BitInteger($url, 0x1505, 0x21);
    $Check2 = $this->convertStringTo32BitInteger($url, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );	
    return ($T1 | $T2);
  }

  function checkHash($Hashnum)
  {
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --)
    {
      $Re = $HashStr{$i};
      if (1 === ($Flag % 2))
      {              
        $Re += $Re;     
        $Re = (int)($Re / 10) + ($Re % 10);
      }
      $CheckByte += $Re;
      $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte)
    {
      $CheckByte = 10 - $CheckByte;
      if (1 === ($Flag % 2) )
      {
        if (1 === ($CheckByte % 2))
        {
          $CheckByte += 9;
        }
        $CheckByte >>= 1;
      }
    }
    return '7'.$CheckByte.$HashStr;
  }

  function getPagerankChecksumHash($url)
  {
	$hash = $this->getHashForURL($url);	
    return $this->checkHash($hash);
  }

  function getPagerank($url)
  {
	$checksum = $this->getPagerankChecksumHash($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, self::PR_API_HOST."/search?client=navclient-auto&ch=$checksum&features=Rank&q=info:$url");
	curl_setopt($ch, CURLOPT_USERAGENT, self::PR_USERAGENT);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	return intval(substr($response, 9, 2));
  }
}

?>