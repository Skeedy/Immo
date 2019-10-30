<?php
if( !empty($sold->annonces) ) {
    foreach ($sold->annonces as $s) {
        $titre = json_decode($s->titre);
        $label = json_decode($s->type_label);
        $images = json_decode($s->images);
        $data = json_decode($s->data);
        ?>
        <div class="col-sm-6">
            <div class="sold">
	            <a href="<?php echo _ROOT_LANG . $s->id . '-' . clean_str(__lang($titre)); ?>">
		            <img src="<?php echo _ROOT . $db_annonce->getAnnonceDirImg($s->id) . 'md_' . $images[0]->image; ?>">
		            <span class="filtre">
                            <span class="titre">
	                            <span class="text-uppercase type"><?php echo empty($_filter['label'])? __lang($label): ''; ?></span>
	                            <span class="text-uppercase type"><?php echo !empty($data->quartier)? $data->quartier : ''?></span>
								<?php
                                if (!empty($data->tier) && !empty($data->nb_chambre)){
                                    ?>
									<span class="text-uppercase"><?php echo $data->tier.' / ' .$data->nb_chambre . ($data->nb_chambre == 1 ? ' chambre': ' chambres');?>  </span>
                                    <?php
                                }
                                ?>
                            </span>
                        </span>
	            </a>
            </div>
        </div>
        <?php
    }
}
?>
