<div class="box">
	<h1 class="text-uppercase">Cabinet immobilier indépendant</h1>
</div>
<?php

if( !empty($_data->home_diaporama_images) ) {
    ?>
    <div class="owl-carousel owl-theme slider-home" data-autoplay data-nonav>
        <?php
        foreach ($_data->home_diaporama_images as $img) {
            ?>
            <div class="item" style="background-image:url('<?php echo _ROOT . _DIR_MEDIA. $img; ?>');">

            </div>
            <?php
        }
        ?>
    </div>

    <?php
}
?>
