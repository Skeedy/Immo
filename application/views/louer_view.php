<?php
require_once _DIR_VIEWS.'content_parser.php';


if (!empty($_data->normal))
    parseContent($_data->normal);
?>
<?php
foreach ($_biens->annonces as $bien){
    $titre = json_decode($bien->titre);
    $label = json_decode($bien->type_label);
    $images = json_decode($bien->images);
    $data = json_decode($bien->data);
    $date = new DateTime($bien->date_dispo);
    ?>
	<div class="<?php if( $_biens->total < 9 )
    { echo 'col-sm-6';}
    else { echo 'col-sm-4';
    }; ?>" >
		<div class="sold rent">
            <?php if ($bien->isDispo){
            ?>
			<a href="<?php echo _ROOT_LANG . $bien->id . '-' . clean_str(__lang($titre)) ; ?>">
				<img src="<?php echo _ROOT_LANG .$db_annonce->getAnnonceDirImg($bien->id) . 'md_' . $images[0]->image; ?>">
				<span class="filtre">
                    <span class="titre">
                        <span class="text-uppercase louer"><?php echo __lang($titre); ?></span>
	                    <br>
						<span class="text-uppercase"><?php echo number_format($bien->prix, 0, ',',' '). ' €';?> / mois</span>
						<span class="text-uppercase text-center">disponible à partir <br> du <?php echo $date->format('d/m/Y'); ?></span>
                    </span>
                </span>
			</a>
                    <?php
                }
                else {
                    ?>
			<div class="indispo">
				<img src="<?php echo _ROOT_LANG .$db_annonce->getAnnonceDirImg($bien->id) . 'md_' . $images[0]->image; ?>">
				<span class="filtre">
				<span class="titre">
					<span class="text-uppercase out text-center">indisponible <br> pour le moment</span>
					<br>
					<a data-fancybox data-type="ajax" href="<?php echo _ROOT_LANG . $bien->id . '-' . clean_str(__lang($titre)) ; ?>?avertir" class="link_arrow text-center">
						<span class="arrow"></span><span>m'avertir de son</span>
						<br>
						<span>retour par email</span>
					</a>
					<a href="<?php echo _ROOT_LANG . $bien->id . '-' . clean_str(__lang($titre)) ; ?>" class="text-uppercase button">aperçu du bien</a>
				</span>
				</span>
			</div>
                    <?php
                }
                ?>

		</div>

	</div>
    <?php
}
?>
