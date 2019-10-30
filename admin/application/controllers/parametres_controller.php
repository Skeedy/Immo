<?php
$_controller = 'parametres';

if($_current_user->role != 'administrateur' && $_current_user->role != 'root') {
	$_view = 'forbidden';
	include _DIR_LAYOUT.'layout.php';
	exit;
}

$db_parametre = loadModel('parametre');
$_parametres = $db_parametre->getAll();

//
// post forms
//

//modification
if(isset($_POST['action_modify'])) {
	$result = $db_parametre->modify($_POST);
	if($result) {
		//$db_parametre->makeCustomCss();
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}

//
// sélection des vues
//

//liste des utilisateurs




//
// rendu
//

$_view = $_controller;
include _DIR_LAYOUT.'layout.php';
