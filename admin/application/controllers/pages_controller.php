<?php
$_controller = 'pages';

if($_current_user->role != 'root' && $_current_user->role != 'administrateur') {
	$_view = 'forbidden';
	include _DIR_LAYOUT.'layout.php';
	exit;
}


$db_page = loadModel('page');
$db_annonce = loadModel('annonce');



//searchannonces
if(isset($_GET['searchannonces']) && isAjax()) {
	echo json_encode($db_annonce->searchAnnoncesPage($_GET['searchannonces']));
	exit;
}


//
// post forms
//

//switchpage
if(!empty($_POST['switch']) && !empty($_POST['id'])) {
	$result = $db_page->switchPage($_POST['id']);
	if($result)
		echo json_encode(array('result' => true, 'active' => $result['active']));
	else
		echo json_encode(null);
	exit;
}

//ajout d'une page
if(isset($_POST['action_add'])) {
	$_POST = associative2array($_POST);
	$result = $db_page->addPage($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect($_SERVER['REQUEST_URI']);
	}
	else {
		$_tab_active = 'add';
	}
}

//modification d'une page
if(!empty($_POST['action_modify'])) {
	$_POST = associative2array($_POST);
	$result = $db_page->modifyPage($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect($_SERVER['REQUEST_URI']);
	}
	else {
		$_tab_active = 'page';
	}
}

//suppression d'une page
if(!empty($_POST['action_delete'])) {
	$result = $db_page->deletePage($_POST['action_delete']);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}


//vue par défaut
$_view_default = 'list';
if(empty($_GET['view']))
	redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// sélection des vues
//


//liste
if(!empty($_GET['view']) && $_GET['view'] == 'list') {
	$_sort = !empty($_GET['sort']) ? $_GET['sort'] : 'id ASC';
	$_items = $db_page->getAllPages($_sort);
	$_view = $_controller.'_list';
}

//modification
else if(!empty($_GET['view']) && $_GET['view'] == 'id' && !empty($_GET['id'])) {
	if($_item = $db_page->getPage($_GET['id'])) {
		$_item->titre = json_decode($_item->titre);
		$_data = json_decode($_item->data);
		$_view = $_controller.'_modify';
	}
	else {
		throwAlert('danger', 'Erreur', 'Cette page n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}

//ajouter
else if(!empty($_GET['view']) && $_GET['view'] == 'add') {
	$_view = $_controller.'_add';
}

//suppression
if(!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {
	if($_item = $db_page->getPage($_GET['id'])) {
		$_item->titre = json_decode($_item->titre);
		throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer la page "'.$_item->titre->{_LANG_DEFAULT}.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_item->id.'">Supprimer</button> <a class="btn btn-default" href="'.(!empty($_GET['cancel_url']) ? $_GET['cancel_url'] : _ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default).'">Annuler</a></p></form>');
	}
	else {
		throwAlert('danger', 'Erreur', 'Cette page n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}


if(empty($_view))
	redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// rendu
//

include _DIR_LAYOUT.'layout.php';
