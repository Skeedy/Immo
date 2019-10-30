<?php
$_controller = 'annonce';
loadModel('annonce');
$_annonce= $db_annonce->getAnnonce($_id);
$_page_mention = $db_page->getPageFromTemplate('mention_legale');

if(!$_annonce) {
	include _DIR_CONTROLLERS.'404_controller.php';
	exit;
}

$_body_class = 'single-annonce';

// force menu
if (empty($_annonce->date_vente)) {
    $_force_menu = !empty($_annonce->isLocation) ? 'louer' : 'acheter';
    $_page = $db_page->getPageFromTemplate($_force_menu);
}

if (!empty ($_GET['s'])){
    $db_annonce->searchAnnonces($_GET['s'], 1);
    include_once _DIR_VIEWS.'search_view.php';
}


$_page_contact = $db_page->getPageFromTemplate('contact');

//décodage
$_data = json_decode($_annonce->data);
$_annonce->titre = json_decode($_annonce->titre);
$_annonce->images = json_decode($_annonce->images);
$_annonce->type_label = json_decode($_annonce->type_label);


//og_fields
if(!empty($_annonce->images))
	$_og_image = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$db_annonce->getAnnonceDirImg($_annonce->id).'md_'.$_annonce->images[0]->image;
$t = __lang($_data->description);
if(!empty($t))
	$_og_description = str_replace('<br>', ' ', nl2br(escHtml($t, true), false));


//attribution des metas
$_meta['title'] = __lang($_data->meta_titre);
$_meta['description'] = __lang($_data->meta_description);
$_meta['keywords'] = __lang($_data->meta_keywords);


$_view = $_controller;


if( isAjax() && isset($_GET['avertir']) ) {
    echo '<div id="avertir">';
		include _DIR_FORMS . 'avertir_form.php';
	echo '</div>';
	exit();
}




//rendu
include _DIR_LAYOUT.'layout.php';
