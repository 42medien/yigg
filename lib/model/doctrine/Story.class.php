<?php
class Story extends BaseStory
{
    const TYPE_NORMAL = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_VIDEO = 2;
    private $types = array(self::TYPE_NORMAL => 'Normal', self::TYPE_ARTICLE => 'Article', self::TYPE_VIDEO => 'Video');

    private $cachedUrl = null;
    private $cache;

    public function construct()
    {
        $this->cache = new sfParameterHolder();
    }

    /**
     * Retrive story image source
     *
     * @return string/null;
     */
    public function getStoryImageSource()
    {
        $query = Doctrine_Query::create()
            ->select('f.*')
            ->from('File f')
            ->where('f.id = ?', $this->getImageId());
        $result = $query->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if(count($result)){
            return $result['file_directory'].$result['file_name'].$result['file_type'];
        }
        return null;
    }

    /**
     * Retrive the date of the last published news
     *
     * @param User
     * @return string;
     */
    public static function getUserLastStoryDate($user)
    {
        if(is_null($user)){
            return;
        }

        $query = Doctrine_Query::create();
        $query  -> select('s.created_at')
            ->from('Story s')
            ->where('s.user_id = ?', (int) $user->getId())
            ->orderBy('s.created_at DESC');

        $story = $query->fetchOne();

        if($story){
            return $story->getCreatedAt();
        }
    }

    public function setStoryType( $type = 0 )
    {
        $types = array_flip( $this->types );
        if( array_key_exists( $type, $types) )
        {
            $this->type = $types[$type];
        }
        else
        {
            throw new Exception('story->setType() called, type is wrong!!! tryed to set' . $type , self::TYPE_VIDEO);
        }
    }

    /**
     * Allows the user to rate, with conditions.
     *
     * user must not have voted already
     * the ip of user must not have voted already.
     *
     *  @param yiggSession
     *  @return void;
     */
    public function rate( $userSession , $conn=null)
    {
        // check our cache.
        if( $this->hasRated() === true )
        {
            return $this;
        }

        if( true === $userSession->hasRated($this['id']) )
        {
            // update cache
            $this->setHasRated(true);
            return $this;
        }

        // Grrrrrrrr nasty, but we have to, cause the
        //  model can't support large collections :(
        $r = new Rating();
        $sr = new StoryRating();

        $sr->Rating = $r;
        $sr->story_id = $this->id;

        $r->save($conn);
        $sr->save($conn);

        $this->cache->set("currentRating", StoryRatingTable::getCountById( $this->id ) );
        $this->setHasRated(true);
        return $this;
    }

    /**
     * Create an initial vote for user that are not logged in
     */
    public function initalVote($conn = null)
    {
        $rating = new Rating();
        $this->Ratings->add($rating);
        $this->save($conn);
    }

    /**
     * Sets a flag to notify that this session has already rated.
     *
     * @param $var Boolean (rated or not).
     */
    public function setHasRated($var)
    {
        $this->cache->set("sessionHasRated", $var);
    }

    /**
     * returns boolean if the session has rated,
     * or null if the story doesn't know.
     *
     * @return Boolean
     */
    public function hasRated()
    {
        return $this->cache->get("sessionHasRated",null);
    }

    /**
     * Returns the current rating for this story.
     * @return int
     */
    public function currentRating()
    {
        if(false === $this->cache->has("currentRating"))
        {
            $this->cache->set("currentRating", StoryRatingTable::getCountById( $this->id ));
        }
        return $this->cache->get("currentRating");
    }

    /**
     * Returns the current comment count for this story.
     */
    public function currentCommentCount()
    {
        if(false === $this->cache->has("currentCommentCount"))
        {
            $this->cache->set("currentCommentCount", null === $this->hasRelation('Comments') ? CommentTable::getCount($this) : count($this->Comments) );
        }
        return $this->cache->get("currentCommentCount");
    }

    public function canEdit( $userSession)
    {
        return $this->isAuthor($userSession) || $userSession->isAdmin() || $userSession->isModerator();
    }

    /**
     * Checks to see if the userSession passed is the author of this story
     * @return bool
     * @param $user Object
     */
    public function isAuthor( $userSession )
    {
        if($userSession->hasUser())
        {
            return $this->user_id === $userSession->getUser()->id;
        }
        return false;
    }

    /**
     * returns the embeded url for a video
     *
     * @return String Returns the URL for the SWF playing the video or null if no video
     */
    public function getVideoLink()
    {
        if($this->type !== self::TYPE_VIDEO)
        {
            return;
        }
        $video = yiggExternalVideoFactory::createFromUrl($this->external_url);
        return $video->getPlayerUrl();
    }

    /**
     * Updates the object with information from the post data (must be validated through form.
     * @return void
     * @param $data array of post data
     */
    public function update(array $data , $conn=null)
    {
        if(array_key_exists("Categories", $data ) )
        {
            $this->updateCategories( $data['Categories'] );
            unset($data['Categories']);
        }
        print_r($data['Categories']); die;
        if(array_key_exists("Tags", $data ) )
        {
            $this->updateTags( $data['Tags'] );
            unset($data['Tags']);
        }

        $this->fromArray($data);
        $this->save($conn);
    }

    public function updateCategories($categories){
        foreach($categories as $category_id){
            $category = new Category();
            $category->setStoryId($this->getId());
            $category->setCategoryId($category_id);
            $category->save();
        }
    }

    public function preValidate($event)
    {
        if(array_key_exists("title", $this->getModified()))
        {
            $this->title = mb_substr($this->title, 0, 128,"utf-8"); // Prevents that the title gets to long
        }

        if($this->type == self::TYPE_ARTICLE)
        {
            $this->external_url = $this->getLinkWithDomain();
        }

        if(array_key_exists("external_url", $this->getModified()))
        {
            $domain = DomainTable::getInstance()->findOneByHostname($this->getHostname());
            if(false === $domain)
            {
                $domain = new Domain();
                $domain->hostname = $this->getHostname();
                $domain->save();
            }
            $this->domain_id = $domain->id;
        }

        if(true === $this->isVideo())
        {
            $this->type = self::TYPE_VIDEO;
        }
    }

    /**
     * returns the url or false if the external_url for this story
     * is a link to a social video site.
     *
     * @return Boolean true if video false if no video
     */
    private function isVideo()
    {
        $video = yiggExternalVideoFactory::createFromUrl($this->external_url);
        return false === is_null($video);
    }

    public function getSourceHost()
    {
        return parse_url($this->external_url, PHP_URL_HOST);
    }

    /**
     * Returns the type of this story.
     *
     * @return unknown
     */
    public function getStoryType()
    {
        $types = $this->types;
        return $types[ (int) $this->type ];
    }

    /**
     * Get twitter link
     */
    public function getTwitterLink()
    {
        $twitter_args = array(
            "related" => "yigg",
            "lang" => "de",
            "url" => $this->getExternalShortUrl(),
            "text" => mb_substr($this->title , self::TYPE_NORMAL,  87 ,'utf-8')
        );

        $twitter_args["text"] .= " " . $this->getInternalShortUrl() . " Quelle: ";
        return "http://twitter.com/share?".http_build_query($twitter_args);
    }

    /**
     * Get a shorturl for the external_url off this story
     * @return String
     */
    public function getExternalShortUrl()
    {
        if($this->getStoryType() === "Normal")
        {
            $url = sfContext::getInstance()->getController()->genUrl($this->external_url, true);
        }
        else
        {
            $url = sfContext::getInstance()->getController()->genUrl($this->getLinkWithDomain(), true);
        }

        return $this->createLinkedRedirect($url);
    }

    /**
     * Creates a shorturl for the stories internal url
     * @return void
     */
    public function getInternalShortUrl()
    {
        return $this->createLinkedRedirect($this->getLinkWithDomain());
    }

    public function createLinkedRedirect($url)
    {
        $redirect = RedirectTable::getByUrl($url);
        if($redirect === false)
        {
            $redirect = Redirect::create($url);
            $StoryRedirect = new StoryRedirect();
            $StoryRedirect->story_id = $this->id;
            $StoryRedirect->redirect_id = $redirect->id;
            $StoryRedirect->save();
        }
        return $redirect->getMiniUri();
    }

    /**
     * Get recent ratings
     * @see Defaults configured in app.yml
     * @param integer $limit
     * @return Ratings
     */
    public function getRecentUserRatings( $limit = null )
    {
        if(empty($limit))
        {
            $limit = sfConfig::get('app_storyDetail_ratings', 10);
        }
        $query = Doctrine_Query::create()
            ->select('sr.*, u.*, a.*')
            ->from('StoryRating sr')
            ->leftJoin('sr.Rating r')
            ->leftJoin("sr.User u")
            ->leftJoin("u.Avatar a")
            ->where('sr.story_id = ? AND sr.user_id <> 1', $this->id)
            ->orderBy('r.created_at DESC')
            ->limit($limit);
        return $query->execute();
    }

    public function getCreationYear()
    {
        return substr($this["created_at"], 0, 4);
    }

    public function getCreationMonth()
    {
        return substr($this["created_at"], 5, 2);
    }

    public function getCreationDay()
    {
        return substr($this["created_at"], 8, 2);
    }

    public function wasOnFrontpage()
    {
        return Doctrine_Query::create()
            ->from("History")
            ->where("story_id = ?", $this->id)
            ->count() > 0;
    }

    /**
     * Return the hostname off this story
     * @return mixed
     */
    public function getHostname()
    {
        return parse_url($this->external_url, PHP_URL_HOST);
    }

    /**
     * Returns a link of this story
     * @return void
     */
    public function getLink()
    {
        return sprintf("@story_show?year=%s&month=%s&day=%s&slug=%s",
            $this->getCreationYear(),
            $this->getCreationMonth(),
            $this->getCreationDay(),
            $this->internal_url);
    }

    public function getLinkWithDomain()
    {
        return "http://yigg.de/nachrichten/{$this->getCreationYear()}/{$this->getCreationMonth()}/{$this->getCreationDay()}/{$this->internal_url}";
    }
}
