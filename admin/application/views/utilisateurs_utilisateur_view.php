<div class="title clearfix">
	<h1 class="pull-left"><?php echo $_utilisateur->prenom.' '.$_utilisateur->nom; ?></h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
	<a class="btn btn-primary pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>"><span class="glyphicon glyphicon-arrow-left"></span> Tous les utilisateurs</a>
</div>

<hr>

<?php
include _DIR_FORMS.'utilisateur_form.php';

