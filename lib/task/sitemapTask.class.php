<?php
class sitemapTask extends yiggTaskToolsTask
{
  const   LIMIT = 35000;
  private $baseurl = "http://yigg.de";
  private $sitemap_dir;

  protected function configure()
  {
    $this->namespace        = 'generate';
    $this->name             = 'sitemap';
    $this->briefDescription = 'Creates the sitemaps for YiGG';
  }

  public function preExit(){}

  public function preExecute(){}

  public function executeWork($arguments = array(), $options = array())
  {
    $this->sitemap_dir = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR;
    $fs = new sfFilesystem();
    $fs->mkdirs($this->sitemap_dir,0755);

    $this->renderAndSaveSitemap("sitemapArchive.xml", 'sitemapArchiveXml', array("day" => 10, "month" => 1, "year" => 2006));
    $this->generateTagIndex();
    $this->processStories();
    $this->renderAndSaveSitemap("sitemap.index.xml", 'sitemapXmlIndex', array("sitemap_count" =>  $this->sitemap_count));
  }

  private function processStories()
  {
    $count = Doctrine::getTable("Story")->count();
    $this->log("stories to process: $count");

    $offset = 0;
    $num = 0;
    while( $offset <= $count )
    {
      $stories = $this->retrieveStories($offset);
      if(count($stories) > 0)
      {
        $filename = "sitemap". ++$num   .".xml";
        $this->renderAndSaveSitemap($filename, 'sitemapXml', array("baseurl" => $this->baseurl, "urls" =>  $stories));
        $this->log("Saved to file: $filename");
        $this->log("Wrote " . count($stories) ." urls");
      }
      $offset += self::LIMIT;
    }
    $this->sitemap_count = $num;
  }

  private function retrieveStories( $offset)
  {
    $this->log("processing stories $offset to " . ($offset + self::LIMIT));

    return Doctrine_Query::create()->
             select("s.created_at, s.internal_url")->
             from("Story s")->
             offset($offset)->
             limit(self::LIMIT)->
             fetchArray();
  }

  private function generateTagIndex()
  {
    $tags = Doctrine_Query::create()
            ->select("t.*")
            ->from("Tag t")
            ->where("(SELECT COUNT(*) FROM StoryTag AS st WHERE st.tag_id = t.id) > 150")
            ->execute(array(), Doctrine::HYDRATE_ARRAY);

    $this->renderAndSaveSitemap("sitemapTags.xml", "sitemapTagsXml", array("tags" => $tags));
    $this->log("Generated Sitemap for Tags");
  }

  private function renderAndSaveSitemap($filename, $template, $vars)
  {
    $partial = new sfPartialView( sfContext::getInstance(), 'system', $template,'');
    $partial->setPartialVars($vars);
    file_put_contents($this->sitemap_dir . $filename, $partial->render());
    $this->log("Wrote $filename");
  }
}