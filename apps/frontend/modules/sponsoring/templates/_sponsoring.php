<div id="sponsoring_<?php echo $place->id; ?>" class="sponsoring <?php echo $place->width > 200 ? "banner": "navigation"; ?>">
    <?php if( isset($preview_image) || isset($preview_url) ): ?>
    <!-- Preview Sponsorship. -->
    <div class="sponsorship" >
        <?php echo link_to(
        avatar_tag($preview_image, "sponsoring-bg.png", $place->width, $place->height),
        isset($preview_url) ? $preview_url : "@about_faq",
        array('rel' => 'external')
    );
        ?>
    </div>
    <?php endif; ?>

    <!-- Sponsors -->
    <?php if( count($place_sponsors) > 0): ?>
    <?php foreach($place_sponsors as $sponsor): ?>
        <div class="sponsorship">
            <?php echo link_to(
            avatar_tag($sponsor->Image, "sponsoring-bg.png", $place->width, $place->height,
                array('title' => $sponsor->image_title)),
            "@sponsoring_call?id={$sponsor->id}",
            array('rel' => 'external')
        );
            ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if( is_object($place) && true === $place->isAvailable() && $place->placesLeft() >= 1  && (!isset($preview_image) && !isset($preview_url)) ): ?>
    <?php for($i=0; $i < $place->placesLeft(); $i++): ?>
        <!--  Sponsorship placeholders 
        <div class="sponsorship preview">
            <?php if(true): //$place->intern_url == "@best_stories" AND $place->width < 200?>
            <p><strong>Ihre Anzeige hier? Buchen Sie jetzt?</strong>
            <?php else:?>
            <p><strong>Diese Anzeige kostet <?php echo "{$place->price} Euro/Woche";?></strong>
            <?php endif;?>
            <?php echo link_to('Jetzt buchen!', $place->getOrderLink() , array("class"=>"button")) ?>
        </p>
        </div> -->
        <?php endfor;?>
    <?php endif; ?>
    <div class="clr"></div>
</div>