<?php
//mise en forme des champs

//hook
if(isset($_POST['hook']))
	$frm_hook = $_POST['hook'];
else if(isset($_mail->hook))
	$frm_hook = $_mail->hook;
else
	$frm_hook = '';
	
//target
if(isset($_POST['target']))
	$frm_target = $_POST['target'];
else if(isset($_mail->target))
	$frm_target = $_mail->target;
else
	$frm_target = '';
	
//mail
if(isset($_POST['body']))
	$frm_mail = json_decode(json_encode($_POST['body']));
else if(!empty($_data))
	$frm_mail = $_data;
else
	$frm_mail = new stdClass();
?>

<form method="post">
	
	<div class="form-group">
		<label class="required" for="frm_hook">Déclencheur</label>
		<select id="frm_hook" name="hook" class="form-control" required>
			<?php
			foreach($_hooks as $v) {
				$fields = json_decode($v->fields);
				echo '<option value="'.$v->id.'" data-client="'.$v->client.'" data-proprietaire="'.$v->proprietaire.'" data-admin="'.$v->admin.'" data-fields="'.implode(' ', $fields->fields).'"'.($frm_hook == $v->id ? ' selected' : '').'>'.$v->label.'</option>';
			}
			?>
		</select>
	</div>
	
	<div class="form-group">
		<label class="required" for="frm_target">Cible</label>
		<select id="frm_target" name="target" class="form-control" required>
			<?php
			foreach(array('client', 'contact', 'admin') as $v)
				echo '<option value="'.$v.'"'.($frm_target == $v ? ' selected' : '').'>'.$v.'</option>';
			?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="frm_objet">Objet</label>
		<input type="text" id="frm_objet" name="mail[objet]" class="form-control" value="<?php echo !empty($frm_mail->objet) ? escHtml($frm_mail->objet) : ''; ?>">
	</div>
		
	<div class="form-group">
		<label for="frm_notify">Notifier (<?php echo $_PARAMS['mail_notif']; ?>)</label>
		<div>
			<button type="button" class="btn btn-onoff btn-danger btn-sm" data-on-text="Oui" data-off-text="Non">Non</button>
			<input type="hidden" id="frm_notify" name="mail[notify]" value="<?php echo !empty($frm_mail->notify) ? 1 : 0; ?>">
		</div>
	</div>
	
	<br>
	
	<div class="form-group">
		<label for="frm_body">Contenu</label>
		<p id="custom_client" class="custom_fields">
			<label>Client : </label>
			<span class="btn-group">
				<?php
				$fields = array(
					'client.prenom' => 'prénom',
					'client.nom' => 'nom',
					'client.email' => 'email',
					'client.telephone' => 'téléphone',
					'client.date' => 'date'
					
				);
				foreach($fields as $k => $v)
					echo '<span class="btn btn-default btn-sm btn_add_content" data-target="frm_body" data-value="{{'.$k.'}}">'.$v.'</span>';
				?>
			</span>
		</p>
		<p id="custom_annonce" class="custom_fields">
			<label>Annonce : </label>
			<span class="btn-group">
				<?php
				$fields = array(
					'annonce.id' => 'id',
					'annonce.ref' => 'ref',
					'annonce.url' => 'url',
					'annonce.titre' => 'titre',
					'annonce.date' => 'date',
					'annonce.nom_reel' => 'ville',
					'annonce.cp' => 'code postal',
					'annonce.image' => 'image'
					
				);
				foreach($fields as $k => $v)
					echo '<span class="btn btn-default btn-sm btn_add_content" data-target="frm_body" data-value="{{'.$k.'}}">'.$v.'</span>';
				?>
			</span>
		</p>
		<p id="custom_admin" class="custom_fields">
			<label>Admin : </label>
			<span class="btn-group">
				<?php
				$fields = array(
					'admin.id' => 'ID',
					'admin.email' => 'email',
					'admin.prenom' => 'prénom',
					'admin.nom' => 'nom'
				);
				foreach($fields as $k => $v)
					echo '<span class="btn btn-default btn-sm btn_add_content" data-target="frm_body" data-value="{{'.$k.'}}">'.$v.'</span>';
				?>
			</span>
		</p>
		<p id="custom_custom" class="custom_fields">
			<label>Spécifique : </label>
			<span class="btn-group">
				<?php
				foreach($_hooks as $v) {
					$fields = json_decode($v->fields);
					foreach($fields->custom as $k => $w)
						echo '<span class="btn btn-default btn-sm btn_add_content custom_fields custom_fields_custom_'.$v->id.'" data-target="frm_body" data-value="{{'.$k.'}}">'.$w.'</span>';
				}
				?>
			</span>
		</p>
		<textarea id="frm_body" class="form-control editor" rows="4" name="mail[body]"><?php if(!empty($frm_mail->body)) echo $frm_mail->body; ?></textarea>
	</div>
		
	
	<hr>
	
	<button type="submit" name="action_<?php echo !empty($_mail->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_mail->id) ? ' value="'.htmlspecialchars($_mail->id, ENT_QUOTES).'"' : ''; ?>>Enregistrer</button>
	
</form>

<script>
$(function() {
	
	$('#frm_hook').change(function() {
		var target = new Array('client', 'proprietaire', 'admin');
		for(i = 0; i < target.length; i++) {
			if($('option:selected', this).data(target[i]) == 1)
				$('#frm_target option[value="' + target[i] + '"]').prop('disabled', false).show();
			else
				$('#frm_target option[value="' + target[i] + '"]').prop('disabled', true).hide();
		}
		if($('#frm_target option:selected').is(':disabled')) {
			$('#frm_target option:selected').prop('selected', false);
			$('#frm_target option:not(:disabled)').first().prop('selected', true);
		}
		$('.custom_fields').hide();
		var fields = $('option:selected', this).data('fields').split(' ');
		for(i = 0; i < fields.length; i++)
			$('[id^="custom_' + fields[i] + '"]').show();
		$('.custom_fields_custom_' + $(this).val()).show();
	});
	
	$('.btn_add_content').click(function() {
		tinymce.get($(this).data('target')).execCommand('mceInsertContent', false, $(this).data('value'));
	});
	
	$('#frm_hook').change();
	
});
</script>
