<div class="title clearfix">
	<h1 class="pull-left"><?php echo $_item->titre->{_LANG_DEFAULT}; ?></h1>
	<a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
	<a class="btn btn-primary pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=list"><span class="glyphicon glyphicon-arrow-left"></span> Toutes les pages</a>
</div>

<hr>

<?php
include _DIR_FORMS.'page_form.php';

