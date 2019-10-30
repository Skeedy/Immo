<?php

require_once _DIR_FORMS.'page_constructor.php';


//active
if(!empty($_POST))
	$frm_active = !empty($_POST['active']) ? $_POST['active'] : 0;
else if(isset($_item->active))
	$frm_active = $_item->active;
else
	$frm_active = 0;
	
//titre
if(isset($_POST['titre']))
	$frm_titre = json_decode(json_encode($_POST['titre']));
else if(isset($_item->titre))
	$frm_titre = $_item->titre;
else
	$frm_titre = new stdClass();

//url
if(isset($_POST['url']))
	$frm_url = $_POST['url'];
else if(isset($_item->url))
	$frm_url = $_item->url;
else
	$frm_url = '';

//template
if(isset($_POST['template']))
	$frm_template = $_POST['template'];
else if(isset($_item->template))
	$frm_template = $_item->template;
else
	$frm_template = '';
	
//contenu
if(isset($_POST['page']))
	$frm_contenu = json_decode(json_encode($_POST['page']));
else if(!empty($_data))
	$frm_contenu = array_map_recursive('decodeDirs', $_data);
else
	$frm_contenu = new stdClass();


?>
<ol class="breadcrumb quickaccess">
	<li>Accès rapide : </li>
	<li><a href="#anchor-publication">Publication</a></li>
	<li><a href="#anchor-seo">SEO</a></li>
	<li><a href="#anchor-contenu">Contenu</a></li>
	<li><a href="#anchor-enregistrer">Enregistrer</a></li>
</ol>


<form method="post">
	
	<h2 id="anchor-publication">Publication</h2><hr>

	<div class="row">
		<div class="col-sm-6">
			<h3 class="cat">Titre et URL</h3>
			<fieldset class="well">
				<?php
				foreach($_LANGS as $l => $ll) {
					?>
					<div class="form-group lang_toggle lang_<?php echo $l; ?>">
						<label for="frm_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
						<?php printToggleLang(); ?>
						<input type="text" id="frm_titre_<?php echo $l; ?>" name="titre[<?php echo $l; ?>]" class="form-control" value="<?php echo !empty($frm_titre->{$l}) ? escHtml($frm_titre->{$l}) : ''; ?>">
					</div>
					<?php
				}
				?>
				<div class="form-group">
					<label for="frm_url">URL</label>
					<div class="input-group">
						<span class="input-group-addon"><?php echo _PROTOCOL.$_SERVER['HTTP_HOST']._ROOT; ?></span>
						<input type="text" id="frm_url" name="url" class="form-control" value="<?php echo escHtml($frm_url); ?>">
					</div>
				</div>
			</fieldset>
		</div>
		<div class="col-sm-6">
			<h3 class="cat">Options de publication</h3>
			<fieldset class="well">
				<div class="form-group">
					<label for="frm_active">Publier la page</label>
					<div>
						<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
						<input type="hidden" id="frm_active" name="active" value="<?php echo escHtml($frm_active); ?>">
					</div>
				</div>
				<?php
				if(!empty($_item)) {
					?>
					<div class="form-group">
						<table class="table noborder freewidth">
							<tr><td><strong>Crée le :</strong></td><td><?php echo date_create($_item->date_creation)->format('d/m/Y à H:i:s'); ?></td></tr>
							<?php if($_item->date_modification != $_item->date_creation) echo '<tr><td><strong>Modifiée le :</strong></td><td>'.date_create($_item->date_modification)->format('d/m/Y à H:i:s').'</td></tr>'; ?>
						</table>
					</div>
					<?php
				}
				?>
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
				<label for="frm_meta_titre_<?php echo $l; ?>">Méta Titre <?php echo printLangTag($l); ?></label>
				<?php printToggleLang(); ?>
				<input type="text" id="frm_meta_titre_<?php echo $l; ?>" name="page[meta_titre][<?php echo $l; ?>]" class="form-control" value="<?php echo !empty($frm_contenu->meta_titre->{$l}) ? escHtml($frm_contenu->meta_titre->{$l}) : ''; ?>">
			</div>
			<?php
		}
		?>
		
		<?php
		foreach($_LANGS as $l => $ll) {
			?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label for="frm_meta_description_<?php echo $l; ?>">Méta Description <?php echo printLangTag($l); ?></label>
				<?php printToggleLang(); ?>
				<textarea class="form-control" rows="2" id="frm_meta_description_<?php echo $l; ?>" name="page[meta_description][<?php echo $l; ?>]"><?php echo !empty($frm_contenu->meta_description->{$l}) ? $frm_contenu->meta_description->{$l} : ''; ?></textarea>
			</div>
			<?php
		}
		?>
		
		<?php
		foreach($_LANGS as $l => $ll) {
			?>
			<div class="form-group lang_toggle lang_<?php echo $l; ?>">
				<label for="frm_meta_keywords_<?php echo $l; ?>">Méta Mots-clés <?php echo printLangTag($l); ?></label>
				<?php printToggleLang(); ?>
				<textarea class="form-control" rows="2" id="frm_meta_keywords_<?php echo $l; ?>" name="page[meta_keywords][<?php echo $l; ?>]"><?php echo !empty($frm_contenu->meta_keywords->{$l}) ? $frm_contenu->meta_keywords->{$l} : ''; ?></textarea>
			</div>
			<?php
		}
		?>
	</fieldset>
	
	<br>
	<h2 id="anchor-contenu">Contenu</h2><hr>

	<h3 class="cat">Template de la page</h3>
	<fieldset class="well">
		<div class="form-group">
			<select id="frm_template" name="template" class="form-control" required>
				<?php
				foreach(array(
					'normal' => 'Normal',
					'home' => 'Accueil',
                    'expertise' =>'Expertise',
                    'acheter'=>'Acheter',
                    'louer'=>'Louer',
                    'renovation'=>'Rénover',
                    'contact'=>'Contact',
                    'search' => 'Recherche',
                    'trouver' => 'Trouver mon bien idéal',
					'immeuble' => 'Immeuble',
                    'mention_legale' => 'Mention légales',
                    'recherche' => 'Recherche'

				) as $k => $v)
					echo '<option value="'.$k.'"'.(!empty($frm_template) && $frm_template == $k ? ' selected' : '').'>'.escHtml($v).'</option>';
				?>
			</select>
		</div>
	</fieldset>


    <?php
    /*
     * Normal
     */
    ?>
    <div class="template_contenu contenu_normal contenu_search contenu_recherche contenu_mention_legale contenu_expertise contenu_acheter contenu_louer
    contenu_renovation contenu_contact hidden">
        <h3 class="cat">Contenu de la page</h3>
        <br>
        <div class="list-sortable-handle-dissmissable-interchangeable">
            <?php
            if(!empty($frm_contenu->normal))
                parseContent($frm_contenu->normal, 'page[normal]');
            ?>
        </div>
        <?php
        printContentButtons('page[normal]');
        ?>
    </div>


	<?php
	/*
	 * home
	 */
	?>
	<div class="template_contenu contenu_home  hidden">
		<h3 class="cat">Titre</h3>
		<fieldset class="well">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<label for="frm_home_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
					<?php printToggleLang(); ?>
					<textarea class="form-control" rows="2" id="frm_home_titre_<?php echo $l; ?>" name="page[home_titre][<?php echo $l; ?>]"><?php echo !empty($frm_contenu->home_titre->{$l}) ? $frm_contenu->home_titre->{$l} : ''; ?></textarea>
				</div>
				<?php
			}
			?>
		</fieldset>

		<h3 class="cat">Diaporama</h3>
		<fieldset class="well">
			<div class="form-group">
				<label>Images</label><br>
				<input type="hidden" id="frm_home_diaporama_image" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), true, 'page[home_diaporama_images][]');">
				<a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filemanager/filemanager/dialog.php?type=1&field_id=frm_home_diaporama_image"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
				<div id="frm_home_diaporama_images" class="row images-list list-sortable">
					<?php
					if(!empty($frm_contenu->home_diaporama_images)) {
						foreach ($frm_contenu->home_diaporama_images as $v) {
							?>
							<div class="thumb" style="background-image:url('<?php echo _ROOT._DIR_THUMBS.$v; ?>');">
								<a href="<?php echo _ROOT._DIR_MEDIA.$v; ?>" class="fancybox_img"></a>
								<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<input type="hidden" name="page[home_diaporama_images][]" value="<?php echo $v; ?>">
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</fieldset>
	</div>

    <?php
    /*
     * immeuble
     */
    ?>
	<div class="template_contenu  contenu_immeuble hidden">
		<h3 class="cat">Titre</h3>
		<fieldset class="well">
            <?php
            foreach($_LANGS as $l => $ll) {
                ?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<label for="frm_immeuble_titre_<?php echo $l; ?>">Titre <?php echo printLangTag($l); ?></label>
                    <?php printToggleLang(); ?>
					<textarea class="form-control" rows="2" id="frm_immeuble_titre_<?php echo $l; ?>" name="page[immeuble_titre][<?php echo $l; ?>]"><?php echo !empty($frm_contenu->immeuble_titre->{$l}) ? $frm_contenu->immeuble_titre->{$l} : ''; ?></textarea>
				</div>
                <?php
            }
            ?>
		</fieldset>

		<h3 class="cat">Diaporama</h3>
		<fieldset class="well">
			<div class="form-group">
				<label>Images</label><br>
				<input type="hidden" id="frm_immeuble_diaporama_image" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), true, 'page[immeuble_diaporama_images][]');">
				<a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filemanager/filemanager/dialog.php?type=1&field_id=frm_immeuble_diaporama_image"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
				<div id="frm_immeuble_diaporama_images" class="row images-list list-sortable">
                    <?php
                    if(!empty($frm_contenu->immeuble_diaporama_images)) {
                        foreach ($frm_contenu->immeuble_diaporama_images as $v) {
                            ?>
							<div class="thumb" style="background-image:url('<?php echo _ROOT._DIR_THUMBS.$v; ?>');">
								<a href="<?php echo _ROOT._DIR_MEDIA.$v; ?>" class="fancybox_img"></a>
								<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<input type="hidden" name="page[immeuble_diaporama_images][]" value="<?php echo $v; ?>">
							</div>
                            <?php
                        }
                    }
                    ?>
				</div>
			</div>
		</fieldset>
	</div>

	<button type="submit" name="action_<?php echo !empty($_item->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_item->id) ? ' value="'.$_item->id.'"' : ''; ?>>Enregistrer</button>
</form>

<?php
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
//												 patterns
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//
?>
<div id="patterns">
    <?php
    printPatterns();
    ?>
</div>

<script>
function templateContent() {
	$('.template_contenu').addClass('hidden');
	$('.template_contenu.contenu_' + $('#frm_template').val()).removeClass('hidden');
}


$(function() {
	$('#frm_page_titre').keyup(function() {
		if(!$('#frm_page_url').hasClass('modified'))
			$('#frm_page_url').val(cleanUrl($('#frm_page_titre').val()));
	});
	
	$('#frm_page_url').keyup(function() {
		if($('#frm_page_url').val() == '')
			$('#frm_page_url').removeClass('modified');
		else
			$('#frm_page_url').addClass('modified');
	});
	
	$('#frm_page_url').blur(function() {
		$('#frm_page_url').val(cleanUrl($('#frm_page_url').val()));
	});

	$('#frm_template').change(function() {
		templateContent();
	});

	templateContent();

	var annonces = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchannonces=",
			prepare: function(query, settings) {
				settings.url = settings.url += encodeURIComponent(query);
				return settings;
			}
		},
		identify: function(obj) { return obj.id; }
	});
	annonces.initialize();
	$('#frm_bien_typeahead').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		displayKey: 'str',
		source: annonces,
		limit: 8,
		templates: {
        empty: [
            '<div class="empty-message">',
            'No results',
            '</div>'
        ].join('\n'),
        header: '',
        suggestion: function(data) {
        	return '<p><img src="' + data.image + '" height="40dpx"> ' + data.str + '</p>';
        }
    }
	}).bind('typeahead:select', function(e, o) {
		$('#frm_bien_typeahead').typeahead('val', '').blur();
		var tid = 'iteration' + Date.now();
		$('#frm_home_biens').append('<div class="dissmissable-block"><button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button><button type="button" class="btn btn-danger delete confirmation"><span class="glyphicon glyphicon-remove"></span></button><div class="block_title">' + o.titre + '</div><div class="content"><input type="hidden" name="page[home_biens][' + tid + ']" class="form-control" value="' + o.id + '"><img src="' + o.image + '" style="float:left; margin-right:15px; height:108px;"><table class="table noborder" style="margin-bottom:0; width:auto"><tr><td><strong>ID : </strong>' + o.id + '</td><td><strong>Ref : </strong>' + o.ref + '</td></tr><tr><td colspan="2"><strong>Ville : </strong>' + o.ville + ' (' + o.cp + ')</td></tr><tr><td><strong>Superficie : </strong>' + o.superficie + 'm<sup>2</sup></td><td><strong>Prix : </strong>' + o.prix_formated + '€</td></tr></table></div></div>');
		initSortable();
	});
});
</script>
