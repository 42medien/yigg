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
       <?php echo link_to($story->title, url_for_story($story, "bar"), array("title" => $story->title, 'target' => '_blank'));?>
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
         <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow"));?>
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

    <?php if("story/show" === $sf_request->getModuleAction()): ?>
      <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false)); ?>
      <?php       
        //echo $story['id'];
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $story_tags_sql = $q->execute("
                                SELECT st2.`story_id`, 
                                    s.`title` AS st_title,
                                    s.internal_url,
                                    s.created_at
                                FROM `story_tag` AS st 

                                INNER JOIN
                                    (
                                    SELECT st.`story_id`, 
                                            st.`tag_id`
                                    FROM `story_tag` AS st
                                    WHERE st.`story_id` != ".$story['id']."
                                    GROUP BY st.`story_id`
                                    ) AS st2 ON st2.tag_id = st.tag_id

                                LEFT JOIN story AS s ON s.id = st2.story_id

                                WHERE st.`story_id` = ".$story['id']."
                                ORDER BY RAND() LIMIT 0,5
                            ");

        $story_tags = $story_tags_sql->fetchAll();  
        //print_r($story_tags);
        ?>

      <?php if(count($story_tags) > 0): ?>
        <div style="font-size: 108% !important; font-weight: bold; margin-top: 10px; margin-bottom:3px;">Das könnte Sie auch interessieren:</div>
        <?php foreach($story_tags as $story_tag):?>
        <?php
            $year = substr($story_tag["created_at"], 0, 4);
            $month = substr($story_tag["created_at"], 5, 2);
            $day = substr($story_tag["created_at"], 8, 2);
            $route = "@story_show?slug={$story_tag["internal_url"]}&year=$year&month=$month&day=$day";

            echo link_to($story_tag['st_title'], $route, array("title" => $story_tag['st_title'])) . '<br />';
        ?>
        <?php endforeach;?> 
      <?php endif; ?>
    <?php endif; ?>

   <div class="clr bth"></div>
<?php echo isset($total) && $total > 1 ? "</li>" : "</div>";?>