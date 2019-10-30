<?php
$_controller = 'logs';

$db_historique = loadModel('historique');


//
// post forms
//






//
// sélection des vues
//
//variables de recherche
if(!empty($_GET['debut']) && (date_create_from_format('d/m/Y H:i:s', $_GET['debut']) || date_create_from_format('d/m/Y H:i', $_GET['debut']) || date_create_from_format('d/m/Y', $_GET['debut']))) {
	if(($debut = date_create_from_format('d/m/Y H:i:s', $_GET['debut'])) == false)
		if(($debut = date_create_from_format('d/m/Y H:i', $_GET['debut'])) == false)
			$debut = date_create_from_format('d/m/Y', $_GET['debut']);
	$debut_str = $debut->format('d/m/Y h:i:s');
}
else {
	$debut = '';
	$debut_str = '';
}
	
if(!empty($_GET['fin']) && (date_create_from_format('d/m/Y H:i:s', $_GET['fin']) || date_create_from_format('d/m/Y H:i', $_GET['fin']) || date_create_from_format('d/m/Y', $_GET['fin']))) {
	if(($fin = date_create_from_format('d/m/Y H:i:s', $_GET['fin'])) == false)
		if(($fin = date_create_from_format('d/m/Y H:i', $_GET['fin'])) == false)
			$fin = date_create_from_format('d/m/Y', $_GET['fin']);
	$fin_str = $fin->format('d/m/Y h:i:s');
}
else {
	$fin = '';
	$fin_str = '';
}
	
$elements = !empty($_GET['elements']) ? $_GET['elements'] : '';


if(!empty($elements) || (!empty($debut) && !empty($fin))) {
	$_logs = $db_historique->search($elements, $debut, $fin);
	$search = true;
}
else
	$_logs = $db_historique->getLatest();


//
// rendu
//

$_view = $_controller;
include _DIR_LAYOUT.'layout.php';
