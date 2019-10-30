<?php
//mise en forme des champs

//login
if(isset($_POST['login']))
	$frm_user_login = htmlspecialchars($_POST['login'], ENT_QUOTES);
else if(isset($_utilisateur->login))
	$frm_user_login = htmlspecialchars($_utilisateur->login, ENT_QUOTES);
else
	$frm_user_login = '';

//chpasswd
if(!empty($_POST))
	$frm_user_chpasswd = htmlspecialchars(!empty($_POST['chpasswd']) ? $_POST['chpasswd'] : 0, ENT_QUOTES);
else
	$frm_user_chpasswd = 0;

//password
if(isset($_POST['password']))
	$frm_user_password = htmlspecialchars($_POST['password'], ENT_QUOTES);
else
	$frm_user_password = '';

//password_confirm
if(isset($_POST['password_confirm']))
	$frm_user_password_confirm = htmlspecialchars($_POST['password_confirm'], ENT_QUOTES);
else
	$frm_user_password_confirm = '';

//prenom
if(isset($_POST['prenom']))
	$frm_user_prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES);
else if(isset($_utilisateur->prenom))
	$frm_user_prenom = htmlspecialchars($_utilisateur->prenom, ENT_QUOTES);
else
	$frm_user_prenom = '';
	
//nom
if(isset($_POST['nom']))
	$frm_user_nom = htmlspecialchars($_POST['nom'], ENT_QUOTES);
else if(isset($_utilisateur->nom))
	$frm_user_nom = htmlspecialchars($_utilisateur->nom, ENT_QUOTES);
else
	$frm_user_nom = '';

//email
if(isset($_POST['email']))
	$frm_user_email = htmlspecialchars($_POST['email'], ENT_QUOTES);
else if(isset($_utilisateur->email))
	$frm_user_email = htmlspecialchars($_utilisateur->email, ENT_QUOTES);
else
	$frm_user_email = '';

//role
if(isset($_POST['role']))
	$frm_user_role = htmlspecialchars($_POST['role'], ENT_QUOTES);
else if(isset($_utilisateur->role))
	$frm_user_role = htmlspecialchars($_utilisateur->role, ENT_QUOTES);
else
	$frm_user_role = '';

?>
<form method="post">

	<div class="row">
		<div class="col-sm-6">
			<h3 class="cat">Connexion</h3>
			<fieldset class="well">
				<div class="form-group">
					<label class="required" for="frm_user_login">Identifiant</label>
					<input type="text" id="frm_user_login" name="login" class="form-control" value="<?php echo $frm_user_login; ?>" required>
				</div>
				<?php
				if(!empty($_utilisateur)) {
					?>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="frm_user_chpasswd" name="chpasswd" value="1"<?php if($frm_user_chpasswd == 1) echo ' checked'; ?>>
							<span>Modifier le mot de passe</span>
						</label>
					</div>
						
					<div id="frm_user_passwdzone">
						<div class="form-group">
							<label class="<?php echo $frm_user_chpasswd == 1 ? '' : 'text-muted'; ?>" for="frm_user_password">Mot de passe</label>
							<input type="password" id="frm_user_password" name="password" class="form-control" value="<?php echo $frm_user_password; ?>"<?php echo $frm_user_chpasswd == 1 ? '' : ' disabled'; ?> placeholder="Laissez vide pour générer un mot de passe aléatoire">
						</div>
						<div class="form-group">
							<label class="<?php echo $frm_user_chpasswd == 1 ? '' : 'text-muted'; ?>" for="frm_user_password_confirm">Confirmation</label>
							<input type="password" id="frm_user_password_confirm" name="password_confirm" class="form-control" value="<?php echo $frm_user_password_confirm; ?>"<?php echo $frm_user_chpasswd == 1 ? '' : ' disabled'; ?> placeholder="Laissez vide pour générer un mot de passe aléatoire">
						</div>
					</div>
					<?php
				}
				else {
					?>
					<div class="form-group">
						<label class="required" for="frm_user_password">Mot de passe</label>
						<input type="password" id="frm_user_password" name="password" class="form-control" value="<?php echo $frm_user_password; ?>" placeholder="Laissez vide pour générer un mot de passe aléatoire" required>
					</div>
					<div class="form-group">
						<label class="required" for="frm_user_password_confirm">Confirmation</label>
						<input type="password" id="frm_user_password_confirm" name="password_confirm" class="form-control" value="<?php echo $frm_user_password_confirm; ?>" placeholder="Laissez vide pour générer un mot de passe aléatoire" required>
					</div>
					<?php
				}
				?>
			</fieldset>
		</div>
		<div class="col-sm-6">
			<h3 class="cat">Informations</h3>
			<fieldset class="well">
				<div class="form-group">
					<label class="required" for="frm_user_prenom">Prénom</label>
					<input type="text" id="frm_user_prenom" name="prenom" class="form-control" value="<?php echo $frm_user_prenom; ?>" required>
				</div>
				<div class="form-group">
					<label class="required" for="frm_user_nom">Nom</label>
					<input type="text" id="frm_user_nom" name="nom" class="form-control" value="<?php echo $frm_user_nom; ?>" required>
				</div>
				<div class="form-group">
					<label class="required" for="frm_user_email">Email</label>
					<input type="email" id="frm_user_email" name="email" class="form-control" value="<?php echo $frm_user_email; ?>" required>
				</div>
			</fieldset>
		</div>
	</div>

	<?php
	if($_current_user->role == 'root') {
		?>
		<div class="row">
			<div class="col-sm-6">
				<h3 class="cat">Droits d'accès</h3>
				<fieldset class="well">
					<div class="form-group">
						<select class="form-control" id="frm_user_role" name="role" required>
							<?php
							foreach(array('negociateur', 'administrateur', 'root') as $v)
								echo '<option value="'.$v.'"'.($v == $frm_user_role ? ' selected' : '').'>'.$v.'</option>';
							?>
						</select>
					</div>
				</fieldset>
			</div>
			<div class="col-sm-6">
				<h3 class="cat">Agences</h3>
				<fieldset class="well">
					<div class="form-group">
						<?php
						foreach($_agences as $v) {
							$nom = json_decode($v->nom);
							?>
							<label class="checkbox-inline">
								<input type="checkbox" name="agences[]" value="<?php echo $v->id; ?>"<?php if(!empty($_utilisateur->agences) && in_array($v->id, $_utilisateur->agences)) echo ' checked'; ?>><span><?php echo $nom->{_LANG_DEFAULT}; ?></span>
							</label>
							<?php
						}
						?>
					</div>
				</fieldset>
			</div>
		</div>
		<?php
	}
	else if($_current_user->role == 'administrateur') {
		?>
		<h3 class="cat">Droits d'accès</h3>
		<fieldset class="well">
			<div class="form-group">
				<select class="form-control" id="frm_user_role" name="role" required>
					<?php
					foreach(array('negociateur', 'administrateur') as $v)
						echo '<option value="'.$v.'"'.($v == $frm_user_role ? ' selected' : '').'>'.$v.'</option>';
					?>
				</select>
			</div>
		</fieldset>
		<?php
	}
	else {
		if(!empty($_utilisateur->agences)) {
			?>
			<h3 class="cat">Agences</h3>
			<fieldset class="well">
				<ul class="list-unstyled">
					<?php
					foreach ($_agences as $v) {
						if(in_array($v->id, $_utilisateur->agences)) {
							$nom = json_decode($v->nom);
							echo '<li><span class="glyphicon glyphicon-ok text-success"></span> '.$nom->{_LANG_DEFAULT}.'</li>';
						}
					}
					?>
				</ul>
			</fieldset>
			<?php
		}
	}
	?>

	<hr>
	
	<button type="submit" name="action_<?php echo !empty($_utilisateur->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_utilisateur->id) ? ' value="'.$_utilisateur->id.'"' : ''; ?>>Enregistrer</button>
</form>

<script>
$(function() {
	$('#frm_user_chpasswd').change(function() {
		if($(this).prop('checked')) {
			$('#frm_user_passwdzone label').removeClass('text-muted');
			$('#frm_user_passwdzone input').prop('disabled', false);
		}
		else {
			$('#frm_user_passwdzone label').addClass('text-muted');
			$('#frm_user_passwdzone input').prop('disabled', true);
		}
	});
});
</script>
