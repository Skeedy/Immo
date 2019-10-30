<div class="title clearfix">
    <h1 class="pull-left"><?php echo $_renovation->titre; ?></h1>
    <a class="btn btn-success pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>&view=add"><span class="glyphicon glyphicon-plus"></span> Ajouter</a>
    <a class="btn btn-primary pull-right" href="<?php echo _ROOT_ADMIN.'?controller='.$_controller; ?>"><span
                class="glyphicon glyphicon-arrow-left"></span> Toutes les rénovations</a>
</div>

<hr>

<?php
include _DIR_FORMS.'renovation_form.php';

