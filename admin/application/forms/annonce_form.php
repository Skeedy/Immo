<?php

//active
if(!empty($_POST))
    $frm_annonce_active = !empty($_POST['active']) ? $_POST['active'] : 0;
else if(isset($_annonce->active))
    $frm_annonce_active = $_annonce->active;
else
    $frm_annonce_active = 0;

//isLocation
if(!empty($_POST))
    $frm_annonce_isLocation = !empty($_POST['isLocation']) ? $_POST['isLocation'] : 0;
else if(isset($_annonce->isLocation))
    $frm_annonce_isLocation = $_annonce->isLocation;
else
    $frm_annonce_isLocation = 0;

//isDipos (location)
if(!empty($_POST))
    $frm_annonce_dispo = !empty($_POST['isDispo']) ? $_POST['isDispo'] : 0;
else if(isset($_annonce->isLocation))
    $frm_annonce_dispo = $_annonce->isDispo;
else
    $frm_annonce_dispo = 0;

//vente
if(!empty($_POST))
    $frm_annonce_vente = !empty($_POST['vente']) ? $_POST['vente'] : 0;
else if(isset($_annonce->date_vente))
    $frm_annonce_vente = !empty($_annonce->date_vente) ? 1 : 0;
else
    $frm_annonce_vente = 0;

//date_vente
if(!empty($_POST))
    $frm_annonce_date_vente = !empty($_POST['date_vente']) ? $_POST['date_vente'] : 0;
else if(isset($_annonce->date_vente))
    $frm_annonce_date_vente = date_create($_annonce->date_vente)->format('d/m/Y');
else
    $frm_annonce_date_vente = date('d/m/Y');

//date_dispo
if(!empty($_POST['date_dispo']))
    $frm_annonce_date_dispo = $_POST['date_dispo'];
else if(!empty($_annonce->date_dispo))
    $frm_annonce_date_dispo = date_create($_annonce->date_dispo)->format('d/m/Y');
else
    $frm_annonce_date_dispo = '';

//ref
if(isset($_POST['ref']))
    $frm_annonce_ref = $_POST['ref'];
else if(isset($_annonce->ref))
    $frm_annonce_ref = $_annonce->ref;
else
    $frm_annonce_ref = '';

//titre
if(isset($_POST['titre']))
    $frm_annonce_titre = json_decode(json_encode($_POST['titre']));
else if(isset($_annonce->titre))
    $frm_annonce_titre = json_decode($_annonce->titre);
else
    $frm_annonce_titre = new stdClass();

//pieces
if(isset($_POST['pieces']))
    $frm_annonce_pieces = $_POST['pieces'];
else if(isset($_annonce->pieces))
    $frm_annonce_pieces = $_annonce->pieces;
else
    $frm_annonce_pieces = '';

//type
if(isset($_POST['type']))
    $frm_annonce_type = $_POST['type'];
else if(isset($_annonce->type))
    $frm_annonce_type = $_annonce->type;
else
    $frm_annonce_type = '';

//superficie
if(isset($_POST['superficie']))
    $frm_annonce_superficie = $_POST['superficie'];
else if(isset($_annonce->superficie))
    $frm_annonce_superficie = $_annonce->superficie;
else
    $frm_annonce_superficie = '';

//prix
if(isset($_POST['prix']))
    $frm_annonce_prix = $_POST['prix'];
else if(isset($_annonce->prix))
    $frm_annonce_prix = $_annonce->prix;
else
    $frm_annonce_prix = '';

//adresse
if(isset($_POST['adresse']))
    $frm_annonce_adresse = $_POST['adresse'];
else if(isset($_annonce->adresse))
    $frm_annonce_adresse = $_annonce->adresse;
else
    $frm_annonce_adresse = '';

//cp
if(isset($_POST['cp']))
    $frm_annonce_cp = $_POST['cp'];
else if(isset($_annonce->cp))
    $frm_annonce_cp = $_annonce->cp;
else
    $frm_annonce_cp = '';

//ville
if(isset($_POST['ville']))
    $frm_annonce_ville = $_POST['ville'];
else if(isset($_annonce->ville))
    $frm_annonce_ville = $_annonce->ville;
else
    $frm_annonce_ville = '';

//ville_nom
if(isset($_POST['ville_nom']))
    $frm_annonce_ville_nom = $_POST['ville_nom'];
else if(isset($_annonce->ville_nom))
    $frm_annonce_ville_nom = $_annonce->ville_nom;
else
    $frm_annonce_ville_nom = '';

//lat
if(isset($_POST['lat']))
    $frm_annonce_lat = $_POST['lat'];
else if(isset($_annonce->lat))
    $frm_annonce_lat = $_annonce->lat;
else
    $frm_annonce_lat = '';

//lat
if(isset($_POST['lng']))
    $frm_annonce_lng = $_POST['lng'];
else if(isset($_annonce->lng))
    $frm_annonce_lng = $_annonce->lng;
else
    $frm_annonce_lng = '';


//images
if(isset($_POST['images'])) {
    $frm_annonce_images = array();
    for($i = 0; $i < count($_POST['images']); $i++) {
        $t = new stdClass();
        $t->image = $_POST['images'][$i];
        $t->legende = new stdClass();
        foreach($_LANGS as $l => $ll)
            $t->legende->{$l} = $_POST['images_legend'][$l][$i];
        $frm_annonce_images[] = $t;
    }
}
else if(isset($_annonce->images))
    $frm_annonce_images = json_decode($_annonce->images);
else
    $frm_annonce_images = array();
//image->info
if(isset($_POST['data']['image'])) {
    $frm_info_images = array();
    for($i = 0; $i < count($_POST['data']['image']); $i++) {
        $t = new stdClass();
        $t->image = $_POST['data']['image'][$i];
        $t->legende = new stdClass();
        foreach($_LANGS as $l => $ll)
            $t->legende->{$l} = $_POST['data']['image_legend'][$l][$i];
        $frm_info_images[] = $t;
    }
}
//data
if(isset($_POST['data']))
    $frm_annonce_data = json_decode(json_encode($_POST['data']));
else if(!empty($_data))
    $frm_annonce_data = $_data;
else
    $frm_annonce_data = new stdClass();

//equipier
if(isset($_POST['equipe']))
    $frm_annonce_equipier = json_decode(json_encode($_POST['equipe']));
else if(!empty($_equipier))
    $frm_annonce_equipier = $_data;
else
    $frm_annonce_equipier = '';

?>
<ol class="breadcrumb quickaccess">
	<li>Accès rapide : </li>
	<li><a href="#anchor-publication">Publication</a></li>
	<li><a href="#anchor-seo">SEO</a></li>
	<li><a href="#anchor-photos">Photos</a></li>
	<li><a href="#anchor-description">Description</a></li>
	<li><a href="#anchor-localisation">Localisation</a></li>
	<li><a href="#anchor-enregistrer">Enregistrer</a></li>
</ol>

<form method="post">

	<h2 id="anchor-publication">Publication</h2><hr>

	<h3 class="cat">Titre du bien</h3>
	<fieldset class="well">
        <?php
        foreach($_LANGS as $l => $ll) {
            ?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label class="required" for="frm_annonce_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
                <?php printToggleLang(); ?>
				<input type="text" id="frm_annonce_titre_<?php echo $l; ?>" name="titre[<?php echo $l; ?>]" class="form-control" value="<?php echo !empty($frm_annonce_titre->{$l}) ? escHtml($frm_annonce_titre->{$l}) : ''; ?>">
			</div>
            <?php
        }
        ?>
	</fieldset>

	<div class="row">
		<div class="col-sm-6">
			<h3 class="cat">Offre et type du bien</h3>
			<fieldset class="well">
				<div class="form-group">
					<label class="required" for="frm_annonce_ref">Ref</label>
					<input type="text" id="frm_annonce_ref" name="ref" class="form-control" value="<?php echo escHtml($frm_annonce_ref); ?>" required>
				</div>
				<div class="form-group">
					<label class="required" for="frm_annonce_type">Type</label>
					<select id="frm_annonce_type" name="type" class="form-control" required>
                        <?php
                        foreach($_types as $v) {
                            $v->label = json_decode($v->label);
                            echo '<option value="'.$v->id.'"'.($frm_annonce_type == $v->id ? ' selected' : '').'>'.escHtml($v->label->{_LANG_DEFAULT}).'</option>';
                        }
                        ?>
					</select>
				</div>
				<div class="form-group">
					<label class="required" for="frm_annonce_pieces">Nombre de pièces</label>
					<input type="number" id="frm_annonce_pieces" name="pieces" class="form-control" value="<?php echo escHtml($frm_annonce_pieces); ?>" required>
				</div>
				<div class="form-group">
					<label class="required" for="frm_annonce_isLocation">Type</label>
					<select id="frm_annonce_isLocation" name="isLocation" class="form-control" required>
						<option value="0" <?php echo !$frm_annonce_isLocation? 'selected': ''?>> Vente</option>
						<option value="1" <?php echo $frm_annonce_isLocation? 'selected': ''?>> Location</option>
					</select>
				</div>

			</fieldset>
		</div>
		<div class="col-sm-6">
			<h3 class="cat">Options de publication</h3>
			<fieldset class="well">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="frm_annonce_active">Publier le bien sur le site</label>
							<div>
								<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
								<input type="hidden" id="frm_annonce_active" name="active" value="<?php echo escHtml($frm_annonce_active); ?>">
							</div>
						</div>
						<div id="forSale" class="<?php echo !$frm_annonce_isLocation? '' : ' hidden'; ?>">
							<div  class="form-group" >
								<label for="frm_annonce_vente">Le bien a été vendu</label>
								<div>
									<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
									<input type="hidden" id="frm_annonce_vente" name="vente" value="<?php echo escHtml($frm_annonce_vente); ?>">
								</div>
							</div>

							<div id="container_date_vente" class="form-group<?php if(empty($frm_annonce_vente)) echo ' hidden'; ?>">

								<label for="frm_annonce_date_vente">Date de la vente</label><br>
								<input type="text" id="frm_annonce_date_vente" name="date_vente" class="form-control short datepicker" value="<?php echo escHtml($frm_annonce_date_vente); ?>">
							</div>
						</div>
						<div id="forRent" class="<?php echo $frm_annonce_isLocation? '' : ' hidden'; ?>">
							<div  class="form-group" >
								<label for="frm_annonce_louer">Le bien est disponible</label>
								<div>
									<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
									<input type="hidden" id="frm_annonce_louer" name="isDispo" value="<?php echo escHtml($frm_annonce_dispo); ?>">
								</div>
							</div>
							<div id="container_date_dispo" class="form-group<?php if(empty($frm_annonce_date_dispo)) echo ' hidden'; ?>">

								<label for="frm_annonce_date_dispo">Date disponibilité</label><br>
								<input type="text" id="frm_annonce_date_dispo" name="date_dispo" class="form-control short datepicker" value="<?php echo !empty($frm_annonce_date_dispo) ? escHtml($frm_annonce_date_dispo) : ''; ?>">
							</div>
						</div>
					</div>
					<div id="rent_info" class="col-sm-6 <?php echo $frm_annonce_type == 2? '' : ' hidden'; ?>">
						<div class="form-group">
							<label class="required" for="frm_annonce_rent_lot">Nombre de lots</label>
							<input type="number" id="frm_annonce_rent_lot" name="data[nb_rent_lot]" class="form-control" value="<?php echo escHtml($frm_annonce_data->nb_rent_lot); ?>">
						</div>
						<div class="form-group">
							<label class="required" for="frm_annonce_rent_copro">Montant charge coproprièté</label>
							<input type="number" id="frm_annonce_rent_copro" name="data[nb_rent_copro]" class="form-control" value="<?php echo escHtml($frm_annonce_data->nb_rent_copro); ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="required" for="frm_annonce_tier">Type</label>
					<select id="frm_annonce_tier" name="data[tier]" class="form-control">
                        <?php $value = array('T1', 'T2', 'T3', 'T4', 'villa' );
                        foreach ($value as $v){
                            echo '<option '.(!$frm_annonce_data->tier? 'selected':'').' value="'.$v.'">'. $v .'</option>';
                        }
                        ?>
					</select>
				</div>
				<div class="form-group">
					<label class="required" for="frm_annonce_chambre">Nombre de chambres</label>
					<input type="number" id="frm_annonce_chambre" name="data[nb_chambre]" class="form-control" value="<?php echo escHtml($frm_annonce_data->nb_chambre); ?>">
				</div>
			</fieldset>
		</div>
	</div>

	<br>
	<h2 id="anchor-seo">SEO</h2><hr>

	<h3 class="cat">Champs utilisés par les moteurs de recherche</h3>
	<fieldset class="well">
        <?php
        foreach($_LANGS as $l => $ll) {
            ?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label for="frm_annonce_meta_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
                <?php printToggleLang(); ?>
				<input type="text" id="frm_annonce_meta_titre_<?php echo $l; ?>" name="data[meta_titre][<?php echo $l; ?>]" class="form-control" value="<?php echo !empty($frm_annonce_data->meta_titre->{$l}) ? escHtml($frm_annonce_data->meta_titre->{$l}) : ''; ?>">
				<div class="help-block">Affichage optimal : entre 50 et 80 caractères</div>
			</div>
            <?php
        }
        ?>

        <?php
        foreach($_LANGS as $l => $ll) {
            ?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label for="frm_annonce_meta_description_<?php echo $l; ?>">Description <?php echo printLangTag($l); ?></label>
                <?php printToggleLang(); ?>
				<textarea class="form-control" rows="2" id="frm_annonce_meta_description_<?php echo $l; ?>" name="data[meta_description][<?php echo $l; ?>]"><?php echo !empty($frm_annonce_data->meta_description->{$l}) ? $frm_annonce_data->meta_description->{$l} : ''; ?></textarea>
				<div class="help-block">Affichage optimal : entre 100 et 200 caractères</div>
			</div>
            <?php
        }
        ?>

        <?php
        foreach($_LANGS as $l => $ll) {
            ?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label for="frm_annonce_meta_keywords_<?php echo $l; ?>">Mots-clés <?php echo printLangTag($l); ?></label>
                <?php printToggleLang(); ?>
				<textarea class="form-control" rows="2" id="frm_annonce_meta_keywords_<?php echo $l; ?>" name="data[meta_keywords][<?php echo $l; ?>]"><?php echo !empty($frm_annonce_data->meta_keywords->{$l}) ? $frm_annonce_data->meta_keywords->{$l} : ''; ?></textarea>
			</div>
            <?php
        }
        ?>
	</fieldset>

	<br>
	<h2 id="anchor-description">Informations générales</h2><hr>

	<fieldset class="well">
		<div class="row">
			<div class="col-sm-8">
                <?php
                foreach($_LANGS as $l => $ll) {
                    ?>
					<div class="form-group lang_toggle lang_<?php echo $l; ?>">
						<label for="frm_annonce_description_<?php echo $l; ?>">Description <?php echo printLangTag($l); ?></label>
                        <?php printToggleLang(); ?>
						<textarea class="form-control" rows="15" style="height:322px;" id="frm_annonce_description_<?php echo $l; ?>" name="data[description][<?php echo $l; ?>]"><?php echo isset($frm_annonce_data->description->{$l}) ? $frm_annonce_data->description->{$l} : ''; ?></textarea>
					</div>
                    <?php
                }
                ?>

			</div>
			<div class="col-sm-4">
				<div class="row">
					<div class="col-xs-5">
						<div class="form-group">
							<label for="frm_annonce_prix">Prix TTC</label>
							<div class="input-group">
								<input type="number" id="frm_annonce_prix" name="prix" class="form-control" value="<?php if($frm_annonce_prix != '') echo escHtml(number_format($frm_annonce_prix, floor($frm_annonce_prix) == $frm_annonce_prix ? 0 : 2, '.', '')); ?>">
								<span class="input-group-addon">€ HAI</span>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label for="frm_annonce_prix_afficher">Afficher le prix</label>
							<div>
								<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
								<input type="hidden" id="frm_annonce_prix_afficher" name="data[afficher_prix]" value="<?php echo escHtml(isset($frm_annonce_data->afficher_prix) && empty($frm_annonce_data->afficher_prix) ? 0 : 1); ?>">
							</div>
						</div>
					</div>
					<div class="col-xs-5">
						<div class="form-group">
							<label for="frm_annonce_prix_HC">Prix Hors Charge</label>
							<div class="input-group">
								<input type="number" id="frm_annonce_prix_HC" name="data[prix_HC]" class="form-control" value="<?php echo !empty($frm_annonce_data->prix_HC) ? escHtml(number_format($frm_annonce_data->prix_HC, floor($frm_annonce_data->prix_HC) == $frm_annonce_data->prix_HC ? 0 : 2, '.', '')): ''; ?>">
								<span class="input-group-addon">€ HAI</span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="frm_annonce_superficie">Superficie</label>
							<div class="input-group">
								<input type="number" id="frm_annonce_superficie" name="superficie" class="form-control" value="<?php echo escHtml($frm_annonce_superficie); ?>">
								<span class="input-group-addon">m²</span>
							</div>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label class="cat">Contact</label>
							<select class="form-control" name="equipe" id="">
                                <?php foreach ($equipiers as $equipier){ ?>
									<option value="<?php echo $equipier->id?>">
                                        <?php echo $equipier->prenom.' '.$equipier->nom?>
									</option>
                                <?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row <?php echo !$frm_annonce_isLocation? '' : ' hidden'; ?>" id="honoraire">
					<div class="col-sm-6 p-0">
						<div class="form-group">
							<label for="frm_annonce_honoraire">Taux honoraire</label>
							<div class="input-group">
								<input type="number" id="frm_annonce_honoraire" name="data[honoraire]" class="form-control" value="<?php echo isset($frm_annonce_data->honoraire) ? escHtml($frm_annonce_data->honoraire) : ''; ?>">
								<span class="input-group-addon">%</span>
							</div>
						</div>
					</div>
					<div class="col-sm-12">

						<div class="form-group">
							<label for="frm_annonce_charge">Honoraire à la charge</label>
                            <select id="frm_annonce_charge" name="data[charge]" class="form-control">
                                <?php $value = array('acquéreur', 'acheteur');
                                foreach ($value as $v){
                                    echo '<option '.($frm_annonce_data->charge? 'selected':'').' value="'.$v.'">'. $v .'</option>';
                                }
                                ?>
                            </select>
						</div>
					</div>
				</div>
			</div>
	</fieldset>

	<br>
	<h2 id="anchor-description">Informations essentielles</h2><hr>

	<fieldset class="well">
		<label>Cliquez sur un pictogramme pour ajouter une information :</label>
		<div>
            <?php
            $i = 0;
            foreach( glob( '../img/pictos/*.png') as $picto ) {
                ?>
				<button type="button" class="btn btn-default nofocus addsection nofocus" data-pattern="#pattern_infos_picto<?php echo $i; ?>" data-name="data[info]" data-count="> .dissmissable-block" data-target="#zone_infos_picto">
					<img src="<?php echo $picto; ?>" alt="" style="height:44px;">
				</button>
                <?php
                $i++;
            }
            ?>
		</div>
		<br>
		<div id="zone_infos_picto" class="list-sortable-handle-dissmissable all-directions">
            <?php
            if( !empty($frm_annonce_data->info) ) {
                foreach ($frm_annonce_data->info as $v) {
                    $i = rand();
                    $iterator = 'iteration' . $i;
                    ?>
					<div class="col-xs-6 dissmissable-block" style="margin-bottom:0;">
						<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
						<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
						<div class="block_title">&nbsp;</div>
						<div class="content">
							<img style="float:left; height:44px;" src="<?php echo _ROOT . _DIR_IMG . 'pictos/' . $v->image; ?>" alt="">
							<input type="hidden" name="data[info][<?php echo $iterator; ?>][image]" value="<?php echo $v->image; ?>">
							<div style="margin-left:65px;">
								<textarea type="text" name="data[info][<?php echo $iterator; ?>][text]" class="form-control"><?php echo $v->text; ?></textarea>
							</div>
						</div>
					</div>
                    <?php
                }
            }
            ?>
		</div>
	</fieldset>

	<br>
	<h2 id="anchor-description">Points d'intérets</h2><hr>
	<fieldset class="well">
        <?php
        if( !empty($frm_annonce_data->point) ) {
            foreach ($frm_annonce_data->point as $v) {
                ?>
				<div class="dissmissable-block simple">
					<button type="button" class="btn btn-primary sort nofocus">
						<span class="glyphicon glyphicon-resize-vertical"></span></button>
					<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span>
					</button>

					<div class="form-group">
						<input type="text"
						       name="data[point][]"
						       class="form-control"
						       placeholder="Texte"
						       value="<?php echo escHtml($v); ?>">
					</div>
				</div>
                <?php
            }
        }
        ?>
		<button type="button" class="btn btn-primary btn-sm addsection nofocus" data-pattern="#pattern_menu_points" data-name="data[point]" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter</button><br>
	</fieldset>

	<div class="row">
		<div class="col-sm-6">
			<h3 class="cat">DPE</h3>
			<fieldset class="well">
				<div class="form-group">
					<label for="frm_annonce_dpe">Consommations énergétiques</label>
					<div class="input-group">
						<input type="text" id="frm_annonce_dpe" name="data[dpe]" class="form-control" value="<?php echo escHtml(isset($frm_annonce_data->dpe) ? $frm_annonce_data->dpe : ''); ?>">
						<span class="input-group-addon">kWhEP/m².an</span>
					</div>
				</div>
				<div id="img_dpe" class="img_dpe"><div class="cursor"></div></div>
			</fieldset>
		</div>
		<div class="col-sm-6">
			<h3 class="cat">GES</h3>
			<fieldset class="well">
				<div class="form-group">
					<label for="frm_annonce_ges">Émissions de gaz à effet de serre</label>
					<div class="input-group">
						<input type="text" id="frm_annonce_ges" name="data[ges]" class="form-control" value="<?php echo escHtml(isset($frm_annonce_data->ges) ? $frm_annonce_data->ges : ''); ?>">
						<span class="input-group-addon">kgeqCO2/m².an</span>
					</div>
				</div>
				<div id="img_ges" class="img_ges"><div class="cursor"></div></div>
			</fieldset>
		</div>
	</div>



	<br>
	<h2 id="anchor-photos">Photos</h2><hr>

	<h3 class="cat">Ajoutez et classez les photos du bien</h3>
	<fieldset class="well">
		<span class="upload_img btn btn-primary" data-url="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&uploadimage" data-progress="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&uploadimageprogress" data-delete="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>" data-zoneprogress="#zone_progress_annonce" data-zoneimages="#zone_images_annonce"><span class="glyphicon glyphicon-picture"></span> Ajouter des photos</span>
		<div id="zone_progress_annonce" class="zone_images_progress"></div>
		<div id="zone_images_annonce" class="zone_images list-sortable">
            <?php
            foreach($frm_annonce_images as $v) {
                $img_url = (!empty($_annonce) && basename($v->image) == $v->image ? _ROOT.$db_annonce->getAnnonceDirImg($_annonce->id) : _ROOT_ADMIN).$v->image;
                $img_thumb = (!empty($_annonce) && basename($v->image) == $v->image ? _ROOT.$db_annonce->getAnnonceDirImg($_annonce->id).'sm_' : _ROOT_ADMIN).$v->image;
                ?>
				<div id="item<?php echo clean_str($v->image.microtime()); ?>" class="item" style="background-image: url('<?php echo $img_thumb; ?>');">
					<div class="mask">
						<a class="fancybox_img" href="<?php echo $img_url; ?>"><span class="glyphicon glyphicon-zoom-in"></span></a>
						<span class="edit_legend"><span class="glyphicon glyphicon-pencil"></span></span>
						<span class="delete" data-delete="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>"><span class="glyphicon glyphicon-trash"></span></span>
						<input type="hidden" name="images[]" value="<?php echo $v->image; ?>">
                        <?php
                        foreach($_LANGS as $l => $ll)
                            echo '<input type="hidden" name="images_legend['.$l.'][]" value="'.escHtml($v->legende->{$l}).'">';
                        ?>
					</div>
				</div>
                <?php
            }
            ?>
		</div>

	</fieldset>

	<br>
	<h2 id="anchor-localisation">Localisation</h2><hr>

	<div class="row">
		<div class="col-sm-6">
			<h3 class="cat">Carte</h3>
			<div id="gmap"></div>
		</div>
		<div class="col-sm-6">
			<h3 class="cat">Adresse</h3>
			<fieldset class="well">
				<div class="form-group">
					<label> Quartier </label>
					<input type="text"
					       name="data[quartier]"
					       class="form-control"
					       placeholder="Texte"
					       value="<?php echo !empty($frm_annonce_data->quartier) ? escHtml($frm_annonce_data->quartier) : ''; ?>">
				</div>
				<div class="form-group">
					<label for="frm_annonce_adresse">Adresse</label>
					<input type="text" id="frm_annonce_adresse" name="adresse" class="form-control" value="<?php echo escHtml($frm_annonce_adresse); ?>">
				</div>
				<div class="row">
					<div class="col-sm-9">
						<div class="form-group">
							<label for="frm_annonce_ville_nom">Ville</label>
							<input type="text" id="frm_annonce_ville_nom" name="ville_nom" class="form-control" value="<?php echo escHtml($frm_annonce_ville_nom); ?>">
						</div>
						<input type="hidden" id="frm_annonce_ville" name="ville" value="<?php echo escHtml($frm_annonce_ville); ?>">
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="frm_annonce_cp">Code postal</label>
							<div id="cont_frm_annonce_cp">
								<input type="text" id="frm_annonce_cp" name="cp" class="form-control" value="<?php echo escHtml($frm_annonce_cp); ?>" readonly>
							</div>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-primary" id="btn_localize"><span class="glyphicon glyphicon-map-marker"></span> Localiser</button>
				<div class="hidden">
					<input type="text" id="frm_annonce_lat" name="lat" class="form-control" value="<?php echo escHtml($frm_annonce_lat); ?>" readonly>
					<input type="text" id="frm_annonce_lng" name="lng" class="form-control" value="<?php echo escHtml($frm_annonce_lng); ?>" readonly>
				</div>
			</fieldset>
		</div>
	</div>

	<hr id="anchor-enregistrer">

	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<button type="submit" name="action_<?php echo !empty($_annonce->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_annonce->id) ? ' value="'.$_annonce->id.'"' : ''; ?>>Enregistrer</button>
</form>

<div class="modal fade" id="modal_image_legend" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Légende de la photo</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="frm_modal_image_legend_id">
                <?php
                foreach($_LANGS as $l => $ll) {
                    ?>
					<div class="form-group lang_toggle lang_<?php echo $l; ?>">
						<label for="frm_modal_image_legend_<?php echo $l; ?>">Légende <?php echo printLangTag($l); ?></label>
                        <?php printToggleLang(); ?>
						<input type="text" id="frm_modal_image_legend_<?php echo $l; ?>" class="form-control">
					</div>
                    <?php
                }
                ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-success">Valider</button>
			</div>
		</div>
	</div>
</div>

<?php
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
//												 patterns
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//
?>
<div id="patterns" class="hidden">

    <?php
    $i = 0;
    foreach( glob( '../img/pictos/*.png') as $picto ) {
        $file = explode('/', $picto);
        ?>
		<div id="pattern_infos_picto<?php echo $i; ?>">
			<div class="col-xs-6 dissmissable-block" style="margin-bottom:0;">
				<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-move"></span></button>
				<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
				<div class="block_title">&nbsp;</div>
				<div class="content">
					<img style="float:left; height:44px;" src="<?php echo $picto; ?>" alt="">
					<input type="hidden" name="{{name}}[{{tid}}][image]" value="<?php echo $file[count($file) - 1]; ?>">
					<div style="margin-left:65px;">
						<textarea type="text" name="{{name}}[{{tid}}][text]" class="form-control"></textarea>
					</div>
				</div>
			</div>
		</div>
        <?php
        $i++;
    }
    ?>

	<div id="pattern_menu_infos">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>

			<div class="form-group lang_toggle lang_">
				<div class="input-group">
					<span class="input-group-addon"></span>
					<input type="text" name="{{name}}[{{tid}}][text]" class="form-control" placeholder="Texte ">
					<span class="input-group-btn"></span>
				</div>
				<div class="form-group">
					<label>Image</label><br>
					<input type="hidden" id="frm_info_image{{tid}}" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), false, '{{name}}[{{tid}}][image]');">
					<a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="/admin/lib/filemanager/filemanager/dialog.php?type=1&amp;field_id=frm_info_image{{tid}}"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
					<div class="row images-list list-sortable ui-sortable">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="pattern_menu_points">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>

			<div class="form-group">
				<input type="text" name="{{name}}[]" class="form-control" placeholder="Texte">
			</div>
		</div>
	</div>

</div>

<script>

    function updateDPE() {
        if(!isNaN(parseInt($('#frm_annonce_dpe').val()))) {
            var v = parseInt($('#frm_annonce_dpe').val());
            var vv;
            if(v <= 50)
                vv = 'a';
            else if(v <= 90)
                vv = 'b';
            else if(v <= 150)
                vv = 'c';
            else if(v <= 230)
                vv = 'd';
            else if(v <= 330)
                vv = 'e';
            else if(v <= 450)
                vv = 'f';
            else
                vv = 'g';
            $('#img_dpe').removeAttr('data-valeur').attr('data-valeur', vv).find('.cursor').text(v);
        }
        else
            $('#img_dpe').removeAttr('data-valeur');
    }

    function updateGES() {
        if(!isNaN(parseInt($('#frm_annonce_ges').val()))) {
            var v = parseInt($('#frm_annonce_ges').val());
            var vv;
            if(v <= 5)
                vv = 'a';
            else if(v <= 10)
                vv = 'b';
            else if(v <= 20)
                vv = 'c';
            else if(v <= 35)
                vv = 'd';
            else if(v <= 55)
                vv = 'e';
            else if(v <= 80)
                vv = 'f';
            else
                vv = 'g';
            $('#img_ges').removeAttr('data-valeur').attr('data-valeur', vv).find('.cursor').text(v);
        }
        else
            $('#img_ges').removeAttr('data-valeur');
    }

    $(function() {

        $('#frm_annonce_isLocation').on('change', function(){
            if (this.value == 0) {
                $('#forSale').removeClass('hidden');
                $('#honoraire').removeClass('hidden');
                $('#forRent').addClass('hidden');

            }
            if (this.value == 1){
                $('#forSale').addClass('hidden');
                $('#honoraire').addClass('hidden');
                $('#frm_annonce_vente').val(0);
                $('#forRent').removeClass('hidden');
            }
        });
		$('#frm_annonce_type').on('change', function(){
		   if (this.value == 2 ){
		       $('#rent_info').removeClass('hidden');

		   }

            if (this.value != 2){
                $('#rent_info').addClass('hidden');

                $('#frm_annonce_rent_copro, #frm_annonce_rent_lot').val(0);
            }
		});
        $('#frm_annonce_louer').on('change',function() {
            if($(this).val() == 1)
                $('#container_date_dispo').removeClass('hidden');
            else
                $('#container_date_dispo').addClass('hidden');
        });

        $('#frm_annonce_vente').on('change',function() {
            if($(this).val() == 1)
                $('#container_date_vente').removeClass('hidden');
            else
                $('#container_date_vente').addClass('hidden');
        });

        $('body').on('click', '.zone_images .item .edit_legend', function() {
            var item = $(this).parents('.item').get(0);
            $('#frm_modal_image_legend_id').val(item.id);

            <?php
            foreach($_LANGS as $l => $ll) {
                echo '$(\'#frm_modal_image_legend_'.$l.'\').val($(\'input[name="images_legend['.$l.'][]"]\', item).val());';
            }
            ?>
            $('#modal_image_legend').modal('show');
        });


        $('#modal_image_legend .btn-success').click(function() {
            <?php
            foreach($_LANGS as $l => $ll) {
                echo '$(\'input[name="images_legend['.$l.'][]"]\', $(\'#\' + $(\'#frm_modal_image_legend_id\').val())).val($(\'#frm_modal_image_legend_'.$l.'\').val());';
            }
            ?>
            $(':input', $('#modal_image_legend')).val('');
            $('#modal_image_legend').modal('hide');
        });


        var villes = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchville=",
                prepare: function(query, settings) {
                    settings.url = settings.url += encodeURIComponent(query);
                    return settings;
                }
            },
            identify: function(obj) { return obj.id; }
        });
        villes.initialize();
        $('#frm_annonce_ville_nom').typeahead({
            hint: false,
            highlight: true,
            minLength: 0
        }, {
            displayKey: 'str',
            source: villes,
            limit: 8
        }).bind('typeahead:select', function(e, o) {
            $('#frm_annonce_ville_nom').typeahead('val', o.nom);
            $('#frm_annonce_ville').val(o.id);
            if(o.cp.length > 5) {
                var cps = o.cp.split(/\s*-\s*/);
                $('#cont_frm_annonce_cp').html('<select id="frm_annonce_cp" name="cp" class="form-control"></select>');
                for(var i = 0; i < cps.length; i++)
                    $('#frm_annonce_cp').append('<option value="' + cps[i] + '">' + cps[i] + '</option>');
            }
            else
                $('#cont_frm_annonce_cp').html('<input type="text" id="frm_annonce_cp" name="cp" class="form-control" value="' + o.cp + '" readonly>');
        }).bind('keyup', function(e, o) {
            if($('#frm_annonce_ville_nom').typeahead('val') == '') {
                $('#frm_annonce_ville').val('');
                $('#cont_frm_annonce_cp').html('<input type="text" id="frm_annonce_cp" name="cp" class="form-control" readonly>');
                marker.setMap(null);
                map.setCenter(gmapdefault);
                map.setZoom(4);
                $('#frm_annonce_latitude').val('');
                $('#frm_annonce_longitude').val('');
            }
        });

        map = L.map('gmap').setView([46.2157467,2.2088258], 4);
        var OpenStreetMap_BlackAndWhite2 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);


        var provider = new window.GeoSearch.OpenStreetMapProvider();

        var marker = L.marker([-44.895776,179.407997], {
            draggable: true
        });
        marker.on('drag', function(e) {
            var location = marker.getLatLng();
            $('#frm_annonce_lat').val(location.lat);
            $('#frm_annonce_lng').val(location.lng);
        });

        if($('#frm_annonce_lat').val() != '' && $('#frm_annonce_lng').val() != '') {
            map.setView([$('#frm_annonce_lat').val(),$('#frm_annonce_lng').val()], 15);
            marker.setLatLng([$('#frm_annonce_lat').val(), $('#frm_annonce_lng').val()]);
            marker.addTo(map);
        }

        $('#btn_localize').click(function() {
            provider.search({ query: $('#frm_annonce_adresse').val() + ' ' + $('#frm_annonce_cp').val() + ' ' + $('#frm_annonce_ville_nom').val() + ' France' }).then(function(result) {
                $('#frm_annonce_lat').val(result[0].y);
                $('#frm_annonce_lng').val(result[0].x);
                map.setView([$('#frm_annonce_lat').val(),$('#frm_annonce_lng').val()], 15);
                marker.setLatLng([$('#frm_annonce_lat').val(), $('#frm_annonce_lng').val()]);
                marker.addTo(map);
            });
        });


        <?php
        if(!empty($_annonce)) {
        if(!empty($locked)) {
        ?>
        if(confirm("Ce bien est en cours de modification par <?php echo $verrou->prenom.' '.$verrou->nom; ?>\nSouhaitez-vous prendre la main sur ce bien ? <?php echo $verrou->prenom.' '.$verrou->nom; ?> sera alors dans l'impossibilité de valider ses modifications.")) {
            $.ajax({
                type: "POST",
                url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_annonce->id; ?>",
                data: { deleteverrou: 1 }
            }).done(function(data) {
                location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_annonce->id; ?>";
            });
        }
        else {
            location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=<?php echo !empty($_annonce->date_vente) ? 'vendus' : 'list'; ?>";
        }
        <?php
        }
        else {
        ?>
        $(window).on('unload, beforeunload', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_annonce->id; ?>",
                async: false,
                data: { deleteverrou: 1, token: $('input[name="token"]').val() }
            });
        });
        <?php
        }
        }
        ?>

        $('#frm_annonce_dpe').keyup(function() {
            updateDPE();
        });
        updateDPE();

        $('#frm_annonce_ges').keyup(function() {
            updateGES();
        });
        updateGES();

    });
    <?php
    if(empty($locked) && !empty($_annonce)) {
    ?>
    function checkVerrou() {
        $.ajax({
            type: "POST",
            url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_annonce->id; ?>",
            data: { checkverrou: $('input[name="token"]').val() },
            dataType: 'json'
        }).done(function(data) {
            if(data.success != '') {
                clearInterval(interval_checkverrou);
                alert(data.prenom + ' ' + data.nom + ' a pris la main sur ce bien. Vous allez être redirigé vers la liste des biens.');
                location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=<?php echo !empty($_annonce->date_vente) ? 'vendus' : 'list'; ?>";
            }
        });
    }
    var interval_checkverrou = setInterval(checkVerrou, 10000);
    <?php
    }
    ?>
</script>
