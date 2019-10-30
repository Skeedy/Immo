<?php
$_controller = 'renovation';
loadModel('renovation');
$_annonce = $db_renovation->getRenovation($_id);
$_page_mention = $db_page->getPageFromTemplate('mention_legale');

if(!$_annonce) {
    include _DIR_CONTROLLERS.'404_controller.php';
    exit;
}

$_body_class = 'single-annonce';
$_force_menu = 'renovation';


//décodage
$_data = json_decode($_annonce->data);
$_annonce->images = json_decode($_annonce->images);
$_annonce->comparaisons = json_decode($_annonce->comparaisons);

$_page_contact = $db_page->getPageFromTemplate('contact');


//og_fields
if(!empty($_annonce->images))
    $_og_image = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$db_renovation->getRenovationDirImg($_annonce->id).'md_'
        .$_annonce->images[0]->image;
$t = __lang($_data->description);
if(!empty($t))
    $_og_description = str_replace('<br>', ' ', nl2br(escHtml($t, true), false));


//attribution des metas
$_meta['title'] = __lang($_data->meta_titre);
$_meta['description'] = __lang($_data->meta_description);
$_meta['keywords'] = __lang($_data->meta_keywords);




$_view = $_controller;




//rendu
include _DIR_LAYOUT.'layout.php';
