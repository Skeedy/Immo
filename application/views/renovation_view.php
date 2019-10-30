<section class="annonce renovation">
    <div class="container">
        <h1 class="titre text-uppercase text-center"><?php echo $_annonce->titre; ?></h1>
        <h2 class="titre text-uppercase text-center"><?php echo $_annonce->nom_reel; ?></h2>
        <div class="tiret_vertical"></div>
        <div class="constructor owl-carousel owl-theme" data-nodots>
            <?php
            $captions = array();
            $i = 0;
            foreach ($_annonce->images as $img) {
                $legende = !empty(__lang($img->legende)) ? __lang($img->legende) : '';
                if( !empty($legende) && !array_key_exists($legende, $captions) )
                    $captions[$legende] = $i;
                ?>
                <div class="item">
                    <a data-fancybox="images" data-index="<?php echo $i; ?>" href="<?php echo _ROOT.$db_renovation->getRenovationDirImg($_annonce->id).'lg_'.$img->image; ?>" class="link">
                        <img src="<?php echo _ROOT.$db_renovation->getRenovationDirImg($_annonce->id).'lg_'.$img->image; ?>" alt="<?php echo escHtml($legende); ?>">
                    </a>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
        <div class="my-5 py-3">
        	<div class="row">
        		<div class="col-md-6 offset-md-3 text-center">
           			<h4 class="subtitle_biens text-uppercase mx-auto">Description</h4>
           			<div class="description"><?php echo nl2br(escHtml(__lang($_data->description))); ?></div>
           		</div>
           	</div>
        </div>

        <?php
        if( !empty($_annonce->comparaisons) && count($_annonce->comparaisons) >= 2 ) {
        	?>
        	<div class="my-5 py-3">
        		<?php
        		for($i = 0; $i < count($_annonce->comparaisons); $i+=2 ) {
        			?>
        			<div class="twentytwenty-container">
        				<img src="<?php echo _ROOT.$db_renovation->getRenovationDirComp($_annonce->id).'lg_'.$_annonce->comparaisons[$i]->image; ?>" alt="">
        				<img src="<?php echo _ROOT.$db_renovation->getRenovationDirComp($_annonce->id).'lg_'.$_annonce->comparaisons[$i+1]->image; ?>" alt="">
        			</div>
        			<?php
        		}
        		?>
        	</div>
        	<?php
        }
        ?>
        <div class="mt-5 text-center">
        	<a class="link_arrow" href="<?php echo _ROOT_LANG . $_page_contact->url; ?>">
				<span class="arrow"></span>Contactez-nous<br>pour tout renseignement<br>complémentaire
			</a>
        </div>
        <div class="tiret"></div>
        <div class="vertical-space60"></div>
        <?php
        if( !empty($_annonce->lat) && !empty($_annonce->lng) ) {
        	?>
       		<div id="mapid" data-lat="<?php echo $_annonce->lat; ?>" data-lng="<?php echo $_annonce->lng; ?>"></div>
       		<?php
       	}
       	?>
    </div>

</section>