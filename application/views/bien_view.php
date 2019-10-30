<?php
if (!empty($_data->normal))
    parseContent($_data->normal);
if (!empty($_filter['label']) && $_filter['label'] == 'frimmeuble'){
	?>

<div class="container">
	<div class="vertical-space60"></div>
	<div class="row justify-content-center ">
		<div class="col-sm-6 d-flex flex-column">
			<p class="text-center">
				Depuis 2015, notre cabinet s’est spécialisé dans la recherche et la cession d’immeubles et d’ensemble immobilier pour le compte de clients particuliers, professionnels et institutionnels. Une rencontre préalable est indispensable
				afin d’étudier ensemble ce type de projet immobilier.
			</p>
			<p class="m-0 text-center">
				<strong>Pour toutes demandes concernant les immeubles,</strong>
			</p>
				<a href="<?php echo _ROOT.$_page_contact->url ;?>" class="text-uppercase text-center red_immeuble font-weight-bold">
					veuillez nous contacter.
				</a>
		</div>
	</div>

</div>
    <?php
    include_once 'page_immeuble_view.php';
    ?>
	<?php
}
else {
    if (!empty($_biens->annonces)) {
        foreach ($_biens->annonces as $bien) {
            $titre = json_decode($bien->titre);
            $label = json_decode($bien->type_label);
            $images = json_decode($bien->images);
            $data = json_decode($bien->data);

            ?>
			<div class="<?php if ($_biens->total < 9) {
                echo 'col-sm-6';
            } else {
                echo 'col-sm-4';
            }; ?>">
				<div class="sold">
					<a href="<?php echo _ROOT_LANG . $bien->id . '-' . clean_str(__lang($titre)); ?>">
						<img src="<?php echo _ROOT . $db_annonce->getAnnonceDirImg($bien->id) . 'md_' . $images[0]->image; ?>">
						<span class="filtre">
                            <span class="titre">
	                            <span class="text-uppercase type"><?php echo empty($_filter['label'])? __lang($label): ''; ?></span>
	                            <span class="text-uppercase type"><?php echo !empty($data->quartier) ? $data->quartier : ''; ?></span>
	                            <span class="text-uppercase type"><?php echo number_format($bien->prix, 0, ',',' '). ' €';?></span>
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
    } else {
        ?>
		<div class="col my-3">
			<div class="no-annonce" style="background: url('<?php echo _ROOT._DIR_MEDIA.escHtml($_parametres['1']->value);?>');">
			</div>
			<div class="msg-no-annonce" >
				Nous n'avons<br>actuellement aucun<br>bien de ce type à l'achat
			</div>
		</div>
        <?php
    }
}