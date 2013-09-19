<?php

class YiggMeta {
  private $title = null;
  private $description = null;
  private $images = array();
  private $url = null;
  private $tags = null;

  public function setUrl($url) {
    if (!$this->url && $url) {
      $this->url = urldecode($url);
    }
  }

  public function setTags($tags) {
    if (!$this->tags && $tags) {
      $this->tags = urldecode($tags);
    }
  }

  public function setTitle($title) {
    if (!$this->title && $title) {
      $this->title = strip_tags(urldecode($title));
    }
  }

  public function setDescription($description) {
    if (!$this->description && $description) {
      $this->description = strip_tags(urldecode($description));
    }
  }

  public function setImages($images) {
    if ($images) {
      if (is_array($images)) {
        $this->images = array_merge($this->images, $images);
      } else {
        $this->images = array_merge($this->images, array(urldecode($images)));
      }
      
      $this->images = array_unique($this->images);
    }
  }

  public function getUrl() {
    return $this->url;
  }

  public function getTags() {
    return $this->tags;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getImages() {
    return $this->images;
  }

  public function isComplete() {
    if ($this->title && $this->description && $this->images && $this->tags) {
      return true;
    } else {
      return false;
    }
  }
  
  public function hasImages() {
    if ($this->images) {
      return true;
    } else {
      return false;
    }
  }

  public function fromOpenGraph($og) {
    if ($og) {
      $this->setTitle($og->title);
      $this->setDescription($og->description);
      $this->setImages($og->image);
    }
  }

  public function fromOembed($oe) {
    if ($oe) {
      if (isset($oe->title)) {
        $this->setTitle($oe->title);
      }

      if ($oe->type == "photo" && isset($oe->url)) {
        $this->setImages($oe->url);
      } elseif (isset($oe->thumbnail_url)) {
        $this->setImages($oe->thumbnail_url);
      }
    }
  }
  
  public function fromMicrodata($data) {
    if ($data) {
      if (!isset($data->items) || !is_array($data->items) || count($data->items) == 0) {
        return;
      }
      
      var_dump($data->items);
      
      $data = $data->items[0];
      
      if (isset($data->properties->name)) {
        $this->setTitle($data->properties->name[0]);
      }
      
      if (isset($data->properties->decription)) {
        $this->setDescription($data->properties->decription[0]);
      }
    }
  }
  
  public function fromMicroformats($data) {
    if ($data) {
      
      if (!array_key_exists("items", $data) || !is_array($data["items"]) || count($data["items"]) == 0) {
        return;
      }
      
      $first_item = $data["items"][0];
      
      // check and use title
      if ( array_key_exists("name", $first_item["properties"]) && is_array($first_item["properties"]["name"]) ) {
        $this->setTitle($first_item["properties"]["name"][0]);
      }
      
      // don't know if we should use the complete text of the article
      /*if ( array_key_exists("summary", $first_item["properties"]) && is_array($first_item["properties"]["summary"]) ) {
        $this->setDescription($first_item["properties"]["summary"][0]);
      } else if ( array_key_exists("content", $first_item["properties"]) && is_array($first_item["properties"]["content"]) ) {
        $this->setDescription($first_item["properties"]["content"][0]);
      }*/
      
      // check and use photos
      if ( array_key_exists("photo", $first_item["properties"]) && is_array($first_item["properties"]["photo"]) ) {
        foreach ($first_item["properties"]["photo"] as $photo) {
          $this->setImages($photo);
        }
      }
      
      // check tags
      if ( array_key_exists("rels", $data) &&
           is_array($data["rels"]) &&
           array_key_exists("tag", $data["rels"]) &&
           is_array($data["rels"]["tag"])
         ) {

        $tag_result = array();
        
        // iterate tags
        foreach ($data["rels"]["tag"] as $tag) {
          $url_parts = parse_url($tag);
          
          // check query string
          if ( array_key_exists("query", $url_parts) && $url_parts["query"] ) {
            // use last part of the query string
            if ( preg_match("/=(.[^=]+)$/i", $url_parts["query"], $match) ) {
              $tag_result[] = urldecode($match[1]);
            }
          // check url path
          } else if ( array_key_exists("path", $url_parts) && $url_parts["path"] ) {
            // use last part of the path
            if ( preg_match("/\/(.[^\/]+)\/?$/i", $url_parts["path"], $match) ) {
              $tag_result[] = urldecode($match[1]);
            }
          }
        }
        
        // use tags
        if ($tag_result) {
          $this->setTags(implode(", ", $tag_result));
        }
      }
    }
  }

  public function fromHtml($meta) {
    if (is_array($meta) && array_key_exists('meta', $meta)) {
      $m = $meta['meta'];
      
      // set title
      if (array_key_exists('twitter:title', $m)) {
        $this->setTitle($m['twitter:title']);
      } elseif (array_key_exists('dc.title', $m)) {
        $this->setTitle($m['dc.title']);
      } elseif (array_key_exists('title', $meta)) {
        $this->setTitle($meta['title']);
      }
      
      // get description
      if (array_key_exists('twitter:description', $m)) {
        $this->setDescription($m['twitter:description']);
      } elseif (array_key_exists('dc.description', $m)) {
        $this->setDescription($m['dc.description']);
      } elseif (array_key_exists('description', $m)) {
        $this->setDescription($m['description']);
      }
      
      // get keywords      
      if (array_key_exists('news_keywords', $m)) {
        $this->setTags($m['news_keywords']);
      } elseif (array_key_exists('keywords', $m)) {
        $this->setTags($m['keywords']);
      }
      
      // get images
      if (array_key_exists('twitter:image', $m)) {
        $this->setImages($m['twitter:image']);
      }
    }
    
    // get images
    if (is_array($meta) && array_key_exists('links', $meta)) {
      $l = $meta['links'];
      if (array_key_exists('image_src', $l)) {
        $this->setImages($l['image_src']);
      }
    }
  }

  public function fromParams($params) {
    $this->setTitle($params->get("title"));
    $this->setDescription($params->get("description"));
    $this->setImages($params->get("photo"));
    $this->setTags($params->get("tags"));
  }
}
?>