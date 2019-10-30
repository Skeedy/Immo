<div class="title clearfix">
	<h1 class="pull-left">Tous les utilisateurs</h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
</div>

<hr>


<input type="text" class="form-control filtertext" data-target="#utilisateurslist" placeholder="Rechercher par nom, prénom ou email">

<br>

<div class="table-responsive">
<table id="utilisateurslist" class="table table-list table-striped">
	<thead>
		<tr>
			<th class="hassort"><?php printSortList('utilisateurs', 'Nom', 'nom', $_utilisateurs_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#utilisateurslist'); ?></th>
			<th class="hassort"><?php printSortList('utilisateurs', 'Prénom', 'prenom', $_utilisateurs_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#utilisateurslist'); ?></th>
			<th class="hassort"><?php printSortList('utilisateurs', 'Email', 'email', $_utilisateurs_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#utilisateurslist'); ?></th>
			<th class="hassort"><?php printSortList('utilisateurs', 'Droits', 'role', $_utilisateurs_sort, _ROOT_ADMIN.'?controller='.$_controller.'&view=list&', '#utilisateurslist'); ?></th>
			<?php
			if($_current_user->role == 'root') {
				echo '<th>Agences</th>';
			}
			?>
			<th class="text-center">Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($_utilisateurs as $u) {
		if($_current_user->role == 'root' || ($_current_user->role != 'root' && $u->role != 'root')) {
			?>
			<tr data-search="<?php echo htmlspecialchars(strip_tags(nl2br($u->nom.$u->prenom.$u->email)), ENT_QUOTES); ?>">
				<td>
					<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=utilisateur&id=<?php echo $u->id; ?>">
						<?php echo $u->nom; ?>
					</a>
				</td>
				<td>
					<a class="multiline" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=utilisateur&id=<?php echo $u->id; ?>">
						<?php echo $u->prenom; ?>
					</a>
				</td>
				<td>
					<a class="multiline" href="mailto:<?php echo $u->email; ?>"><?php echo $u->email; ?></a>
				</td>
				<td>
					<?php echo $u->role; ?>
				</td>
				<?php
				if($_current_user->role == 'root') {
					echo '<td>';
						$agences = array();
						foreach ($_agences as $v) {
							if(in_array($v->id, $u->agences)) {
								$nom = json_decode($v->nom);
								$agences[] = $nom->{_LANG_DEFAULT};
							}
						}
						echo implode(', ', $agences);
					echo '</td>';
				}
				?>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=utilisateur&id=<?php echo $u->id; ?>" title="Modifier"><span class="glyphicon glyphicon-edit"></span></a>
					<a class="btn btn-danger btn-sm" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=list&action=delete&id=<?php echo $u->id; ?>" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a>
				</td>
			</tr>
			<?php
		}
	}
	?>
	</tbody>
</table>
</div>
