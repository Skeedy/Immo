<?php
if( !empty($data_immeuble->immeuble_diaporama_images) ) {
    ?>
<div class="tiret_vertical"></div>
	<div class="owl-carousel owl-theme slider-home" data-autoplay >
        <?php
        foreach ($data_immeuble->immeuble_diaporama_images as $img) {
            ?>
			<div class="item" style="padding-top: 58%; background-image:url('<?php echo _ROOT . _DIR_MEDIA. $img; ?>');">
			</div>
            <?php
        }
        ?>
	</div>
    <?php
}
?>
