<?php echo tag( isset($total) && $total > 1 ? "li" : "div", array("class"=>"hentry post ", "id"=>"story_{$story['id']}"), true);?>
  <?php use_helper("Date"); ?>
  
  <?php include_partial('story/storyActions', array('story' => $story));?>

   <h3 class="entry-title">
     <?php if($sf_request->getModuleAction() === "story/show")://$story["type"] == Story::TYPE_NORMAL?>
       <?php echo link_to($story->title, url_for_story($story, "bar"), array("title" => $story->title, 'target' => '_blank'));?>
     <?php else:?>
       <?php echo link_to_story(preg_replace('/\s+?(\S+)?$/', '', substr($story->title, 0, 75))." ...", $story, array("title" => $story->title));?>
     <?php endif; ?>
       <?php
       echo link_to(img_tag('external_link.png', array(
               'alt' => $story->title,
               'width' => 17,
               'height' => 9
           )),
           $story["external_url"],
           array(
               'title' => $story->title,
               'class' => 'logo',
               'target' => '_blank'
           ));
       ?>
   </h3>
 
<script>
var img_resize = function(img, maxh, maxw) {
  var ratio = maxh / maxw;

  var h = img.clientHeight;
  var w = img.clientWidth;

  if (h / w > ratio){
     // height is the problem
    if (h > maxh){
      img.width = Math.round(w * (maxh / h));
      img.height = maxh;
    }
  } else {
    // width is the problem
    if (w > maxh){
      img.height = Math.round(h * (maxw / w));
      img.width = maxw;
    }
  }
};

jQuery("img.js-resize").ready(function(){
	jQuery("img.js-resize").each(function(){
		var h = jQuery(this).parent(".screenshot").innerHeight();
		var w = jQuery(this).parent(".screenshot").innerWidth();
		//console.log("resising WxH = " + w + "x" + h);
		img_resize(this, h, w);
	});

	jQuery(".screenshot").css("background", "none");
});
</script>

 <div class="list_cont">
  <?php if($story->type == Story::TYPE_NORMAL):?>
    <div class="screenshot">
        <?php
        $source = $story->getStoryImageSource();
        if($source){
            echo img_tag($story->getStoryImageSource(), array(
			'data-w' => 130,
			'data-h' => 93,
			'class' => 'js-resize'	
		));
        }else{
            echo img_tag("http://stromboli.yigg.de/?url=" . $story->external_url, array());
        }

        ?>
    </div>
  <?php endif; ?>


   <div class="body entry-content<?php echo ($story["type"] === Story::TYPE_VIDEO) ? " video":"";?>">
     <p>
       
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
         
       <span class="story_auth"> 
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
        
       <span class="story_body">         
       <?php if($sf_request->getModuleAction() !== "story/show"):?>
          <?php echo $story->getDescriptionSummary(ESC_RAW);?>
       <?php elseif($story->type != Story::TYPE_ARTICLE):?>
          <?php echo $story->getDescriptionSummary(600, ESC_RAW);?>
       <?php else:?>
          <?php echo $story->getPresentationDescription(ESC_RAW);?>
       <?php endif;?>
         </span>
       <?php

         $external_url_title = parse_url(str_replace('www.','',$story["external_url"]))
         ?>
         <?php //echo link_to($story->title, $story["external_url"], array("title" => $story->title, "rel" => "external"));?>
         <?php echo link_to('mehr bei '.$external_url_title['host'], url_for_story($story, "bar"), array("title" => $story->title, "rel" => "nofollow", 'target' => '_blank'));?>
     </p>

        <?php if(isset($total) && $total > 9 &&
              ($count == 1 || ($count == $total-1) || ($total > 12 && $count == round($total/2)-1))): ?>
          <?php use_helper("JavascriptBase");  ?>
            <!-- <div class="story-inline-advert"> -->
              <?php //echo adsense_ad_tag(6546807557, 468, 60);?>
            <!-- </div> -->
        <?php endif;?>

        

		    <div class="clr"><!--  --></div>                  
        </div>
    </div>
<span class="hlp_txt fst">Die News gef&auml;llt Dir? Gib ihr ein yigg!</span>
<div class="clear"></div>
<?php include_component( 'story', 'rateStory',  array('story' => $story, 'completeStory' => true)); ?>
<div class="story_bt_data">   
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
    <?php if($sf_request->getModuleAction() === "story/show"):?>       
		<div style="width: 100%">
           <span class="hlp_txt_spread">Teile die News mit Deinen Freunden!</span>
           <iframe class="spreadly-button" frameBorder="0" scrolling="no" style="border: 0; height: 29px;" src="http://button.spread.ly/?url=<?php echo urlencode($sf_request->getUriPrefix().url_for_story($story, false)) ?>"></iframe>
           <?php /*<a href="<?php echo $sf_request->getUriPrefix().url_for_story($story, false);?>" 
            title="<?php echo $story->title;?>"
            class="spreadly-button" 
            rel="share like">
          </a>*/ ?>   
        </div>      
       <?php endif;?>        
        <?php
            $source = $story->getStoryImageSource();
            if($source){
                $img_source = $sf_request->getUriPrefix().'/'.$source;
            }else{
                $img_source = "http://stromboli.yigg.de/?url=" . $story->external_url;
            }
            $js_id = $story['id'];
            $js_title = $story['title'];
            $js_url = $sf_request->getUriPrefix().url_for_story($story, false);
            $js_text = addslashes($story->getDescriptionSummary(100, ESC_RAW));
            $js_img = $img_source;
        ?>
        <div id="plista_widget_standard_1"></div>
        <div id="plista_widget_belowArticle"></div>
        <script type="text/javascript" src="http://static.plista.com/fullplista/54257f4f1c2c966980b63b2c.js"></script> 
        <script type="text/javascript">
            PLISTA.items.push({                
                objectid: "<?php echo $js_id; ?>",
                title: "<?php echo $js_title; ?>",
                url: "<?php echo $js_url; ?>",
                text: "<?php echo $js_text; ?>",
                img: "<?php echo $js_img; ?>"
            });

            PLISTA.partner.init();
        </script>   
        <div id="related_stories">            
            <?php include_partial('relatedStories',  array('stories' => $relatedStories, 'bar' => false)); ?>            
        </div>
    <?php endif; ?>

   <div class="clr bth"></div>
<?php echo isset($total) && $total > 1 ? "</li>" : "</div>";?>
