<?php
$url = _ROOT_ADMIN.'?controller='.$_controller.'&view=list&annoncessortby='.$_annonces_sort;

if(isset($_GET['s_id'])) {
	$frm_s_id = $_GET['s_id'];
	$url .= '&s_id='.urlencode($_GET['s_id']);
}
else
	$frm_s_id = '';

if(isset($_GET['id'])) {
	$frm_id = $_GET['id'];
	$url .= '&id='.urlencode($_GET['id']);
}
else
	$frm_id = '';

if(isset($_GET['s_ref'])) {
	$frm_s_ref = $_GET['s_ref'];
	$url .= '&s_ref='.urlencode($_GET['s_ref']);
}
else
	$frm_s_ref = '';

if(isset($_GET['ref'])) {
	$frm_ref = $_GET['ref'];
	$url .= '&ref='.urlencode($_GET['ref']);
}
else
	$frm_ref = '';

if(isset($_GET['s_titre'])) {
	$frm_s_titre = $_GET['s_titre'];
	$url .= '&s_titre='.urlencode($_GET['s_titre']);
}
else
	$frm_s_titre = '';

if(isset($_GET['s_ville'])) {
	$frm_s_ville = $_GET['s_ville'];
	$url .= '&s_ville='.urlencode($_GET['s_ville']);
}
else
	$frm_s_ville = '';

if(isset($_GET['ville'])) {
	$frm_ville = $_GET['ville'];
	$url .= '&ville='.urlencode($_GET['ville']);
}
else
	$frm_ville = '';

//url liste actuelle ($url + page)
$url_current = $url.(!empty($_GET['p']) ? '&p='.$_GET['p'] : '');

if(!isAjax()) {
?>
<div class="title clearfix">
	<h1 class="pull-left">Tous les biens à vendre / louer</h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
</div>

<hr>

<p><strong>Résultats <?php echo ($_annonces->total > 0 ? ($_annonces->page - 1) * _NB_PAR_PAGE + 1 : 0).' ~ '.(($_annonces->page) * (_NB_PAR_PAGE) < $_annonces->total ? ($_annonces->page) * (_NB_PAR_PAGE) : $_annonces->total).' sur '.$_annonces->total; ?></strong></p>

<form method="get">
<div class="table-responsive">
<table id="annonceslist" class="table table-list table-striped">
	<thead>
		<tr>
			<th class="hassort minimum"><?php printSortList('annonces', 'Ordre ', 'ordre', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th>Image</th>
			<th class="hassort minimum"><?php printSortList('annonces', 'ID ', 'id', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Ref', 'ref', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
            <th class="hassort"><?php printSortList('annonces', 'Offre', 'isLocation', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Date', 'date', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Titre', 'titre', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Ville', 'nom_reel', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Prix', 'prix', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort"><?php printSortList('annonces', 'Contact', 'contact', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th class="hassort minimum text-center"><?php printSortList('annonces', 'Publié', 'active', $_annonces_sort, $url.'&', '#annonceslist'); ?></th>
			<th>Actions</th>
		</tr>
		<tr class="filter">
			<th></th>
			<th>
				<input type="hidden" name="controller" value="biens">
				<input type="hidden" name="view" value="list">
				<input type="hidden" name="annoncessortby" value="<?php echo $_annonces_sort; ?>">
			</th>
			<th>
				<input type="text" class="form-control input-sm" id="frm_id_typeahead" name="s_id" value="<?php echo escHtml($frm_s_id); ?>">
				<input type="hidden" id="frm_id" name="id" value="<?php echo escHtml($frm_id); ?>">
			</th>
			<th>
				<input type="text" class="form-control input-sm" id="frm_ref_typeahead" name="s_ref" value="<?php echo escHtml($frm_s_ref); ?>">
				<input type="hidden" id="frm_ref" name="ref" value="<?php echo escHtml($frm_ref); ?>">
			</th>
			<th></th>
			<th>
				<input type="text" class="form-control input-sm" id="frm_titre_typeahead" name="s_titre" value="<?php echo escHtml($frm_s_titre); ?>">
			</th>
			<th>
				<input type="text" class="form-control input-sm" id="frm_ville_typeahead" name="s_ville" value="<?php echo escHtml($frm_s_ville); ?>">
				<input type="hidden" id="frm_ville" name="ville" value="<?php echo escHtml($frm_ville); ?>">
			</th>
			<th></th>
            <th></th>
			<th></th>
			<th></th>
			<th>
				<button class="btn btn-sm btn-success" type="submit" name="filter">Filtrer</button>
				<a class="btn btn-sm btn-danger" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller.'&view=list'; ?>">Reset</a>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	//fin filtre ajax
	}
	
	if(!empty($_annonces->annonces)) {
		foreach($_annonces->annonces as $v) {
			$images = !empty($v->images) && json_decode($v->images) ? json_decode($v->images) : array();
			$data = json_decode($v->data);
			$v->titre = json_decode($v->titre);
			$v->type_label = json_decode($v->type_label);
			?>
			<tr id="annonce_<?php echo $v->id; ?>">
				<td class="minimum text-center">
					<button type="button" class="btn btn-link btn_ch_position" data-direction="up" data-id="<?php echo $v->id; ?>" data-url="<?php echo $url_current; ?>&view=list"><span class="glyphicon glyphicon-triangle-top"></span></button>
					<div class="ordre_value"></div>
					<button type="button" class="btn btn-link btn_ch_position" data-direction="down" data-id="<?php echo $v->id; ?>" data-url="<?php echo $url_current; ?>&view=list"><span class="glyphicon glyphicon-triangle-bottom"></span></button>
				</td>
				<td class="minimum">
					<?php
					if(!empty($images)) {
						?>
						<a class="multiline" style="position:relative" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
							<img src="<?php echo _ROOT.$db_annonce->getAnnonceDirImg($v->id).'sm_'.$images[0]->image; ?>" alt="">
							<span class="badge" style="position:absolute; bottom:-5px; right:-5px;"><?php echo count($images); ?></span>
						</a>
						<?php
					}
					else if(!empty($_PARAMS['annonce_no_img']))
						echo '<img src="'._ROOT._DIR_MEDIA.$_PARAMS['annonce_no_img'].'" alt="aucune image">';
					?>
				</td>
				<td>
					<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
						<?php echo $v->id; ?>
					</a>
				</td>
				<td>
					<a class="multiline nowrap" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
						<?php echo $v->ref; ?>
					</a>
				</td>
                <td>
                    <a class="multiline nowrap" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
                        <?php echo $v->isLocation? 'Location': 'Vente'; ?>
                    </a>
                </td>
				<td class="minimum text-center">
					<?php
					$date = date_create($v->date);
					echo $date->format('d/m/Y').'<br>'.$date->format('H:i:s');
					?>
				</td>
				<td>
					<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
						<?php echo $v->titre->{_LANG_DEFAULT}; ?>
					</a><br>
					<?php echo $v->type_label->{_LANG_DEFAULT}; ?>
					<?php if(isset($v->superficie)) echo $v->superficie.'m²'; ?>
				</td>
				<td>
					<?php if(!empty($v->ville)) echo $v->nom_reel.' ('.$v->cp.')'; ?>
				</td>
				<td class="minimum nowrap text-right text-<?php echo isset($data->afficher_prix) && empty($data->afficher_prix) ? 'danger' : 'success'; ?>">
					<?php if(isset($v->prix)) echo number_format($v->prix, floor($v->prix) == $v->prix ? 0 : 2, ',', ' ').' €'; ?>
				</td>
                <td <a class="multiline nowrap" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>">
                    <?php echo $v->equipe_prenom . ' ' . $v->equipe_nom; ?></a>
                </td>
				<td class="text-center">
					<button type="button" class="btnswitchannonce btn btn-sm nofocus btn-<?php echo empty($v->active) ? 'danger' : 'success'; ?>" data-id="<?php echo $v->id; ?>">
						<span class="glyphicon glyphicon-<?php echo empty($v->active) ? 'remove' : 'ok'; ?>"></span>
					</button>
				</td>
				<td class="text-center">
					<a class="btn btn-success btn-sm" title="Dupliquer" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add&model=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-plus"></span></a>
					&nbsp;
					<a class="btn btn-default btn-sm" target="_blank" title="Fiche PDF" href="<?php echo _ROOT . $v->id; ?>-export?pdf"><span class="glyphicon glyphicon-print"></span></a>
					&nbsp;
					<a class="btn btn-primary btn-sm" title="Détails" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=bien&bien=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-edit"></span></a>
					<a class="btn btn-danger btn-sm" title="Supprimer" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=list&urlreturn=<?php echo urlencode($url_current); ?>&cancel_url=<?php echo urlencode($url_current); ?>&action=delete_bien&bien=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
				</td>
			</tr>
			<?php
		}
	}

	if(!isAjax()) {
	?>
	</tbody>
</table>
</div>
</form>

<nav class="text-center">
	<?php
	printPagination($_annonces->page_max, $_annonces->page, $url.'&p=');
	?>
</nav>
<?php
if(empty($_annonces->annonces))
	echo '<p>Aucun bien</p>';
?>
<script>
$(function() {
	$('.btnswitchannonce').click(function() {
		$el = $(this);
		$.ajax({
			type: "POST",
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>",
			dataType: 'json',
			data: { switchannonce: 1, annonce: $el.data('id') }
		}).done(function(data) {
			if(data.hasOwnProperty('active')) {
				$el.removeClass('btn-danger btn-success').html('');
				if(data.active == 1)
					$el.addClass('btn-success').html('<span class="glyphicon glyphicon-ok"></span>');
				else
					$el.addClass('btn-danger').html('<span class="glyphicon glyphicon-remove"></span>');
				if(data.hasOwnProperty('error'))
					alert(data.error);
			}
		});
	});


	var annonces_ids = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchannonces_ids=",
			prepare: function(query, settings) {
				settings.url = settings.url += encodeURIComponent(query);
				return settings;
			}
		},
		identify: function(obj) { return obj.id; }
	});
	annonces_ids.initialize();
	$('#frm_id_typeahead').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		displayKey: 'id',
		source: annonces_ids,
		limit: 8
	}).bind('typeahead:select', function(e, o) {
		$('#frm_id').val(o.id);
	}).bind('keypress', function(e, o) {
		$('#frm_id').val('');
	});

	var annonces_refs = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchannonces_refs=",
			prepare: function(query, settings) {
				settings.url = settings.url += encodeURIComponent(query);
				return settings;
			}
		},
		identify: function(obj) { return obj.id; }
	});
	annonces_refs.initialize();
	$('#frm_ref_typeahead').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		displayKey: 'ref',
		source: annonces_refs,
		limit: 8
	}).bind('typeahead:select', function(e, o) {
		$('#frm_ref').val(o.ref);
	}).bind('keypress', function(e, o) {
		$('#frm_ref').val('');
	});

	var titres = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchtitres=",
			prepare: function(query, settings) {
				settings.url = settings.url += encodeURIComponent(query);
				return settings;
			}
		},
		identify: function(obj) { return obj.id; }
	});
	titres.initialize();
	$('#frm_titre_typeahead').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		displayKey: 'titre',
		source: titres,
		limit: 8
	});

	var annoncesvilles = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&searchannoncesvilles=",
			prepare: function(query, settings) {
				settings.url = settings.url += encodeURIComponent(query);
				return settings;
			}
		},
		identify: function(obj) { return obj.id; }
	});
	annoncesvilles.initialize();
	$('#frm_ville_typeahead').typeahead({
		hint: false,
		highlight: true,
		minLength: 0
	}, {
		displayKey: 'str',
		source: annoncesvilles,
		limit: 8
	}).bind('typeahead:select', function(e, o) {
		$('#frm_ville').val(o.id);
	}).bind('keypress', function(e, o) {
		$('#frm_ville').val('');
	});

	$('body #annonceslist').on('click', '.btn_ch_position', function() {
		var $btn = $(this);
		$.ajax({
			type: "POST",
			url: $btn.data('url'),
			dataType: 'json',
			data: { ch_position: $btn.data('direction'), id: $btn.data('id')}
		}).done(function(data) {
			if(data.hasOwnProperty('error'))
				alert(data.error);
			if(data.hasOwnProperty('html'))
				$('#annonceslist tbody').html(data.html);
		});
	});
	
});
</script>
<?php
//fin filtre ajax
}
