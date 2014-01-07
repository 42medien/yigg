<?php
class yiggSystemTools
{
  /**
   * Returns an array with the load avg
   */
  public static function getLoadAvergage()
  {
    $load_file = file_get_contents("/proc/loadavg");
    preg_match("^([0-9]{1,2}.[0-9][0-9]) ([0-9]{1,2}.[0-9][0-9]) ([0-9]{1,2}.[0-9][0-9])^", $load_file, $result);

    $load_avg = array();
    $load_avg[] = (float)$result[1];
    $load_avg[] = (float)$result[2];
    $load_avg[] = (float)$result[3];
    
    return $load_avg;
  }
  
  /**
   * Checks if a process is running
   * @param string $process_name
   * @return boolean
   */
  public static function isProcessRunning($process_name)
  {
    exec(sprintf('ps aux | grep %s', $process_name), $result);
    return count($result) > 2;
  }
}
