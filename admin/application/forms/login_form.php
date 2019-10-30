<?php
//mise en forme des variables
$frm_login_login = !empty($_POST['login']) ? htmlspecialchars($_POST['login'], ENT_QUOTES) : '';
$frm_login_password = !empty($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : '';
$frm_recover_email = !empty($_POST['recover_email']) ? htmlspecialchars($_POST['recover_email'], ENT_QUOTES) : '';

?>
<form method="post">	
	<?php
	if(!empty($_ALERTS))
		echo $_ALERTS;
	?>
	<div class="form-group has-feedback">
		<label class="sr-only" for="frm_login_login">Identifiant</label>
		<input type="text" id="frm_login_login" name="login" class="form-control" placeholder="Identifiant" value="<?php echo $frm_login_login; ?>" required autofocus>
		<span class="glyphicon glyphicon-user form-control-feedback"></span>
	</div>
	<div class="form-group has-feedback">
		<label class="sr-only" for="frm_login_password">Mot de passe</label>
		<input type="password" id="frm_login_password" name="password" class="form-control" placeholder="Mot de passe" value="<?php echo $frm_login_password; ?>" required>
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-lg">Se connecter</button>
	</div>
</form>

<p><a href="#" class="show_recover_form">Mot de passe oublié ?</a></p>

<form id="frm_recover" method="post"<?php if(empty($_entites_recover)) echo ' style="display:none;"'; ?>>
	<hr>
	<p>Entrez votre adresse email pour recevoir un nouveau mot de passe.</p>
	<div class="form-group">
		<label class="sr-only" for="frm_recover_email">Email</label>
		<input type="email" id="frm_recover_email" name="recover_email" class="form-control" placeholder="Email" value="<?php echo $frm_recover_email; ?>" required>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-lg">Valider</button>
	</div>
	
</form>

<script>
$(function() {
	$('.show_recover_form').click(function(e) {
		e.preventDefault();
		$('#frm_recover').show();
		$(this).parent().hide();
	});
});
</script>
