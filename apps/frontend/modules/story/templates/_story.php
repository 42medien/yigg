<?php echo tag( isset($total) && $total > 1 ? "li" : "div", array("class"=>"hentry post ", "id"=>"story_{$story['id']}"), true);?>
  <?php use_helper("Date"); ?>
  <?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
  <?php include_partial('story/storyActions', array('story' => $story));?>

  <?php if($story->type == Story::TYPE_NORMAL):?>
    <div class="screenshot">
      <?php echo link_to(img_tag("http://stromboli.yigg.de/?url=" . $story->external_url, array("width" =>  120, "height" => 88)), $story->external_url, array("title" => $story->title, "rel" => "external"));?>
    </div>
  <?php endif; ?>
   <h3 class="entry-title">
     <?php if(false):?>  //$story["type"] == Story::TYPE_NORMAL
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
       <?php echo link_to('mehr bei '.$external_url_title['host'], $story["external_url"], array("title" => $story->title, "rel" => "nofollow"));?>
     </p>

        <?php if(isset($total) && $total > 9 &&
              ($count == 1 || ($count == $total-1) || ($total > 12 && $count == round($total/2)-1))): ?>
          <?php use_helper("JavascriptBase");  ?>
            <div class="story-inline-advert">
              <?php echo adsense_ad_tag(6546807557, 468, 60);?>
            </div>
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
           <div class="story-inline-advert">
             <?php echo adsense_ad_tag(7012733842, 468, 60);?>
            </div>
          <?php endif; ?>

		    <div class="clr"><!--  --></div>

       <h3 class="comments <?php if($sf_request->getModuleAction() === "story/show" && false === $sf_request->isAjaxRequest()):?>heading-left<?php endif;?>"><?php echo link_to_story(
       "Kommmentare: (".$story->currentCommentCount().") ",
       $story,
       array(
         "title" => "Die Kommentare zu dieser Nachricht lesen &quot;{$story['title']}&quot;"
       )
     );?></h3>
    </div>

    <?php if("story/show" === $sf_request->getModuleAction()): ?>
      <?php include_component("comment", "commentList", array("obj" => $story, "inlist" => isset($inlist)  ? $inlist : false)); ?>
    <?php endif; ?>

   <div class="clr bth"></div>
<?php echo isset($total) && $total > 1 ? "</li>" : "</div>";?>