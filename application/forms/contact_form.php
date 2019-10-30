<form method="post" class="form-ajax mt-3" action="<?php echo _ROOT_LANG; ?>">
	<input type="hidden" name="form" value="contact">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="" for="frm_contact_nom">Nom </label>
				<input id="frm_contact_nom" type="text" name="nom" class="form-control" required>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="" for="frm_contact_prenom">Prénom </label>
				<input id="frm_contact_prenom" type="text" name="prenom" class="form-control" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="" for="frm_contact_telephone">Téléphone </label>
				<input id="frm_contact_telephone" type="number" name="telephone" class="form-control" required>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="" for="frm_contact_email">Email </label>
				<input id="frm_contact_email" type="email" name="email" class="form-control" required>
			</div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-sm-12">
			<div class="form-group ">
				<label class="" for="frm_contact_message">Message </label>
				<textarea id="frm_contact_message" name="data[message]" class="form-control" rows="9" required></textarea>
			</div>
		</div>
	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn submit_button nofocus">Envoyer</button>
	</div>
	<div class="frm_zone_message text-center"></div>
</form>
