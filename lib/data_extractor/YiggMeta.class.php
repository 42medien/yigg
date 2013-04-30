<?php

class YiggMeta {
  private $title = null;
  private $description = null;
  private $images = null;
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
    if (!$this->images && $images) {
      if (is_array($images)) {
        $this->images = $images;
      } else {
        $this->images = array(urldecode($images));
      }
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

  public function fromOpenGraph($og) {
    if ($og) {
      $this->setTitle($og->title);
      $this->setDescription($og->description);
      $this->setImages($og->image);
    }
  }

  public function fromOembed($oe) {
    if ($oe) {
      $this->setTitle($oe->title);

      if ($oe->type == "photo" && $oe->url) {
        $this->setImages($oe->url);
      } else {
        $this->setImages($oe->thumbnail_url);
      }
    }
  }

  public function fromMeta($meta) {
    if (is_array($meta) && array_key_exists('meta', $meta)) {
      $m = $meta['meta'];

      if (array_key_exists('dc.title', $m)) {
        $this->setTitle($m['dc.title']);
      }
      if (array_key_exists('dc.description', $m)) {
        $this->setDescription($m['dc.description']);
      }

      if (array_key_exists('description', $m)) {
        $this->setDescription($m['description']);
      }

      if (array_key_exists('keywords', $m)) {
        $this->setTags($m['keywords']);
      }
    }

    if (is_array($meta) && array_key_exists('links', $meta)) {
      $l = $meta['links'];
      if (array_key_exists('image_src', $l)) {
        $this->setImages($l['image_src']);
      }
    }

    $this->setTitle($meta['title']);
  }

  public function fromParams($params) {
    $this->setTitle($params->get("title"));
    $this->setDescription($params->get("description"));
    $this->setImages($params->get("photo"));
    $this->setTags($params->get("tags"));
  }
}
?>