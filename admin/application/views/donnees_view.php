<ul class="nav nav-tabs">
	<?php
	if(empty($_tab_active))
		$_tab_active = $_tabs[0];

	if(in_array('types', $_tabs))
		echo '<li'.($_tab_active == 'types' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_types" data-toggle="tab">Types</a></li>';

	if(in_array('pieces', $_tabs))
		echo '<li'.($_tab_active == 'pieces' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_pieces" data-toggle="tab">Pièces</a></li>';

	if(in_array('decorations', $_tabs))
		echo '<li'.($_tab_active == 'decorations' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_decorations" data-toggle="tab">Décorations</a></li>';

	if(in_array('environnements', $_tabs))
		echo '<li'.($_tab_active == 'environnements' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_environnements" data-toggle="tab">Environnements</a></li>';

	if(in_array('elements', $_tabs))
		echo '<li'.($_tab_active == 'elements' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_elements" data-toggle="tab">Éléments</a></li>';

	if(in_array('matieres', $_tabs))
		echo '<li'.($_tab_active == 'matieres' ? ' class="active"' : '').'><a href="#tab_'.$_controller.'_matieres" data-toggle="tab">Matières</a></li>';

	?>
</ul>
<div class="tab-content">
	<?php
	if(in_array('types', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'types' ? ' active' : '').'" id="tab_'.$_controller.'_types">';
			include _DIR_VIEWS.'donnees_types_view.php';
		echo '</div>';
	}

	if(in_array('pieces', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'pieces' ? ' active' : '').'" id="tab_'.$_controller.'_pieces">';
			include _DIR_VIEWS.'donnees_pieces_view.php';
		echo '</div>';
	}

	if(in_array('decorations', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'decorations' ? ' active' : '').'" id="tab_'.$_controller.'_decorations">';
			include _DIR_VIEWS.'donnees_decorations_view.php';
		echo '</div>';
	}

	if(in_array('environnements', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'environnements' ? ' active' : '').'" id="tab_'.$_controller.'_environnements">';
			include _DIR_VIEWS.'donnees_environnements_view.php';
		echo '</div>';
	}

	if(in_array('elements', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'elements' ? ' active' : '').'" id="tab_'.$_controller.'_elements">';
			include _DIR_VIEWS.'donnees_elements_view.php';
		echo '</div>';
	}

	if(in_array('matieres', $_tabs)) {
		echo '<div class="tab-pane'.($_tab_active == 'matieres' ? ' active' : '').'" id="tab_'.$_controller.'_matieres">';
			include _DIR_VIEWS.'donnees_matieres_view.php';
		echo '</div>';
	}
	?>
</div>
<?php
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
//												 patterns
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//
?>
<div id="patterns">

	<div id="pattern_type" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="types_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="pattern_piece" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="pieces_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="pattern_decoration" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="decorations_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="pattern_environnement" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="environnements_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="pattern_element" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="elements_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="pattern_matiere" class="hidden">
		<div class="dissmissable-block simple">
			<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
			<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
			<input type="hidden" class="field_id" name="matieres_ordre[]" value="">
			<?php
			foreach($_LANGS as $l => $ll) {
				?>
				<div class="form-group lang_toggle lang_<?php echo $l; ?>">
					<div class="input-group">
						<span class="input-group-addon"><?php printToggleLang(); ?></span>
						<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

</div>
