<nav id="header" class="navbar">
	<div class="container-fluid">
		<a class="logo" href="<?php echo _ROOT; ?>"><span>LAPALUS</span></a>
		<div class="btn-group pull-right user">
			<button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<?php echo $_current_user->prenom.' '.$_current_user->nom; ?> <small class="text-lowercase<?php if($_current_user->role == 'administrateur' || $_current_user->role == 'root') echo ' text-danger'; ?>">(<?php echo $_current_user->role; ?>)</small> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="<?php echo _ROOT_ADMIN; ?>?controller=utilisateurs&view=utilisateur&id=<?php echo $_current_user->id; ?>" title="Mon compte"><i class="glyphicon glyphicon-user"></i> Mon compte</a></li>
				<li><a href="<?php echo _ROOT_ADMIN; ?>?controller=logout" title="Déconnexion"><i class="glyphicon glyphicon-off"></i> Déconnexion</a></li>
			</ul>
		</div>
	</div>
</nav>
