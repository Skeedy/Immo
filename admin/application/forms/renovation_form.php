<?php

//active
if(!empty($_POST))
    $frm_renovation_active = !empty($_POST['active']) ? $_POST['active'] : 0;
else if(isset($_renovation->active))
    $frm_renovation_active = $_renovation->active;
else
    $frm_renovation_active = 0;

//isOver
if(!empty($_POST))
    $frm_renovation_isOver = !empty($_POST['isOver']) ? $_POST['isOver'] : 0;
else if(isset($_renovation->isOver))
    $frm_renovation_isOver = $_renovation->isOver;
else
    $frm_renovation_isOver = 0;


//ref
if(isset($_POST['ref']))
    $frm_renovation_ref = $_POST['ref'];
else if(isset($_renovation->ref))
    $frm_renovation_ref = $_renovation->ref;
else
    $frm_renovation_ref = '';

//titre
if(isset($_POST['titre']))
    $frm_renovation_titre = json_decode(json_encode($_POST['titre']));
else if(isset($_renovation->titre))
    $frm_renovation_titre = json_decode($_renovation->titre);
else
    $frm_renovation_titre = new stdClass();

//date_livraison
if(!empty($_POST))
    $frm_annonce_date_livraison = !empty($_POST['date_livraison']) ? $_POST['date_livraison'] : 0;
else if(isset($_renovation->date_livraison))
    $frm_annonce_date_livraison = date_create($_renovation->date_livraison)->format('d/m/Y');
else
    $frm_annonce_date_livraison = date('d/m/Y');

//adresse
if(isset($_POST['adresse']))
    $frm_renovation_adresse = $_POST['adresse'];
else if(isset($_renovation->adresse))
    $frm_renovation_adresse = $_renovation->adresse;
else
    $frm_renovation_adresse = '';

//cp
if(isset($_POST['cp']))
    $frm_renovation_cp = $_POST['cp'];
else if(isset($_renovation->cp))
    $frm_renovation_cp = $_renovation->cp;
else
    $frm_renovation_cp = '';



//ville
if(isset($_POST['ville']))
    $frm_renovation_ville = $_POST['ville'];
else if(isset($_renovation->ville))
    $frm_renovation_ville = $_renovation->ville;
else
    $frm_renovation_ville = '';

//ville_nom
if(isset($_POST['ville_nom']))
    $frm_renovation_ville_nom = $_POST['ville_nom'];
else if(isset($_renovation->nom_reel))
    $frm_renovation_ville_nom = $_renovation->nom_reel;
else
    $frm_renovation_ville_nom = '';

//lat
if(isset($_POST['lat']))
    $frm_renovation_lat = $_POST['lat'];
else if(isset($_renovation->lat))
    $frm_renovation_lat = $_renovation->lat;
else
    $frm_renovation_lat = '';

//lat
if(isset($_POST['lng']))
    $frm_renovation_lng = $_POST['lng'];
else if(isset($_renovation->lng))
    $frm_renovation_lng = $_renovation->lng;
else
    $frm_renovation_lng = '';

//images
if(isset($_POST['images'])) {
    $frm_renovation_images = array();
    for($i = 0; $i < count($_POST['images']); $i++) {
        $t = new stdClass();
        $t->image = $_POST['images'][$i];
        $t->legende = new stdClass();
        foreach($_LANGS as $l => $ll)
            $t->legende->{$l} = $_POST['images_legend'][$l][$i];
        $frm_renovation_images[] = $t;
    }
}
else if(isset($_renovation->images))
    $frm_renovation_images = json_decode($_renovation->images);
else
    $frm_renovation_images = array();
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

if(isset($_POST['comparaisons'])) {
    $frm_renovation_comparaisons = array();
    for($i = 0; $i < count($_POST['comparaisons']); $i++) {
        $t = new stdClass();
        $t->comparaisons = $_POST['comparaisons'][$i];
        $t->legende = new stdClass();
        foreach($_LANGS as $l => $ll)
            $t->legende->{$l} = $_POST['comparaisons_legend'][$l][$i];
        $frm_renovation_comparaisons[] = $t;
    }
}
else if(isset($_renovation->comparaisons))
    $frm_renovation_comparaisons = json_decode($_renovation->comparaisons);
else
    $frm_renovation_comparaisons = array();

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
    $frm_renovation_data = json_decode(json_encode($_POST['data']));
else if(!empty($_data))
    $frm_renovation_data = $_data;
else
    $frm_renovation_data = new stdClass();

//slug
if(isset($_POST['slug']))
    $frm_renovation_slug = json_decode(json_encode($_POST['slug']));
else if(!empty($_slug))
    $frm_renovation_slug = $_data;
else
    $frm_renovation_slug = '';

?>
<form method="post">
    <div class="row">
        <div class="col-sm-6">
            <h3 class="cat">Titre du bien</h3>
            <fieldset class="well">
                <div class="form-group">
                    <label class="required" for="frm_renovation_titre">Titre</label>
                    <input type="text" id="frm_renovation_titre" name="titre" class="form-control" value="<?php echo !empty($_renovation->titre) ? $_renovation->titre : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="required">Référence</label>
                    <input type="text" id="frm_renovation_ref" name="ref" class="form-control" value="<?php echo escHtml($frm_renovation_ref); ?>" required>
                </div>
                    
            </fieldset>
        </div>
        <div class="col-sm-6">
        	<h3 class="cat">Options de publication</h3>
            <fieldset class="well">
                <div class="form-group">
                    <label for="frm_renovation_active">Publier le bien sur le site</label>
                    <div>
                        <button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
                        <input type="hidden" id="$frm_renovation_active" name="active" value="<?php echo escHtml
                        ($frm_renovation_active); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="frm_renovation_isOver">Le bien est-il terminé ?</label>
                    <div>
                        <button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
                        <input type="hidden" id="frm_renovation_isOver" name="isOver" value="<?php echo escHtml
                        ($frm_renovation_isOver); ?>">
                    </div>
                </div>
                <div id="container_date_livraison" class="form-group<?php if(!$frm_renovation_isOver) echo ' hidden';
                ?>">
                    <label for="frm_annonce_date_vente">Date de livraison</label><br>
                    <input type="text" id="frm_annonce_date_livraison" name="date_livraison" class="form-control short
                    datepicker" value="<?php echo escHtml($frm_annonce_date_livraison); ?>">
                </div>
            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">


            <br>
            <h2 id="anchor-seo">SEO</h2><hr>

            <h3 class="cat">Champs utilisés par les moteurs de recherche</h3>
            <fieldset class="well">
                <?php
                foreach($_LANGS as $l => $ll) {
                    ?>
                    <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                        <label for="frm_renovation_meta_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
                        <?php printToggleLang(); ?>
                        <input type="text" id="frm_renovation_meta_titre_<?php echo $l; ?>" name="data[meta_titre][<?php echo $l; ?>]" class="form-control" value="<?php echo !empty($frm_renovation_data->meta_titre->{$l}) ? escHtml($frm_renovation_data->meta_titre->{$l}) : ''; ?>">
                        <div class="help-block">Affichage optimal : entre 50 et 80 caractères</div>
                    </div>
                    <?php
                }
                ?>

                <?php
                foreach($_LANGS as $l => $ll) {
                    ?>
                    <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                        <label for="frm_renovation_meta_description_<?php echo $l; ?>">Description <?php echo printLangTag($l); ?></label>
                        <?php printToggleLang(); ?>
                        <textarea class="form-control" rows="2" id="frm_renovation_meta_description_<?php echo $l; ?>" name="data[meta_description][<?php echo $l; ?>]"><?php echo !empty($frm_renovation_data->meta_description->{$l}) ? $frm_renovation_data->meta_description->{$l} : ''; ?></textarea>
                        <div class="help-block">Affichage optimal : entre 100 et 200 caractères</div>
                    </div>
                    <?php
                }
                ?>

                <?php
                foreach($_LANGS as $l => $ll) {
                    ?>
                    <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                        <label for="frm_renovation_meta_keywords_<?php echo $l; ?>">Mots-clés <?php echo printLangTag($l); ?></label>
                        <?php printToggleLang(); ?>
                        <textarea class="form-control" rows="2" id="frm_renovation_meta_keywords_<?php echo $l; ?>" name="data[meta_keywords][<?php echo $l; ?>]"><?php echo !empty($frm_renovation_data->meta_keywords->{$l}) ? $frm_renovation_data->meta_keywords->{$l} : ''; ?></textarea>
                    </div>
                    <?php
                }
                ?>
            </fieldset>
        </div>
        <br>
        <div class="col-sm-6">
            <h2 id="anchor-description">Informations générales</h2><hr>

            <fieldset class="well">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        foreach($_LANGS as $l => $ll) {
                            ?>
                            <div class="form-group lang_toggle lang_<?php echo $l; ?>">
                                <label for="frm_renovation_description_<?php echo $l; ?>">Description <?php echo printLangTag($l); ?></label>
                                <?php printToggleLang(); ?>
                                <textarea class="form-control" rows="15" style="height:322px;" id="frm_renovation_description_<?php echo $l; ?>" name="data[description][<?php echo $l; ?>]"><?php echo isset($frm_renovation_data->description->{$l}) ? $frm_renovation_data->description->{$l} : ''; ?></textarea>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <h2 id="anchor-description">Informations</h2><hr>
            <fieldset class="well">
                <?php
                if( !empty($frm_renovation_data->infos) ) {
                    foreach ($frm_renovation_data->infos as $v) {
                        ?>
                        <div class="dissmissable-block simple">
                            <button type="button" class="btn btn-primary sort nofocus">
                                <span class="glyphicon glyphicon-resize-vertical"></span></button>
                            <button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span>
                            </button>

                            <div class="form-group">
                                <input type="text"
                                       name="data[infos][]"
                                       class="form-control"
                                       placeholder="Texte"
                                       value="<?php echo escHtml($v); ?>">
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <button type="button" class="btn btn-primary btn-sm addsection nofocus"
                        data-pattern="#pattern_menu_points" data-name="data[infos]" data-count=">
                        .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter</button><br>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">


            <h2 id="anchor-photos">Photos caroussel</h2><hr>

            <h3 class="cat">Ajoutez et classez les photos du bien</h3>
            <fieldset class="well">
                <span class="upload_img btn btn-primary" data-url="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&uploadimage" data-progress="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&uploadimageprogress" data-delete="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>" data-zoneprogress="#zone_progress_renovation" data-zoneimages="#zone_images_renovation"><span class="glyphicon glyphicon-picture"></span> Ajouter des photos</span>
                <div id="zone_progress_renovation" class="zone_images_progress"></div>
                <div id="zone_images_renovation" class="zone_images list-sortable">
                    <?php
                    foreach($frm_renovation_images as $v) {
                        $img_url = (!empty($_renovation) && basename($v->image) == $v->image ? _ROOT.$db_renovation->getRenovationDirImg($_renovation->id) : _ROOT_ADMIN).$v->image;
                        $img_thumb = (!empty($_renovation) && basename($v->image) == $v->image ? _ROOT
                                .$db_renovation->getRenovationDirImg($_renovation->id).'sm_' : _ROOT_ADMIN).$v->image;
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
        </div>
        <div class="col-sm-6">
            <h2 id="anchor-photos">Photos avant/après</h2><hr>

            <h3 class="cat">Ajoutez les photos du bien</h3>
            <fieldset class="well">
                <span class="upload_img btn btn-primary" data-name-images="comparaisons"
                      data-name-legend="comparaisons_legend" data-url="<?php echo
                    _ROOT_ADMIN
                    .'?controller='
                    .$_controller; ?>&uploadimage" data-progress="<?php echo _ROOT_ADMIN.'?controller='.$_controller;
                ?>&uploadimageprogress" data-delete="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>" data-zoneprogress="#zone_progress_renovation2" data-zoneimages="#zone_images_renovation2"><span class="glyphicon glyphicon-picture"></span> Ajouter des photos</span>
                <div id="zone_progress_renovation2" class="zone_images_progress"></div>
                <div id="zone_images_renovation2" class="zone_images list-sortable">
                    <?php
                    foreach($frm_renovation_comparaisons as $v) {
                        $img_url = (!empty($_renovation) && basename($v->image) == $v->image ? _ROOT
                                .$db_renovation->getRenovationDirComp($_renovation->id) : _ROOT_ADMIN).$v->image;
                        $img_thumb = (!empty($_renovation) && basename($v->image) == $v->image ? _ROOT
                                .$db_renovation->getRenovationDirComp($_renovation->id).'sm_' : _ROOT_ADMIN).$v->image;
                        ?>
                        <div id="item<?php echo clean_str($v->image.microtime()); ?>" class="item" style="background-image: url('<?php echo $img_thumb; ?>');">
                            <div class="mask">
                                <a class="fancybox_img" href="<?php echo $img_url; ?>"><span class="glyphicon glyphicon-zoom-in"></span></a>
                                <span class="edit_legend"><span class="glyphicon glyphicon-pencil"></span></span>
                                <span class="delete" data-delete="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>"><span class="glyphicon glyphicon-trash"></span></span>
                                <input type="hidden" name="comparaisons[]" value="<?php echo $v->image; ?>">
                                <?php
                                foreach($_LANGS as $l => $ll)
                                    echo '<input type="hidden" name="comparaisons_legend['.$l.'][]" value="'.escHtml($v->legende->{$l}).'">';
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </fieldset>
        </div>
    </div>


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
                           value="<?php echo !empty($frm_renovation_data->quartier) ? escHtml($frm_renovation_data->quartier) : ''; ?>">
                </div>
		        <div class="form-group">
		            <label for="frm_annonce_adresse">Adresse</label>
		            <input type="text" id="frm_renovation_adresse" name="adresse" class="form-control" value="<?php echo escHtml($frm_renovation_adresse); ?>">
		        </div>
		        <div class="row">
		            <div class="col-sm-9">

		                <div class="form-group">
		                    <label for="frm_annonce_ville_nom">Ville</label>
		                    <input type="text" id="frm_renovation_ville_nom" name="ville_nom" class="form-control" value="<?php echo escHtml($frm_renovation_ville_nom); ?>">
		                </div>
		                <input type="hidden" id="frm_renovation_ville" name="ville" value="<?php echo escHtml($frm_renovation_ville); ?>">
		            </div>
		            <div class="col-sm-3">
		                <div class="form-group">
		                    <label for="frm_renovation_cp">Code postal</label>
		                    <div id="cont_frm_renovation_cp">
		                        <input type="text" id="frm_renovation_cp" name="cp" class="form-control" value="<?php echo escHtml($frm_renovation_cp); ?>" readonly>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <button type="button" class="btn btn-primary" id="btn_localize"><span class="glyphicon glyphicon-map-marker"></span> Localiser</button>
		        <div class="hidden">
		       		<input type="text" id="frm_renovation_lat" name="lat" class="form-control" value="<?php echo escHtml($frm_renovation_lat); ?>" readonly>
		       		<input type="text" id="frm_renovation_lng" name="lng" class="form-control" value="<?php echo escHtml($frm_renovation_lng); ?>" readonly>
		       	</div>
		    </fieldset>
		</div>
	</div>

    <hr id="anchor-enregistrer">

    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <button type="submit" name="action_<?php echo !empty($_renovation->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_renovation->id) ? ' value="'.$_renovation->id.'"' : ''; ?>>Enregistrer</button>
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
        if(!isNaN(parseInt($('#frm_renovation_dpe').val()))) {
            var v = parseInt($('#frm_renovation_dpe').val());
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
        if(!isNaN(parseInt($('#frm_renovation_ges').val()))) {
            var v = parseInt($('#frm_renovation_ges').val());
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

        $('#frm_renovation_isOver').change(function() {
            if($(this).val() == 1)
                $('#container_date_livraison').removeClass('hidden');
            else
                $('#container_date_livraison').addClass('hidden');
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
        $('#frm_renovation_ville_nom').typeahead({
            hint: false,
            highlight: true,
            minLength: 0
        }, {
            displayKey: 'str',
            source: villes,
            limit: 8
        }).bind('typeahead:select', function(e, o) {
            $('#frm_renovation_ville_nom').typeahead('val', o.nom);
            $('#frm_renovation_ville').val(o.id);
            if(o.cp.length > 5) {
                var cps = o.cp.split(/\s*-\s*/);
                $('#cont_frm_renovation_cp').html('<select id="frm_renovation_cp" name="cp" class="form-control"></select>');
                for(var i = 0; i < cps.length; i++)
                    $('#frm_renovation_cp').append('<option value="' + cps[i] + '">' + cps[i] + '</option>');
            }
            else
                $('#cont_frm_renovation_cp').html('<input type="text" id="frm_renovation_cp" name="cp" class="form-control" value="' + o.cp + '" readonly>');
        }).bind('keyup', function(e, o) {
            if($('#frm_renovation_ville_nom').typeahead('val') == '') {
                $('#frm_renovation_ville').val('');
                $('#cont_frm_renovation_cp').html('<input type="text" id="frm_renovation_cp" name="cp" class="form-control" readonly>');
                marker.setMap(null);
                map.setCenter(gmapdefault);
                map.setZoom(4);
                $('#frm_renovation_latitude').val('');
                $('#frm_renovation_longitude').val('');
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
			$('#frm_renovation_lat').val(location.lat);
			$('#frm_renovation_lng').val(location.lng);
		});

		if($('#frm_renovation_lat').val() != '' && $('#frm_renovation_lng').val() != '') {
			map.setView([$('#frm_renovation_lat').val(),$('#frm_renovation_lng').val()], 15);
			marker.setLatLng([$('#frm_renovation_lat').val(), $('#frm_renovation_lng').val()]);
			marker.addTo(map);
		}

		$('#btn_localize').click(function() {
			provider.search({ query: $('#frm_renovatio_adresse').val() + ' ' + $('#frm_renovatio_cp').val() + ' ' + $('#frm_renovatio_ville_nom').val() + ' France' }).then(function(result) {
				$('#frm_renovation_lat').val(result[0].y);
				$('#frm_renovation_lng').val(result[0].x);
				map.setView([$('#frm_renovation_lat').val(),$('#frm_renovation_lng').val()], 15);
				marker.setLatLng([$('#frm_renovation_lat').val(), $('#frm_renovation_lng').val()]);
				marker.addTo(map);
			});
		});


        <?php
        if(!empty($_renovation)) {
        if(!empty($locked)) {
        ?>
        if(confirm("Ce bien est en cours de modification par <?php echo $verrou->prenom.' '.$verrou->nom; ?>\nSouhaitez-vous prendre la main sur ce bien ? <?php echo $verrou->prenom.' '.$verrou->nom; ?> sera alors dans l'impossibilité de valider ses modifications.")) {
            $.ajax({
                type: "POST",
                url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_renovation->id; ?>",
                data: { deleteverrou: 1 }
            }).done(function(data) {
                location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_renovation->id; ?>";
            });
        }
        else {
            location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=<?php echo !empty($_renovation->date_vente) ? 'vendus' : 'list'; ?>";
        }
        <?php
        }
        else {
        ?>
        $(window).on('unload, beforeunload', function() {
            $.ajax({
                type: "POST",
                url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_renovation->id; ?>",
                async: false,
                data: { deleteverrou: 1, token: $('input[name="token"]').val() }
            });
        });
        <?php
        }
        }
        ?>

        $('#frm_renovation_dpe').keyup(function() {
            updateDPE();
        });
        updateDPE();

        $('#frm_renovation_ges').keyup(function() {
            updateGES();
        });
        updateGES();

    });
    <?php
    if(empty($locked) && !empty($_renovation)) {
    ?>
    function checkVerrou() {
        $.ajax({
            type: "POST",
            url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $_renovation->id; ?>",
            data: { checkverrou: $('input[name="token"]').val() },
            dataType: 'json'
        }).done(function(data) {
            if(data.success != '') {
                clearInterval(interval_checkverrou);
                alert(data.prenom + ' ' + data.nom + ' a pris la main sur ce bien. Vous allez être redirigé vers la liste des biens.');
                location.href = "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=<?php echo !empty($_renovation->date_vente) ? 'vendus' : 'list'; ?>";
            }
        });
    }
    var interval_checkverrou = setInterval(checkVerrou, 10000);
    <?php
    }
    ?>
</script>
