<form method="post" class="form-ajax mt-3 form-trouver" id="form_trouver" action="<?php echo _ROOT_LANG; ?>">
	<input type="hidden" name="form" value="trouver">

	<div class="row">
		<div class="col-md-6 mx-auto">
			<div class="form-title text-center">Vos coordonnées</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="" for="frm_trouver_nom">Nom*</label>
						<input id="frm_trouver_nom" type="text" name="nom" class="form-control" required>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="" for="frm_trouver_prenom">Prénom*</label>
						<input id="frm_trouver_prenom" type="text" name="prenom" class="form-control" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="" for="frm_trouver_telephone">Téléphone*</label>
						<input id="frm_trouver_telephone" type="text" name="telephone" class="form-control" required>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="" for="frm_trouver_email">Email*</label>
						<input id="frm_trouver_email" type="email" name="email" class="form-control" required>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-md-4">
			<div class="form-title">Votre achat</div>
			<div class="form-group">
				<label class="" for="frm_trouver_type">Type de bien</label>
				<select id="frm_trouver_type" name="type" class="form-control">
					<?php
					$values = array('',
						'Appartement',
						'Maison',
						'Terrain',
						'Immeuble',
						'Bureaux',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_type_achat">Type d'achat</label>
				<select id="frm_trouver_type_achat" name="type_achat" class="form-control">
					<?php
					$values = array('',
						'Résidence principale',
						'Investissement locatif',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-title">Caractéristiques</div>
			<div class="form-group range-div">
				<label for="amount">Surface habitable:</label>
				<input type="text" class="js-range-slider" name="my_range" value="" />
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_pieces">Nombre de pièces</label>
				<input id="frm_trouver_pieces" type="number" name="pieces" class="form-control">
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_chambres">Nombre de chambres minimum</label>
				<input id="frm_trouver_chambres" type="number" name="chambres" class="form-control">
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_type_immeuble">Type d'immeuble</label>
				<select id="frm_trouver_type_immeuble" name="type_immeuble" class="form-control">
					<?php
					$values = array('',
						'Ancien uniquement',
						'Neuf uniquement',
						'Neuf ou Ancien',
						'Neuf (postérieur à 1980 uniquement)',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_ascenseur">Ascenseur</label>
				<select id="frm_trouver_ascenseur" name="ascenseur" class="form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_sortie_exterieure">Sortie extérieure</label>
				<select id="frm_trouver_sortie_exterieure" name="sortie_exterieure" class="form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_rdc">J'accepte un rez-de-chaussée</label>
				<select id="frm_trouver_rdc" name="rdc" class="form-control test-select">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group mt-3">
				<label class="" for="frm_trouver_commentaire">Commentaire</label>
				<textarea id="frm_trouver_commentaire" name="commentaire" class="form-control" rows="2"></textarea>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-title">Localisation</div>
			<div class="form-group">
				<label class="" for="frm_trouver_quartier">Vos quartiers de préférence</label>
				<select id="frm_trouver_quartier" name="quartier[]" class="form-control selectpicker" multiple data-selected-text-format="count" title="">
					<?php
					$values = array(
						'Grands Hommes',
						'Quinconces',
						'Jardin Public',
						'Chartrons - Saint Martial',
						'Bassin à Flots',
						'Saint Louis',
						'Saint Pierre',
						'Saint Paul',
						'Saint Michel',
						'Sainte Croix',
						'Pey Berland - Saint Christoly',
						'Saint Sernin',
						'Palais Gallien',
						'Saint Seurin',
						'Tivoli - Parc Rivière',
						'Croix de Seguey',
						'Croix Blanche',
						'Meriadeck',
						'Sainte Eulalie',
						'Victoire',
						'Saint Genes',
						'Saint Nicolas',
						'Somme',
						'Marne - Gare St Jean',
						'Ornano',
						'Rive Droite',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_precisions">Vos précisions</label>
				<textarea id="frm_trouver_precisions" name="precisions" class="form-control" rows="2" ></textarea>
			</div>
			<div class="form-title">Financement</div>
			<div class="form-group">
				<label class="" for="frm_trouver_budget_max">Budget maximum</label>
				<input id="frm_trouver_budget_max" type="number" name="budget_max" class="form-control">
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_honoraire_inclus">Montant honoraire agence inclus</label>
				<select id="frm_trouver_honoraire_inclus" name="honoraire_inclus" class="form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_travaux_inclus">Travaux inclus</label>
				<select id="frm_trouver_travaux_inclus" name="travaux_inclus" class="hidden_input form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group d-none">
				<label class="" for="frm_trouver_travaux_montant">Montant des travaux en €</label>
				<input id="frm_trouver_travaux_montant" type="number" name="travaux_montant" class="form-control">
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_droits_mutation_inclus">Droits de mutation inclus</label>
				<select id="frm_trouver_droits_mutation_inclus" name="droits_mutation_inclus" class="form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_budget_valide">Ce budget a été validé par votre banque</label>
				<select id="frm_trouver_budget_valide" name="budget_valide" class="form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_achat_personne">Achetez-vous en...</label>
				<select id="frm_trouver_achat_personne" name="achat_personne" class="form-control">
					<?php
					$values = array('',
						'En nom propre',
						'En société',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_apport">Disposez-vous d'un apport ?</label>
				<select id="frm_trouver_apport" name="apport" class="hidden_input form-control">
					<?php
					$values = array('',
						'Oui',
						'Non',
					);
					foreach( $values as $v )
						echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
					?>
				</select>
			</div>
			<div class="form-group d-none">
				<label class="" for="frm_trouver_apport_montant">Montant de l'apport en €</label>
				<input id="frm_trouver_apport_montant" type="number" name="apport_montant" class="form-control">
			</div>
		</div>
		<div class="col-md-4" id="recherche">
			<div class="form-title">Votre recherche</div>
			<div class="form-group">
				<label class="" for="frm_trouver_debut_recherche">Quand a débuté votre recherche ?</label>
				<select id="frm_trouver_debut_recherche" name="debut_recherche" class="form-control">
                    <?php
                    $values = array('',
                        'Je commence juste',
                        'Il y a 3 mois',
	                    'Il y a 6 mois',
	                    'Plus d’un an',
	                    'Je ne sais plus'
                    );
                    foreach( $values as $v )
                        echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
                    ?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_nb_visites">Nombre de visites réalisées</label>
				<select id="frm_trouver_apport" name="debutnb_visites_recherche" class="form-control">
                    <?php
                    $values = array('',
                        'Moins de 5',
                        'Une dizaine',
                        'Plus de 20',
                        'Je ne compte plus!'
                    );
                    foreach( $values as $v )
                        echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
                    ?>
				</select>
			</div>

			<div class="form-title">Vous êtes</div>
			<div class="form-group">
				<label class="bmd-label-floating" for="frm_trouver_cdi">Êtes-vous tous en CDI ?</label>
				<select id="frm_trouver_cdi" name="cdi" class="form-control">
                    <?php
                    $values = array('',
                        'Oui',
                        'Non',
                    );
                    foreach( $values as $v )
                        echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
                    ?>
				</select>
			</div>
			<div class="form-group">
				<label class="" for="frm_trouver_nb_acheteurs">Quel est le nombre d'acheteurs ?</label>
				<select id="frm_trouver_nb_acheteurs" name="nb_acheteurs" class="form-control">
                    <?php
                    $values = array('',
                        '1',
                        '2',
                        '3',
                    );
                    foreach( $values as $v )
                        echo '<option value="' . escHtml($v) . '">' . $v . '</option>';
                    ?>
				</select>
			</div>
			<div id="container_acheteurs"></div>



			<div class="form-group check">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="rappel" id="frm_trouver_rappel">
					<label class="form-check-label form-title text-uppercase" for="frm_trouver_rappel">Je souhaite être rappelé par le cabinet</label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn submit_button nofocus">Je soumets ma demande</button>
	</div>
	<div class="frm_zone_message text-center"></div>
</form>
