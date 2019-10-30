<?php
foreach ($_biens->annonces as $bien){
    $titre = $bien->titre;
    $images = json_decode($bien->images);
    $data = json_decode($bien->data);
    $date = new DateTime($bien->date_livraison);
    ?>
	<div class="col-sm-6">
		<div class="sold renov">
		<?php
		if (!$bien->isOver) {
							?>
			<div>
				<img src="<?php echo $db_renovation->getRenovationDirImg($bien->id) . 'md_' . $images[0]->image; ?>">
				<span class="filtre">
					<span class="titre">
						<span class="text-uppercase type">projet en cours</span><br>
							<span class="text-uppercase"> <?php echo $data->infos[0]; ?></span>
                            <span class="text-uppercase"> <?php echo $data->quartier; ?></span>
						<br>
						<a href="<?php echo _ROOT_LANG . $_page_contact->url; ?>" class="contact_link">
							<span class="link_arrow text-center">
								<span class="arrow"></span><span>nous contacter</span>
							</span>
						</a>
					</span>
				</span>
			</div>
            <?php
			} else {
            ?>
			<a href="<?php echo _ROOT_LANG . 'r' . $bien->id . '-' . clean_str($titre) ; ?>">
				<img src="<?php echo $db_renovation->getRenovationDirImg($bien->id) . 'md_' . $images[0]->image; ?>">
				<span class="filtre">
					<span class="titre">
						<span class="text-uppercase type">projet livré</span><br>
	                <span class="text-uppercase">livraison le</span>
						<span><?php echo $date->format('d/m/Y'); ?></span>
					</span>
				</span>
			</a>
			<?php
            }
            ?>
		</div>
	</div>
    <?php
}
?>

