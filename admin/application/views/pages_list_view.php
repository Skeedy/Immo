<div class="title clearfix">
	<h1 class="pull-left">Toutes les pages</h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
</div>

<hr>

<p><input type="text" class="form-control filtertext" data-target="#list" placeholder="Rechercher par id, date, titre, url ou activation"></p>

<div class="table-responsive">
<table id="list" class="table table-list table-striped">
	<thead>
		<tr>
			<th class="hassort minimum"><?php printSortList($_controller, 'ID', 'id', $_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#list'); ?></th>
			<th class="hassort"><?php printSortList($_controller, 'Titre', 'titre', $_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#list'); ?></th>
			<th class="hassort"><?php printSortList($_controller, 'URL', 'url', $_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#list'); ?></th>
			<th class="hassort"><?php printSortList($_controller, 'Date', 'date_creation', $_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#list'); ?></th>
			<th class="hassort minimum text-center"><?php printSortList($_controller, 'Publiée', 'active', $_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#list'); ?></th>
			<th class="text-center">Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($_items as $v) {
		$titre = json_decode($v->titre);
		?>
		<tr data-search="<?php echo htmlspecialchars(strip_tags(nl2br($v->id.date_create($v->date_creation)->format('d/m/Y H:i:s').date_create($v->date_modification)->format('d/m/Y H:i:s').$v->titre.$v->url.(!empty($v->active) ? 'activé' : 'non activé'))), ENT_QUOTES); ?>">
			<td>
				<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=id&id=<?php echo $v->id; ?>">
					<?php echo $v->id; ?>
				</a>
			</td>
			<td>
				<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=id&id=<?php echo $v->id; ?>">
					<?php echo $titre->{_LANG_DEFAULT}; ?>
				</a>
			</td>
			<td>
				<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=id&id=<?php echo $v->id; ?>">
					<?php echo $v->url; ?>
				</a>
			</td>
			<td>
				<table>
					<tr><td>Crée le : </td><td><?php echo date_create($v->date_creation)->format('d/m/Y à H:i:s'); ?></td></tr>
					<?php if($v->date_modification != $v->date_creation) echo '<tr><td>Modifiée le : </td><td>'.date_create($v->date_modification)->format('d/m/Y à H:i:s').'</td></tr>'; ?>
				</table>
			</td>
			<td class="text-center">
				<button type="button" class="btnswitch btn btn-sm nofocus btn-<?php echo empty($v->active) ? 'danger' : 'success'; ?>" data-id="<?php echo $v->id; ?>">
					<span class="glyphicon glyphicon-<?php echo empty($v->active) ? 'remove' : 'ok'; ?>"></span>
				</button>
			</td>
			<td class="text-center">
				<a class="btn btn-primary btn-sm" title="Détails" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=id&id=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-edit"></span></a>
				<a class="btn btn-danger btn-sm" title="Supprimer" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=list&action=delete&id=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</div>
<?php echo count($_items); ?> ligne<?php if(count($_items) > 1) echo 's'; ?>

<script>
$(function() {
	$('.btnswitch').click(function() {
		$el = $(this);
		$.ajax({
			type: "POST",
			url: "<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>",
			dataType: 'json',
			data: { switch: 1, id: $el.data('id') }
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
});
</script>