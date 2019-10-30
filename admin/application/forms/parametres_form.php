<form method="post">
	<?php
	$i = 0;
	foreach($_parametres as $p) {
		$p->value = decodeDirs($p->value);
		if($p->type == 'hidden') {
			echo '<input type="hidden" name="parametres['.$i.'][id]" value="'.escHtml($p->id).'">';
			echo '<input type="hidden" name="parametres['.$i.'][type]" value="'.escHtml($p->type).'">';
			echo '<input type="hidden" name="parametres['.$i.'][value]" value="'.escHtml($p->value).'">';
		}
		else {
			?>
			<div class="form-group">
				<input type="hidden" name="parametres[<?php echo $i; ?>][id]" value="<?php echo escHtml($p->id); ?>">
				<input type="hidden" name="parametres[<?php echo $i; ?>][type]" class="form-control" value="<?php echo escHtml($p->type); ?>">
				<label for="frm_<?php echo $i; ?>"><?php echo $p->id; ?></label>
				<?php
				if($p->type == 'text') {
					?>
					<input type="text" id="frm_<?php echo $i; ?>" name="parametres[<?php echo $i; ?>][value]" class="form-control" value="<?php echo escHtml($p->value); ?>">
					<?php
				}
				else if($p->type == 'textarea') {
					?>
					<textarea id="frm_<?php echo $i; ?>" name="parametres[<?php echo $i; ?>][value]" class="form-control" rows="3"><?php echo $p->value; ?></textarea>
					<?php
				}
				else if($p->type == 'html') {
					?>
					<textarea class="form-control editor" rows="4" name="parametres[<?php echo $i; ?>][value]"><?php echo $p->value; ?></textarea>
					<?php
				}
				else if($p->type == 'color') {
					?>
					<input type="color" id="frm_<?php echo $i; ?>" name="parametres[<?php echo $i; ?>][value]" class="form-control" value="<?php echo escHtml($p->value); ?>">
					<?php
				}
				else if($p->type == 'image') {
					?>
					<div>
						<input type="hidden" id="frm_<?php echo $i; ?>" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), false, 'parametres[<?php echo $i; ?>][value]');">
						<a class="fancybox btn btn-warning btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN._DIR_LIB; ?>filemanager/filemanager/dialog.php?type=1&field_id=frm_<?php echo $i; ?>"><i class="glyphicon glyphicon-picture"></i> Image</a>
						<div class="row images-list list-sortable">
							<?php
							if(!empty($p->value)) {	
								?>
								<div class="thumb" style="background-image:url(<?php echo _ROOT._DIR_THUMBS.$p->value; ?>);">
									<a href="<?php echo _ROOT._DIR_MEDIA.$p->value; ?>" class="fancybox_img"></a>
									<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<input type="hidden" name="parametres[<?php echo $i; ?>][value]" value="<?php echo escHtml($p->value); ?>">
								</div>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				}
				else if(preg_match('/^select\[(.*)\]$/', $p->type, $vals)) {
					$values = explode(',', $vals[1]);
					if(!empty($values)) {
						?>
						<select id="frm_<?php echo $i; ?>" name="parametres[<?php echo $i; ?>][value]" class="form-control">
							<?php
							foreach($values as $v)
								echo '<option value="'.escHtml($v).'"'.($v == escHtml($p->value) ? ' selected' : '').'>'.escHtml($v).'</option>';
							?>
						</select>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
		$i++;
	}
	?>
	
	<hr>
	
	<button type="submit" name="action_modify" class="btn btn-lg btn-success">Enregistrer</button>
</form>
