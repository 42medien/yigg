<div id="carousels">
    <div id="buttons">
        <a href="#" id="prev"></a>
        <a href="#" id="next"></a>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <div id="slides">
        <ul>
        <?php foreach($images as $image):?>
          <li>
            <img class="image-slider" src="<?php echo $image;?>"/>
          </li>
        <?php endforeach; ?>
          <li>
            No Image
          </li>
        </ul>
        <div class="clear"></div>
    </div>
    <p class="bt_info">Bitte wähle ein passendes Bild aus</p>
</div>
<script>createSlider();</script>