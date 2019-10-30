<?php

$_controller = '404';

header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');


//attribution des metas
$_meta['title'] = 'Erreur 404';
$_meta['description'] = 'Erreur 404';



//vue
$_view = $_controller;

//affichage
include _DIR_LAYOUT.'layout.php';
