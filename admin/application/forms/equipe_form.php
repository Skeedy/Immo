<?php
//mise en forme des champs

//prenom
if(isset($_POST['prenom']))
    $frm_user_prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES);
else if(isset($_equipe->prenom))
    $frm_user_prenom = htmlspecialchars($_equipe->prenom, ENT_QUOTES);
else
    $frm_user_prenom = '';


//nom
if(isset($_POST['nom']))
    $frm_user_nom = htmlspecialchars($_POST['nom'], ENT_QUOTES);
else if(isset($_equipe->nom))
    $frm_user_nom = htmlspecialchars($_equipe->nom, ENT_QUOTES);
else
    $frm_user_nom = '';

//email
if(isset($_POST['email']))
    $frm_user_email = htmlspecialchars($_POST['email'], ENT_QUOTES);
else if(isset($_equipe->email))
    $frm_user_email = htmlspecialchars($_equipe->email, ENT_QUOTES);
else
    $frm_user_email = '';


//profession
if(isset($_POST['profession']))
    $frm_user_profession = htmlspecialchars($_POST['profession'], ENT_QUOTES);
else if(isset($_equipe->profession))
    $frm_user_profession = htmlspecialchars($_equipe->profession, ENT_QUOTES);
else
    $frm_user_profession = '';

//téléphone
if(isset($_POST['telephone']))
    $frm_user_telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES);
else if(isset($_equipe->telephone))
    $frm_user_telephone = htmlspecialchars($_equipe->telephone, ENT_QUOTES);
else
    $frm_user_telephone = '';

//image
if(isset($_POST['image']))
    $frm_user_image = htmlspecialchars($_POST['image'], ENT_QUOTES);
else if(isset($_equipe->img))
    $frm_user_image = htmlspecialchars($_equipe->img, ENT_QUOTES);
else
    $frm_user_image = '';

//description
if(isset($_POST['description']))
    $frm_user_description = htmlspecialchars($_POST['description'], ENT_QUOTES);
else if(isset($_equipe->description))
    $frm_user_description = htmlspecialchars($_equipe->description, ENT_QUOTES);
else
    $frm_user_description = '';

//isActive
if(isset($_POST['isActive']))
    $frm_annonce_isActive = htmlspecialchars($_POST['isActive'], ENT_QUOTES);
else if(isset($_equipe->isActive))
    $frm_annonce_isActive = htmlspecialchars($_equipe->isActive, ENT_QUOTES);
else
    $frm_annonce_isActive = '';
?>
<form method="post">

	<div class="row">

		<div class="col-sm-12">

			<h3 class="cat">Informations</h3>
			<fieldset class="well">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="required" for="frm_equipier_prenom">Prénom</label>
						<input type="text" id="frm_equipier_prenom" name="prenom" class="form-control" value="<?php echo $frm_user_prenom; ?>" required>
					</div>
					<div class="form-group">
						<label class="required" for="frm_equipier_prenom">Nom</label>
						<input type="text" id="frm_equipier_prenom" name="nom" class="form-control" value="<?php echo $frm_user_nom; ?>" required>
					</div>
					<div class="form-group">
						<label class="required" for="frm_equipier_email">Email</label>
						<input type="email" id="frm_equipier_email" name="email" class="form-control" value="<?php echo $frm_user_email; ?>" required>
					</div>
					<div class="form-group">
						<label class="required" for="frm_equipier_description">Description</label>
						<textarea type="text" rows="4" id="frm_equipier_description" name="description" class="form-control" required><?php echo$frm_user_description; ?>
						</textarea>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="required" for="frm_equipier_telephone">Téléphone</label>
						<input type="text" id="frm_equipier_telephone" name="telephone" class="form-control" value="<?php echo $frm_user_telephone; ?>" required>
					</div>
					<div class="form-group">
						<label class="required" for="frm_equipier_profession">Profession</label>
						<input type="text" id="frm_equipier_profession" name="profession" class="form-control" value="<?php echo $frm_user_profession; ?>" required>
					</div>
					<div class="row">
					<div class="col-sm-6 form-group">
						<label>Image</label><br>
						<input type="hidden" id="frm_equipier_image" onchange="insertImage($(this), $(this).parents('.form-group').find('.images-list'), false, 'image');">
						<a class="fancybox btn btn-primary btn-sm" data-fancybox-type="iframe" href="<?php echo _ROOT_ADMIN;?>lib/filemanager/filemanager/dialog.php?type=1&amp;field_id=frm_equipier_image"><i class="glyphicon glyphicon-picture"></i> Sélectionner une image</a>
						<div class="row images-list list-sortable ui-sortable">
                            <?php
                            if(!empty($frm_user_image)) {
                                ?>
								<div class="thumb" style="background-image:url('<?php echo _ROOT._DIR_THUMBS.$frm_user_image; ?>');">
									<a href="<?php echo _ROOT._DIR_MEDIA.$frm_user_image; ?>" class="fancybox_img"></a>
									<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<input type="hidden" name="image" value="<?php echo $frm_user_image; ?>">
								</div>
                                <?php
                            }
                            ?>
						</div>
					</div>

					<div class="col-sm-6 form-group" >
						<label for="frm_annonce_isActive">Afficher l'agent sur la page ?</label>
						<div>
							<button type="button" class="btn btn-onoff btn-danger btn-sm nofocus" data-on-text="Oui" data-off-text="Non">Non</button>
							<input type="hidden" id="frm_annonce_isActive" name="isActive" value="<?php echo escHtml($frm_annonce_isActive); ?>">
						</div>
					</div>
					</div>
				</div>
			</fieldset>

		</div>


	</div>
	<hr>

	<button type="submit" name="action_<?php echo !empty($_equipe->id) ? 'modify' : 'add'; ?>" class="btn btn-lg btn-success"<?php echo !empty($_equipe->id) ? ' value="'.$_equipe->id.'"' : ''; ?>>Enregistrer</button>
</form>
