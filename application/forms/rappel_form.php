<div class="container">
    <form method="post" class="form-ajax form-avertir form-rappel" action="<?php echo _ROOT_LANG; ?>">
        <input type="hidden" name="form" value="rappel">
        <input type="hidden" value="<?php echo __lang($_annonce->titre) ;?>" name="bien">
        <input type="hidden" value="<?php echo $_annonce->equipe_email;?>" name="equipier">
        <h3 class="text-center subtitle_biens text-uppercase"> Laissez-nous vos coordonées</h3>
        <div class="tiret"></div>
        <div class="row justify-content-center mt-4">
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="" for="frm_contact_nom">Nom*</label>
                    <input id="frm_contact_nom" type="text" name="nom" class="form-control" placeholder="" required>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="" for="frm_contact_prenom">Prénom*</label>
                    <input id="frm_contact_prenom" type="text" name="prenom" class="form-control" placeholder="" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="" for="frm_contact_telephone">Téléphone*</label>
                    <input id="frm_contact_telephone" type="number" name="telephone" class="form-control" placeholder="" required>
                </div>
                <div class="frm_zone_message text-center"></div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="" for="frm_contact_email">Email*</label>
                    <input id="frm_contact_email" type="email" name="email" class="form-control" placeholder="" required>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn submit_button nofocus">Envoyer</button>
                </div>

            </div>
        </div>
    </form>
</div>