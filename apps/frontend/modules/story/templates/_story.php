<?php echo tag( isset($total) && $total > 1 ? "li" : "div", array("class"=>"hentry post ", "id"=>"story_{$story['id']}"), true);?>
  <?php use_helper("Date"); ?>
  <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
  <?php include_partial('story/storyActions', array('story' => $story));?>

  <?php if($story->type == Story::TYPE_NORMAL):?>
    <div class="screenshot">
      <?php echo img_tag("http://stromboli.yigg.de/?url=" . $story->external_url, array("width" =>  120, "height" => 88));?>
    </div>
  <?php endif; ?>

    <div class="spreadly-button">
        <iframe src="http://button.spread.ly/?url=<?php echo urlencode($sf_request->getUriPrefix().url_for_story($story, false));?>&social=0&color=ff9500&title=<?php echo urlencode($story->title);?>"
                style="overflow:hidden; width: 175px; height: 30px; padding: 0px 0;"
                frameborder="0"
                scrolling="no"
                marginheight="0"
                allowTransparency="true">
        </iframe>
    </div>

   <h3 class="entry-title">
     <?php if($sf_request->getModuleAction() === "story/show")://$story["type"] == Story::TYPE_NORMAL?>
       <?php echo link_to($story->title, $story["external_url"], array("title" => $story->title, "rel" => "external"));?>
     <?php else:?>
       <?php echo link_to_story($story->title, $story, array("title" => $story->title));?>
     <?php endif; ?>
   </h3>

   <div class="body entry-content<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
     <p>
       <span>
         <?php echo avatar_tag($story->Author->Avatar, "icon.gif", 14, 14, array("alt" => "Avatar von {$story->Author->username}"));?>
         <?php echo link_to(
                  $story['Author']['username'],
                  "@user_public_profile?username={$story['Author']['username']}",
                   array('title' => "Profil von {$story['Author']['username']} besuchen"));?>
         -
           <?php if($sf_user->hasUser() && $sf_user->getUserId() !== $story->Author->id && false === $sf_user->getUser()->follows($story->Author)):?>
           <span class="ico ico-follow">
               <?php echo link_to(
               "Follow {$story['Author']['username']}",
               "@user_friends_modify?list=add&username={$story['Author']['username']}",
               array(
                   "rel" => "external",
                   "title" => 'Abonniere die Nachrichten von '  . $story['Author']['username'],
                   "class" => "follow"
               )
           );
               ?> </span>
           <?php endif;?>
         <abbr><?php echo format_date(strtotime($story['created_at']),"g","de",'UTF-8');?></abbr>
       </span>
       <?php if($sf_request->getModuleAction() !== "story/show"):?>
          <?php echo $story->getDescriptionSummary(ESC_RAW);?>
       <?php elseif($story->type != Story::TYPE_ARTICLE):?>
          <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
       <?php else:?>
          <?php echo $story->getPresentationDescription(ESC_RAW);?>
       <?php endif;?>
       <?php

         $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
         ?>
       <?php echo link_to('mehr bei '.$external_url_title['host'], $story["external_url"], array("title" => $story->title, "rel" => "nofollow","target"=> "_blank"));?>
     </p>

        <?php if(isset($total) && $total > 9 &&
              ($count == 1 || ($count == $total-1) || ($total > 12 && $count == round($total/2)-1))): ?>
          <?php use_helper("JavascriptBase");  ?>
            <!-- <div class="story-inline-advert"> -->
              <?php //echo adsense_ad_tag(6546807557, 468, 60);?>
            <!-- </div> -->
        <?php endif;?>

        <?php if($story->type == Story::TYPE_VIDEO): ?>
           <?php $video = yiggExternalVideoFactory::createFromUrl($story->external_url);
                echo false === empty($video) ? $video->render() : "" ; ?>
        <?php endif; ?>


         <?php if($sf_request->getModuleAction() === "story/show" && false === $sf_request->isAjaxRequest()): ?>

           <?php include_partial(
           	 "story/youtubeVideo",
               array(
               	"width" => 360,
               	"height"=> 280,
               	"story" => $story,
               )
             );
           ?>
           <!--<div class="story-inline-advert"> -->
             <?php //echo adsense_ad_tag(7012733842, 468, 60);?>
            <!-- </div> -->
          <?php endif; ?>

		    <div class="clr"><!--  --></div>

       <h3 class="comments <?php if($sf_request->getModuleAction() === "story/show" && false === $sf_request->isAjaxRequest()):?>heading-left<?php endif;?>">
           <?php if(true === $sf_user->hasUser()):?>
               <?php echo link_to_story(
                        "Kommentare: (".$story->currentCommentCount().") ",
                        $story,
                        array("title" => "Die Kommentare zu dieser Nachricht lesen &quot;{$story['title']}&quot;")
                     );
               ?>
           <?php else:?>
               <?php echo link_to(
                       "Kommentare: (".$story->currentCommentCount().") ",
                       "@user_register",
                       array("title"=>"Benutzeraccount erstellen")); ?>
           <?php endif;?>                      
       </h3>
    </div>

<?php
$story_tags = Doctrine_Query::create()
                      ->select('st.story_id,
                                s.title')
                      ->from("Story s")
                      ->leftJoin('s.StoryTag st')
                      
                      ->where("st.story_id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();
    //var_dump($story); 
    //echo $story['id'];

    /*$story_tags = Doctrine_Query::create()
                      ->select('st.story_id,
                                s.title')
                      ->from("Story s")
                      ->leftJoin('s.StoryTag st')
                      
                      ->where("s.id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();

    /*$story_tags = Doctrine_Query::create()
                      ->select('st.story_id,
                                s.title')
                      ->from("Story s")
                      ->leftJoin('s.StoryTag st')
                      
                      ->where("st.story_id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();*/
    
   /* $story_tags = Doctrine_Query::create()
                      ->select('st.story_id,
                                s2.title')
                      ->from("Story s")
                      ->leftJoin('s.StoryTag st')
                      
                      ->leftJoin('s.Story s2')
                      
                      ->where("st.story_id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();
    
    /*$story_tags = Doctrine_Query::create()
                      ->select('st.tag_id,
                                st2.story_id,
                                s.title')
                      ->from("story_tag st")
                      ->innerJoin("(SELECT tag_id,
                                        story_id
                                    FROM story_tag
                                    GROUP BY
                                    story_id
                                ) AS st2 ON st2.tag_id = st.tag_id")
                      ->leftJoin('story AS s ON s.id = st2.story_id')
                      ->where("st.story_id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();*/
    
    /*$story_tags = Doctrine_Query::create()
                      ->select('st2.story_id,
                                st2.story_title')
                      ->from("story s")
                      ->leftJoin('story_tag AS st ON s.id = st.story_id')
                      ->innerJoin("(SELECT st.tag_id,
                                        st.story_id,
                                        s.title AS story_title
                                    FROM story_tag AS st
                                    LEFT JOIN story AS s ON s.id = st.story_id
                                    GROUP BY
                                    st.story_id
                                ) AS st2 ON st2.tag_id = st.tag_id")
                      ->where("s.id = ?", $story['id'])
                      ->orderBy("RAND()")
                      ->limit(5)
                      ->execute();
   
 print_r($story_tags);
   
   /*
    SELECT 
        st2.story_id,
        st2.story_title
    FROM `story` AS s

    LEFT JOIN story_tag AS st ON s.id = st.story_id

    INNER JOIN 
    (SELECT st.tag_id,
            st.story_id,
            s.title AS story_title
        FROM story_tag AS st
        LEFT JOIN story AS s ON s.id = st.story_id
        GROUP BY
        st.story_id
    ) AS st2 ON st2.tag_id = st.tag_id

    WHERE s.id = 2639321
    ORDER BY RAND() LIMIT 0,5
    */
   
    
?>
<?php //foreach($story_tags as $story_tag):?>    
    <?php //echo link_to_story($story_tag->title, $story, array("title" => $story_tag->title)).'<br />';?>
<?php //endforeach;?>

    <?php if("story/show" === $sf_request->getModuleAction()): ?>
      <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false)); ?>
    <?php endif; ?>

   <div class="clr bth"></div>
<?php echo isset($total) && $total > 1 ? "</li>" : "</div>";?>