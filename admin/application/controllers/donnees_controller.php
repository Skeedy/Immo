<?php
$_controller = 'donnees';

if($_current_user->role != 'administrateur' && $_current_user->role != 'root') {
	$_view = 'forbidden';
	include _DIR_LAYOUT.'layout.php';
	exit;
}


$_tabs = array('types');

$db_annonce = loadModel('annonce');



//
// post forms
//

//modification types
if(isset($_POST['action_types_modify'])) {
	$result = $db_annonce->modifyTypes($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view=types');
	}
}

//modification pieces
if(isset($_POST['action_pieces_modify'])) {
	$result = $db_annonce->modifyPieces($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view=pieces');
	}
}




//
// sélection des vues
//

//liste des types
$_types = $db_annonce->getAllTypes();





//types
if(!empty($_GET['view']) && $_GET['view'] == 'types') {
	$_tab_active = 'types';
}



//
// rendu
//

$_view = $_controller;
include _DIR_LAYOUT.'layout.php';
