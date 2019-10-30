<div class="title clearfix">
	<h1 class="pull-left"><?php echo json_decode($_annonce->titre)->{_LANG_DEFAULT}; ?></h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
	<a class="btn btn-primary pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=<?php echo !empty($_annonce->date_vente) ? 'vendus' : 'list'; ?>"><span class="glyphicon glyphicon-arrow-left"></span> Tous les lieux<?php if(!empty($_annonce->date_vente)) echo ' vendus'; ?></a>
</div>

<hr>

<?php
include _DIR_FORMS.'annonce_form.php';