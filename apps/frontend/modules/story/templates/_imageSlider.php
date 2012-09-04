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
        </ul>
        <div class="clear"></div>
    </div>
</div>
<script>createSlider();</script>


