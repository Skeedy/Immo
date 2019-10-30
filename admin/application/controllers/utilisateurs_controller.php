<?php
$_controller = 'utilisateurs';

$db_user = loadModel('bo_user');

//
// non root
//
if($_current_user->role != 'root' && $_current_user->role != 'administrateur') {
	if(!empty($_POST['action_modify']) && $_POST['action_modify'] == $_current_user->id) {
		$result = $db_user->modify($_POST);
		if($result) {
			$_SESSION['_ALERTS'] = $_ALERTS;
			redirect(_ROOT_ADMIN.'?controller='.$_controller);
		}
	}
	$_utilisateur = $db_user->get($_current_user->id);
	$_agences = $db_agence->getAllAgences();
	$_view = $_controller.'_utilisateur';
	include _DIR_LAYOUT.'layout.php';
	exit;
}


//vue par défaut
$_view_default = 'list';
if(empty($_GET['view']))
	redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);


//
// post forms
//

//ajout d'un utilisateur
if(isset($_POST['action_add'])) {
	$result = $db_user->add($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}

//modification d'un utilisateur
if(!empty($_POST['action_modify'])) {
	$result = $db_user->modify($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect($_SERVER['REQUEST_URI']);
	}
}

//suppression d'un utilisateur
if(!empty($_POST['action_delete'])) {
	$result = $db_user->delete($_POST['action_delete']);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}


//
// sélection des vues
//

//liste des utilisateurs
if(!empty($_GET['view']) && $_GET['view'] == 'list') {
	$_utilisateurs_sort = !empty($_GET['utilisateurssortby']) ? $_GET['utilisateurssortby'] : 'nom ASC';
	$_utilisateurs = $_utilisateurs = $db_user->getAll($_utilisateurs_sort);
	$_view = $_controller.'_list';
}

//utilisateur
else if(!empty($_GET['view']) && $_GET['view'] == 'utilisateur' && !empty($_GET['id'])) {
	if($_utilisateur = $db_user->get($_GET['id'])) {
		$_view = $_controller.'_utilisateur';
	}
	else {
		throwAlert('danger', 'Erreur', 'Cet utilisateur n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}

//ajouter
if(!empty($_GET['view']) && $_GET['view'] == 'add') {
	$_view = $_controller.'_add';
}

//suppression utilisateur
else if(!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {
	if($_utilisateur = $db_user->get($_GET['id'])) {
		throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer l\'utilisateur "'.$_utilisateur->prenom.' '.$_utilisateur->nom.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_utilisateur->id.'">Supprimer</button> <a class="btn btn-default" href="'._ROOT_ADMIN.'?controller='.$_controller.'">Annuler</a></p></form>');
	}
	else {
		throwAlert('danger', 'Erreur', 'Cet utilisateur n\'existe pas.');
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
