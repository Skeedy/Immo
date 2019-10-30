<div class="title clearfix">
	<h1 class="pull-left">Tous les emails</h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
</div>

<hr>

<div class="row">
	<div class="col-md-6">
		<input type="text" class="form-control filtertext" data-target="#mailslist" placeholder="Rechercher par id, déclencheur, objet ou cible">
	</div>
</div>

<br>

<div class="table-responsive">
<table id="mailslist" class="table table-list table-striped table-hover2">
	<thead>
		<tr>
			<th class="hassort"><?php printSortList('mails', 'ID', 'id', $_mails_sort, _ROOT_ADMIN.'?controller='.$_controller.'&', '#mailslist'); ?></th>
			<th class="hassort"><?php printSortList('mails', 'Déclencheur', 'hook', $_mails_sort, _ROOT_ADMIN.'?controller='.$_controller.'&', '#mailslist'); ?></th>
			<th>Objet</th>
			<th class="hassort"><?php printSortList('mails', 'Cible', 'target', $_mails_sort, _ROOT_ADMIN.'?controller='.$_controller.'&', '#mailslist'); ?></th>
			<th>Notif</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($_mails as $v) {
		$data = json_decode($v->data);
		?>
		<tr data-search="<?php echo htmlspecialchars(strip_tags(nl2br($v->id.$v->label.$v->objet.$v->target)), ENT_QUOTES); ?>">
			<td>
				<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=mail&mail_id=<?php echo $v->id; ?>">
					<?php echo $v->id; ?>
				</a>
			</td>
			<td>
				<?php echo $v->label; ?>
			</td>
			<td>
				<a class="multiline text-limit" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=mail&mail_id=<?php echo $v->id; ?>">
					<?php echo $data->objet; ?>
				</a>
			</td>
			<td>
				<?php echo $v->target; ?>
			</td>
			<td>
				<span class="label label-<?php echo !empty($data->notify) ? 'success' : 'danger'; ?>"><?php echo !empty($data->notify) ? 'Oui' : 'Non'; ?></span>
			</td>
			<td>
				<a class="btn btn-primary btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=mail&mail_id=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-edit"></span></a>
				<a class="btn btn-danger btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=delete_mail&mail_id=<?php echo $v->id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</div>
<?php echo count($_mails); ?> ligne<?php if(count($_mails) > 1) echo 's'; ?>
