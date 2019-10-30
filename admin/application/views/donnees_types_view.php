<form method="post">

	<div class="list-sortable-handle-dissmissable">
		<?php
		foreach($_types as $v) {
			$v->label = json_decode($v->label);
			?>
			<div class="dissmissable-block simple">
				<button type="button" class="btn btn-primary sort nofocus"><span class="glyphicon glyphicon-resize-vertical"></span></button>
				<button type="button" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></button>
				<input type="hidden" class="field_id" name="types_ordre[]" value="<?php echo escHtml($v->id); ?>">
				<?php
				foreach($_LANGS as $l => $ll) {
					?>
					<div class="form-group lang_toggle lang_<?php echo $l; ?>">
						<div class="input-group">
							<span class="input-group-addon"><?php printToggleLang(); ?></span>
							<input type="text" name="label[<?php echo $l; ?>][]" class="form-control required" value="<?php echo escHtml($v->label->{$l}); ?>">
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>

	<div class="form-group">
		<a href="#" class="addsection btn btn-primary nofocus" data-pattern="#pattern_type" data-name="" data-count="> .dissmissable-block"><span class="glyphicon glyphicon-plus"></span> Ajouter un type</a>
	</div>

	<hr>

	<button type="submit" name="action_types_modify" class="btn btn-lg btn-success">Enregistrer</button>
</form>