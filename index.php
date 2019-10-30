<?php
session_start();

// cookie accept cookies
if( !empty($_GET['_rgpd_ok']) ) {
    $_cookie_time = !empty($_PARAMS['cookie_duree']) && is_numeric($_PARAMS['cookie_duree']) ? $_PARAMS['cookie_duree'] : 60 * 60 * 24 * 30;
    setcookie('_rgpd_ok', '1', time()+$_cookie_time, _ROOT);
    exit();
}


//renouvellement de session
if(!empty($_POST['_ks']))
    exit;


//chargement init
require_once 'lib/init.php';





//mobile detect
require_once _DIR_LIB.'MobileDetect/Mobile_Detect.php';
$mobile_detect = new Mobile_Detect;


require __DIR__ . '/vendor/autoload.php';


//cache control
if(_DEBUG_MODE) {
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}
else {
    header("Cache-Control: max-age=2592000");
}



//paramètres requête en cours
$_query = array();
foreach($_GET as $k => $v) {
    if($k != 'q') {
        if(is_array($v)) {
            foreach($v as $vv)
                $_query[] = $k.'[]='.$vv;
        }
        else
            $_query[] = $k.'='.$v;
    }
}
$_query = !empty($_query) ? '?'.implode('&', $_query) : '';


//definition du langage
$_cookie_time = !empty($_PARAMS['cookie_duree']) && is_numeric($_PARAMS['cookie_duree']) ? $_PARAMS['cookie_duree'] : 60 * 60 * 24 * 30;
if(count($_LANGS) > 1) {

    if(!empty($_GET['q'])) {
        $_GET['q'] = preg_replace('/\/$/', '', $_GET['q']);
        $_req = explode('/', $_GET['q']);
        $_GET['lang'] = $_req[0];
        array_shift($_req);
    }
    else
        $_req = array();
    if(!empty($_GET['lang']) && array_key_exists($_GET['lang'], $_LANGS))
        $_lang = $_GET['lang'];
    else if(!empty($_COOKIE['_lang']) && array_key_exists($_COOKIE['_lang'], $_LANGS))
        $_lang = $_COOKIE['_lang'];
    else
        $_lang = _LANG_DEFAULT;
    //redirecrion si langue non spécifiée
    if(empty($_GET['lang']) || $_GET['lang'] != $_lang)
        redirect(_ROOT.$_lang.'/'.(!empty($_GET['q']) ? $_GET['q'] : '').$_query);
    unset($_GET['lang']);

    //constante de chemin langue
    define('_ROOT_LANG', _ROOT.$_lang.'/');

    setcookie('_lang', $_lang, time()+$_cookie_time, _ROOT);
}
else {
    $_lang = _LANG_DEFAULT;
    define('_ROOT_LANG', _ROOT);

    if(!empty($_GET['q']))
        $_req = explode('/', $_GET['q']);
    else
        $_req = array();
}



require_once _DIR_LANGUAGE.$_lang.'.php';

setlocale (LC_TIME, $_LANGS[$_lang]['locale'].'.utf8','fra');


$ordre = !empty($_GET['ordre']) && isset($_GET['ordre'])? $_GET['ordre'] : '';
//
// chargement des modèles
//
//menu
$db_annonce = loadModel('annonce');
$db_renovation = loadModel('renovation');
$db_page = loadModel('page');
$db_menu = loadModel('menu');


//variables utilisées sur tout le site
$_types = $db_annonce->getAllTypes();







//
// chargement ajax
//


//
// traitement des formulaires
//
if(isAjax() && !empty($_POST['form'])) {

    $response = new stdClass();

    //contact
    if($_POST['form'] == 'trouver') {
        $error = array();
        if(empty($_POST['nom']))
            $error[] = __str("Le nom doit être renseigné");
        if(empty($_POST['prenom']))
            $error[] = __str("Le prénom doit être renseigné");
        if(empty($_POST['telephone']))
            $error[] = __str("Le téléphone doit être renseigné");
        if(empty($_POST['email']))
            $error[] = __str("L'email doit être renseigné");
        else if(!preg_match(_REGEXP_EMAIL, $_POST['email']))
            $error[] = 'Entrez un email valide (exemple@domaine.tld)';
        else {
            $response->success = 1;
            $response->response = '<div class="alert alert-success"><strong>'.__str('Merci. Nous accusons bonne réception de votre demande.').'</strong><br>'.__str("Nous prendrons contact avec vous dans les meilleurs délais.").'</div>';

            if(!empty($error)) {
                $response->success = 0;
                $response->response = '<div class="alert alert-warning">'.implode('<br>', $error).'</div>';
            }
            else {
                $response->success = 1;
                $response->response = '<div class="alert alert-success"><strong>' . __str('Merci. Nous accusons bonne réception de votre demande.') . '</strong><br>' . __str("Nous prendrons contact avec vous dans les meilleurs délais.") . '</div>';
                $db_parametre = loadModel('parametre');
                $email = $db_parametre->getContactMail();
                $obj = 'Demande de recherche de bien';
                if (!empty($_POST['quartier'])) {
                    $quartier_html = '';
                    foreach ($_POST['quartier'] as $quartier) {
                        $quartier_html .= '<li>' . $quartier . '</li>';
                    }
                }

                if (!empty($_POST['profession_acheteur'])) {
                    $i = 1;
                    $profession_html = '';
                    foreach ($_POST['profession_acheteur'] as $profession) {
                        $profession_html .= '<li> Profession acheteur n°' . $i . ' :' . $profession . '</li>';
                        $i++;
                    }
                }

				$options = new Dompdf\Options;
				$options->set('defaultFont', 'helvetica');
				$pdf = new Dompdf\Dompdf($options);

				$html = file_get_contents(__DIR__ . '/trouver_bien_ideal_pdf.html');
				$replacement = array(
					'nom' => $_POST['nom'],
					'prenom' => $_POST['prenom'],
					'telephone' => $_POST['telephone'],
					'email' => $_POST['email'],
					'type' => !empty($_POST['type']) ? $_POST['type'] : 'non renseigné',
					'type_achat' => !empty($_POST['type_achat']) ? $_POST['type_achat'] : 'non renseigné',
					'surface' => str_replace(';', ' - ', $_POST['my_range']),
					'pieces' => !empty($_POST['pieces']) ? $_POST['pieces'] : 'non renseigné',
					'chambres' => !empty($_POST['chambres']) ? $_POST['chambres'] : 'non renseigné',
					'type_immeuble' => !empty($_POST['type_immeuble']) ? $_POST['type_immeuble'] : 'non renseigné',
					'ascenseur' => !empty($_POST['ascenseur']) ? $_POST['ascenseur'] : 'non renseigné',
					'sortie_exterieure' => !empty($_POST['sortie_exterieure']) ? $_POST['sortie_exterieure'] : 'non renseigné',
					'rdc' => !empty($_POST['rdc']) ? $_POST['rdc'] : 'non renseigné',
					'commentaire' => !empty($_POST['commentaire']) ? $_POST['commentaire'] : 'non renseigné',
					'quartiers' => !empty($_POST['quartier']) ? implode(', ', (array) $_POST['quartier']) : 'non renseigné',
					'precisions' => !empty($_POST['precisions']) ? $_POST['precisions'] : 'non renseigné',
					'budget_max' => !empty($_POST['budget_max']) ? $_POST['budget_max'] . '€' : 'non renseigné',
					'honoraire_inclus' => !empty($_POST['honoraire_inclus']) ? $_POST['honoraire_inclus'] : 'non renseigné',
					'travaux_inclus' => !empty($_POST['travaux_inclus']) ? $_POST['travaux_inclus'] : 'non renseigné',
					'droits_mutation_inclus' => !empty($_POST['droits_mutation_inclus']) ? $_POST['droits_mutation_inclus'] : 'non renseigné',
					'budget_valide' => !empty($_POST['budget_valide']) ? $_POST['budget_valide'] : 'non renseigné',
					'achat_personne' => !empty($_POST['achat_personne']) ? $_POST['achat_personne'] : 'non renseigné',
					'apport' => !empty($_POST['apport']) ? $_POST['apport'] . ( $_POST['apport'] == 'Oui' ? ( $_POST['apport_montant'] ? ' ' . $_POST['apport_montant'] . '€' : '' ) : '' ) : 'non renseigné',
					'debut_recherche' => !empty($_POST['debut_recherche']) ? $_POST['debut_recherche'] : 'non renseigné',
					'debutnb_visites_recherche' => !empty($_POST['debutnb_visites_recherche']) ? $_POST['debutnb_visites_recherche'] : 'non renseigné',
					'cdi' => !empty($_POST['cdi']) ? $_POST['cdi'] : 'non renseigné',
					'nb_acheteurs' => !empty($_POST['nb_acheteurs']) ? $_POST['nb_acheteurs'] : 'non renseigné',
				);

				$html = str_replace( 
					array_map(
						function($a) {
							return '{$' . $a . '}';
						},
						array_keys($replacement)
					),
					array_values($replacement),
					$html
				);
				$pdf->loadHtml($html);
				$pdf->render();

				$canvas = $pdf->get_canvas();
				$t = new Dompdf\FontMetrics($canvas, $options);
				$font = $t->get_font("helvetica");
				$size = 8;

				$text = 'L&A - LAPALUS IMMOBILIER & PATRIMOINE';
				$w = $canvas->get_text_width($text, $font, $size);
				$canvas->page_text( (612 - $w) / 2, 740, $text, $font, $size, array(0.5, 0.5, 0.5));

				$text = '33 RUE CAPDEVILLE - 33000 BORDEAUX';
				$w = $canvas->get_text_width($text, $font, $size);
				$canvas->page_text( (612 - $w) / 2, 750, $text, $font, $size, array(0.5, 0.5, 0.5));

				$text = 'RCS DE BORDEAUX N°535 101 489';
				$w = $canvas->get_text_width($text, $font, $size);
				$canvas->page_text( (612 - $w) / 2, 760, $text, $font, $size, array(0.5, 0.5, 0.5));

				$file = new stdClass();
				$file->data = $pdf->output();
				$file->name = 'fiche_recherche_' . clean_str($_POST['prenom']) . '_' . clean_str($_POST['nom']) . '.pdf';
				$file->type = 'application/pdf';

				
                $msg = '<div>
                            <h1>Coordonées client:</h1>
                            <div>Nom: ' . $_POST['prenom'] . '</div>
                            <div>Prenom: ' . $_POST['nom'] . '</div>
                            <div>Téléphone: ' . $_POST['telephone'] . '</div>
                            <div>Email: ' . $_POST['email'] . '</div>
                            <div>Souhaite être rappelé : ' . (!empty($_POST['rappel']) ? 'Oui' : 'Non') . '</div>
                            </div>
                        <div>
                        <h1>Paramètres</h1>
                           <div>
                               <h3>Achat</h3>
                                <div>Type de bien : ' . (!empty($_POST['type'])? $_POST['type'] : 'non renseigné') . '</div>
                                <div>Type d\'achat : ' . (!empty($_POST['type_achat'])? $_POST['type_achat'] : 'non renseigné') . '</div>
                           </div>
                           <div>
                            <h3>Localisation</h3>
                            <div>Quartiers de préférence : <ul>' . (!empty($quartier_html)? $quartier_html : 'non renseigné') . '</ul></div>
                            <div>Précisions : ' . (!empty($_POST['precisions'])? $_POST['precisions'] : 'non renseigné'). '</div>
                           </div>
                           <div>
                            <h3>Sa recherche</h3>
                            <div>Depuis : ' . (!empty($_POST['debut_recherche'])? $_POST['debut_recherche'] : 'non renseigné') . '</div>
                            <div>Nombre de visites réalisées : ' . (!empty($_POST['debutnb_visites_recherche'])? $_POST['debutnb_visites_recherche'] : 'non renseigné') . '</div>
                           </div>
                           <div>
                            <h3>Caractéristiques</h3>
                            <div>Surface habitable : ' . str_replace(';', ' - ', $_POST['my_range']) . '</div>
                            <div>Nombre de pièces : ' . (!empty($_POST['pieces'])? $_POST['pieces'] : 'non renseigné') . '</div>
                            <div>Nombre de chambres : ' . (!empty($_POST['chambres'])? $_POST['chambres'] : 'non renseigné') . '</div>
                            <div>Type d\'immeuble : ' . (!empty($_POST['type_immeuble'])? $_POST['type_immeuble'] : 'non renseigné') . '</div>
                            <div>Ascenceur : ' . (!empty($_POST['ascenseur'])? $_POST['ascenseur'] : 'non renseigné') . '</div>
                            <div>Sortie extérieure : ' . (!empty($_POST['sortie_exterieure'])? $_POST['sortie_exterieure'] : 'non renseigné') . '</div>
                            <div>Rez de chaussé : ' . (!empty($_POST['rdc'])? $_POST['rdc'] : 'non renseigné') . '</div>
                            <div>Commentaire : ' . (!empty($_POST['commentaire'])? $_POST['commentaire'] : 'non renseigné') . '</div>
                           </div>
                           <div>
                            <h3>Financement</h3>
                            <div>Budget Maximum : ' . (!empty($_POST['budget_max'])? $_POST['budget_max'].' €' : 'non renseigné') . '</div>
                            <div>Montant honoraire angence inclus : ' . (!empty($_POST['honoraire_inclus'])? $_POST['honoraire_inclus'] : 'non renseigné') . '</div>
                            <div>Travaux inclus : ' . (!empty($_POST['travaux_inclus'])? $_POST['travaux_inclus'] :'non renseigné') . '</div> 
                            '.($_POST['travaux_inclus'] == 'Oui' ? '<div> Montant travaux : '.($_POST['travaux_montant']? $_POST['travaux_montant'].'</div>' : 'non renseigné </div>'): '').'
                            
                            <div>Droits de mutation : ' . $_POST['droits_mutation_inclus'] . '</div>
                            <div>Budget validé par la banque : ' . $_POST['budget_valide'] . '</div>
                            <div>Achetez en : ' . (!empty($_POST['achat_personne'])? $_POST['achat_personne'] : 'non renseigné') . '</div>
                            <div>Apport : ' . (!empty($_POST['apport'])? $_POST['apport'] :'non renseigné') . '</div> 
                            '.($_POST['apport'] == 'Oui' ? '<div> Montant apport : '.($_POST['apport_montant']? $_POST['apport_montant'].' €</div>' : 'non renseigné </div>'): '').'
                           </div>
                           <div>
                            <h3>Situation </h3>
                            <div>CDI : ' . (!empty($_POST['cdi'])? $_POST['cdi'] : 'non renseigné') . '</div>
                            <div> Nombre acheteurs : ' . (!empty($_POST['nb_acheteurs'])? $_POST['nb_acheteurs'] : 'non renseigné') . '</div>
                            <div> Professions : ' . (!empty($profession_html)? $profession_html : 'non renseigné') . '</div>
                            </div>
                            </div>';
                send_mail($email, $obj, $msg, $file);
                unset($_POST);
            }
        }

    }
    else if($_POST['form'] == 'rappel') {
        $error = array();
        if(empty($_POST['nom']))
            $error[] = __str("Le nom doit être renseigné");
        if(empty($_POST['prenom']))
            $error[] = __str("Le prénom doit être renseigné");
        if(empty($_POST['telephone']))
            $error[] = __str("Le téléphone doit être renseigné");
        if(empty($_POST['email']))
            $error[] = __str("L'email doit être renseigné");
        else if(!preg_match(_REGEXP_EMAIL, $_POST['email']))
            $error[] = 'Entrez un email valide (exemple@domaine.tld)';

        if(!empty($error)) {
            $response->success = 0;
            $response->response = '<div class="alert alert-warning">'.implode('<br>', $error).'</div>';
        }
        else {
            $response->success = 1;
            $response->response = '<div class="alert alert-success"><strong>'.__str('Merci. Nous accusons bonne réception de votre demande.').'</strong><br>'.__str("Nous prendrons contact avec vous dans les meilleurs délais.").'</div>';
            $obj = $_POST['nom'] . ' ' . $_POST['prenom'] . ' cherche à vous contacter';
            $msg = '<div>' . $_POST['nom'] . ' ' . $_POST['prenom'] . ' demande des renseignements pour le bien "' . $_POST['bien'].'"</div><div>Email: '.$_POST['email'] .'</div><div>Téléphone: '.$_POST['telephone'].'</div>';
            send_mail($_POST['equipier'], $obj, $msg);
            unset($_POST);
        }
    }

    else if($_POST['form'] == 'contact') {
        $error = array();
        if(empty($_POST['nom']))
            $error[] = __str("Le nom doit être renseigné");
        if(empty($_POST['prenom']))
            $error[] = __str("Le prénom doit être renseigné");
        if(empty($_POST['telephone']))
            $error[] = __str("Le téléphone doit être renseigné");
        if(empty($_POST['email']))
            $error[] = __str("L'email doit être renseigné");
        else if(!preg_match(_REGEXP_EMAIL, $_POST['email']))
            $error[] = 'Entrez un email valide (exemple@domaine.tld)';
        if(empty($_POST['data']['message']))
            $error[] = __str("Le message doit être renseigné");

        if(!empty($error)) {
            $response->success = 0;
            $response->response = '<div class="alert alert-warning">'.implode('<br>', $error).'</div>';
        }
        else {
            $response->success = 1;
            $response->response = '<div class="alert alert-success"><strong>'.__str('Merci. Nous accusons bonne réception de votre demande.').'</strong><br>'.__str("Nous prendrons contact avec vous dans les meilleurs délais.").'</div>';
            triggerHookMail('CONTACT', $admin = null, $client = null, $annonce = null, $vars = array("prenom" => $_POST['prenom'], "nom" => $_POST['nom'], "replyto" => $_POST['email'], "message" => nl2br($_POST['data']['message']), "telephone" => $_POST['telephone']));
            unset($_POST);
        }
    }
    else if($_POST['form'] == 'avertir') {
        $error = array();
        if (empty($_POST['bien']) || !is_numeric($_POST['bien']))
            $error[] = __str("Id annonce invalide");
        if (empty($_POST['email']) || !preg_match(_REGEXP_EMAIL, $_POST['email']))
            $error[] = 'Entrez un email valide (exemple@domaine.tld)';
        if (!empty($error)) {
            $response->success = 0;
            $response->response = '<div class="alert alert-warning">' . implode('<br>', $error) . '</div>';
        } else {
            $db_alerte = loadModel('alerte');
            $test = $db_alerte->addAlerte($_POST);
            if($test) {
                $response->success = 1;
                $response->response = '<div class="alert alert-success"><strong>' . __str('Merci. Votre alerte a été enregistrée.') . '</strong></div>';
                unset($_POST);
            }
            else{
                $response->success = 0;
                $response->response = '<div class="alert alert-warning"> Vous avez déjà une alerte pour ce bien </div>';
            }
        }
    }

    echo json_encode($response);
    exit;
}



//controller bien
if( !empty($_req) && preg_match('/^\d+(.*)/', $_req[0], $matches) ) {
    $_id = $matches[0];
    include _DIR_CONTROLLERS.'annonce_controller.php';

}

//controller renovation
else if( !empty($_req) && preg_match('/^r(\d+)(.*)/', $_req[0], $matches) ) {
    $_id = $matches[1];
    include _DIR_CONTROLLERS.'renovation_controller.php';

}

//controller page
else {
    include _DIR_CONTROLLERS.'page_controller.php';
}
