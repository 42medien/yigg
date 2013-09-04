<?php
  
class yiggSocialNetworkCounter {
  
  private $url = null;
  
  public function __construct($url) {
    $this->url = $url;
  }
  
  public function get_twitter() {
    $raw_data = yiggUrlTools::do_get('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);
    
    if( $data = json_decode($raw_data) ) {
    	return intval($data->count);
    }

    return 0;
  }
  
  public function get_facebook() {
    $raw_data = yiggUrlTools::do_get('http://graph.facebook.com/?ids=' . $this->url);
    				
    if($data = json_decode($raw_data, true)) {
      if (isset($data[$this->url]['shares'])) {
        return intval($data[$this->url]['shares']);
      }      
    }
    
    return 0;
  }
  
  public function get_google() {
    $raw_data = yiggUrlTools::do_post('https://clients6.google.com/rpc', '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $this->url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]', array('Content-type: application/json'));
    				
    if ($data = json_decode($raw_data, true)) {
      if (isset($data[0]) && isset($data[0]['result'])) {
        return intval($data[0]['result']['metadata']['globalCounts']['count']);
      }
    }
    
    return 0;
  }
  
  public function get_yigg() {
    $story = StoryTable::getTable()->findOneByExternalUrl($this->url);
    
    if ($story) {
      return $story->currentRating();
    }
    
    return 0;
  }
  
  public function get_spreadly() {
    $raw_data = yiggUrlTools::do_get('http://api.spreadly.com/shares/counter?url=' . urlencode($this->url));
    
    if ($data = json_decode($raw_data, true)) {
      return $data['counter'];
    }
    
    return 0;
  }
}