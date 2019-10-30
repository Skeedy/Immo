<div class="container">
    <form method="post" class="form-ajax form-avertir form-rappel" action="<?php echo _ROOT_LANG; ?>">
        <input type="hidden" value="<?php echo $_annonce->id;?>" name="bien">
        <input type="hidden" value="avertir" name="form">
        <h3 class="text-center subtitle_biens text-uppercase"> M'avertir de son retour par email</h3>
        <div class="tiret"></div>
        <div class="row justify-content-center mt-4">
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="" for="frm_contact_nom">Nom*</label>
                    <input id="frm_contact_nom" type="text" name="nom" class="form-control" placeholder="" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
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
                    <label class="" for="frm_contact_email">Email*</label>
                    <input id="frm_contact_email" type="email" name="email" class="form-control" placeholder="" required>
                    <button type="submit" class="btn nofocus btn-arrow"><span class="arrow"></span> </button>
                </div>
            </div>
            <div class="frm_zone_message text-center"></div>
        </div>

    </form>
