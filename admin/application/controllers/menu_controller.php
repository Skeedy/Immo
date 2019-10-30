<?php
$_controller = 'menu';

if($_current_user->role != 'root' && $_current_user->role != 'administrateur') {
	$_view = 'forbidden';
	include _DIR_LAYOUT.'layout.php';
	exit;
}

$db_menu = loadModel('menu');
$db_page = loadModel('page');


//
// post forms
//

//modification
if(isset($_POST['action_modify'])) {
	$_POST = associative2array($_POST);
	$result = $db_menu->modifyMenu($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}

//
// sélection des vues
//

$_menus = $db_menu->getAll();


$_pages = $db_page->getAllPages('titre ASC');




//
// rendu
//

$_view = $_controller;
include _DIR_LAYOUT.'layout.php';
