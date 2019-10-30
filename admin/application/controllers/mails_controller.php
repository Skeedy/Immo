<?php
$_controller = 'mails';


if($_current_user->role != 'root' && $_current_user->role != 'administrateur') {
	$_view = 'forbidden';
	include _DIR_LAYOUT.'layout.php';
	exit;
}



$db_mail = loadModel('mail');


//
// post forms
//



//ajout d'un mail
if(isset($_POST['action_add'])) {
	$result = $db_mail->addMail($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}

//modification d'un mail
if(!empty($_POST['action_modify'])) {
	$result = $db_mail->modifyMail($_POST);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}

//suppression d'un mail
if(!empty($_POST['action_delete'])) {
	$result = $db_mail->deleteMail($_POST['action_delete']);
	if($result) {
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller);
	}
}



//vue par défaut
$_view_default = 'list';
if(empty($_GET['view']))
	redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);



$_hooks = $db_mail->getAllHooks();


//
// sélection des vues
//

//liste des mails
if(!empty($_GET['view']) && $_GET['view'] == 'list') {
	$_mails_sort = !empty($_GET['mailssortby']) ? $_GET['mailssortby'] : 'hook ASC';
	$_mails = $db_mail->getAll($_mails_sort);
	$_view = $_controller.'_list';
}

//mail
else if(!empty($_GET['view']) && $_GET['view'] == 'mail' && !empty($_GET['mail_id'])) {
	if($_mail = $db_mail->getMail($_GET['mail_id'])) {
		$_data = json_decode($_mail->data);
		$_view = $_controller.'_mail';
	}
	else {
		throwAlert('danger', 'Erreur', 'Ce mail n\'existe pas.');
		$_SESSION['_ALERTS'] = $_ALERTS;
		redirect(_ROOT_ADMIN.'?controller='.$_controller.'&view='.$_view_default);
	}
}

//ajouter
else if(!empty($_GET['view']) && $_GET['view'] == 'add') {
	$_view = $_controller.'_add';
}

//suppression mail
else if(!empty($_GET['view']) && $_GET['view'] == 'delete_mail' && !empty($_GET['mail_id'])) {
	$_mails_sort = !empty($_GET['mailssortby']) ? $_GET['mailssortby'] : 'hook ASC';
	$_mails = $db_mail->getAll($_mails_sort);
	$_view = $_controller.'_list';
	if($_mail = $db_mail->getMail($_GET['mail_id'])) {
		$_data = json_decode($_mail->data);
		throwAlert('danger', 'Attention', '<form method="post"><p>Voulez-vous supprimer le mail "'.$_data->objet.'" ?</p><p><button type="submit" class="btn btn-danger" name="action_delete" value="'.$_mail->id.'">Supprimer</button> <a class="btn btn-default" href="'.(!empty($_GET['cancel_url']) ? $_GET['cancel_url'] : _ROOT_ADMIN.'?controller='.$_controller.'&view=list').'">Annuler</a></p></form>');
	}
	else {
		throwAlert('danger', 'Erreur', 'Ce mail n\'existe pas.');
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
