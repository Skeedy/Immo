<?php
session_start();

//chargement du fichier de configuration
require_once dirname(dirname(__FILE__)).'/lib/init.php';
require_once dirname(__FILE__).'/lib/init.php';

$_lang = _LANG_DEFAULT;


//chargement du modele bo_user
$db_user = loadModel('bo_user');


//verification de l'utilisateur
$_current_user = false;
if( empty($_GET['controller']) || $_GET['controller'] != 'login' ) {
	$urlreturn = empty($_GET['urlreturn']) ? urlencode($_SERVER['REQUEST_URI']) : $_GET['urlreturn'];
	//login par session
	if( !empty($_SESSION['_bo']) && !empty($_SESSION['_bo']['id']) ) {
		$_current_user = $db_user->checkUserById($_SESSION['_bo']['id']);
	}
	//login par cookie
	else if( !empty($_COOKIE['_bo_token']) && $token = $db_user->decodeToken($_COOKIE['_bo_token']) ) {
		if( $user = $db_user->getHash($token->_bo_login) ) {
			if( $token->_bo_hash == $user->password ) {
				$_current_user = $user;
			}
		}
	}
	if( !empty($_current_user) ) {
		$db_user->logIn($_current_user);
	}
	else
		redirect(_ROOT_ADMIN.'?controller=login&urlreturn='.$urlreturn);
}


//menu principal
$_MENU = array();
if( !empty($_current_user) && $_current_user->role == 'root' ) {
	$_MENU['biens'] = array('url' => 'biens', 'title' => 'Biens', 'icon' => '&#9871;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Biens à vendre / louer'),
		'vendus' => array('url' => 'vendus', 'title' => 'Biens vendus'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un bien')
	));
	$_MENU['renovation']= array('url' => 'renovation', 'title' => 'Rénovations', 'icon' => '&#9871;', 'sousmenu' => array(
        'list' => array('url' => 'list', 'title' => 'Toutes les rénovations'),
        'add' => array('url' => 'add', 'title' => 'Ajouter une rénovation')
    ));
	$_MENU['menu'] = array('url' => 'menu', 'title' => 'Menu', 'icon' => '&#9776;');
//	$_MENU['donnees'] = array('url' => 'donnees', 'title' => 'Critères', 'icon' => '&#59148;');
	$_MENU['pages'] = array('url' => 'pages', 'title' => 'Pages', 'icon' => '&#128196;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Toutes les pages'),
		'add' => array('url' => 'add', 'title' => 'Ajouter une page')
	));
	$_MENU['mails'] = array('url' => 'mails', 'title' => 'Emails', 'icon' => '&#9993;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Tous les emails'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un email')
	));
	$_MENU['equipe'] = array('url' => 'equipe', 'title' => 'Équipe', 'icon' => '&#128101;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Tous les équipiers'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un équipier')
	));
    $_MENU['utilisateurs'] = array('url' => 'utilisateurs', 'title' => 'Utilisateurs', 'icon' => '&#128101;', 'sousmenu' => array(
        'list' => array('url' => 'list', 'title' => 'Tous les utilisateurs'),
        'add' => array('url' => 'add', 'title' => 'Ajouter un utilisateur')
    ));
	$_MENU['parametres'] = array('url' => 'parametres', 'title' => 'Paramètres', 'icon' => '&#9881;');
	$_MENU['logs'] = array('url' => 'logs', 'title' => 'Logs', 'icon' => '&#128214;');

}

if( !empty($_current_user) && $_current_user->role == 'administrateur' ) {
	$_MENU['biens'] = array('url' => 'biens', 'title' => 'Biens', 'icon' => '&#9871;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Biens à vendre / louer'),
		'vendus' => array('url' => 'vendus', 'title' => 'Biens vendus'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un bien')
	));
    $_MENU['renovation']= array('url' => 'renovation', 'title' => 'Rénovations', 'icon' => '&#9871;', 'sousmenu' => array(
        'list' => array('url' => 'list', 'title' => 'Toutes les rénovations'),
        'add' => array('url' => 'add', 'title' => 'Ajouter une rénovation')
    ));
	$_MENU['menu'] = array('url' => 'menu', 'title' => 'Menu', 'icon' => '&#9776;');
//	$_MENU['donnees'] = array('url' => 'donnees', 'title' => 'Critères', 'icon' => '&#59148;');
	$_MENU['pages'] = array('url' => 'pages', 'title' => 'Pages', 'icon' => '&#128196;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Toutes les pages'),
		'add' => array('url' => 'add', 'title' => 'Ajouter une page')
	));
	$_MENU['mails'] = array('url' => 'mails', 'title' => 'Emails', 'icon' => '&#9993;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Tous les emails'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un email')
	));
    $_MENU['equipe'] = array('url' => 'equipe', 'title' => 'Équipe', 'icon' => '&#128101;', 'sousmenu' => array(
        'list' => array('url' => 'list', 'title' => 'Tous les équipiers'),
        'add' => array('url' => 'add', 'title' => 'Ajouter un équipier')
    ));
	$_MENU['utilisateurs'] = array('url' => 'utilisateurs', 'title' => 'Utilisateurs', 'icon' => '&#128101;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Tous les utilisateurs'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un utilisateur')
	));
	$_MENU['parametres'] = array('url' => 'parametres', 'title' => 'Paramètres', 'icon' => '&#9881;');
	$_MENU['logs'] = array('url' => 'logs', 'title' => 'Logs', 'icon' => '&#128214;');
}

if( !empty($_current_user) && $_current_user->role == 'negociateur' ) {
	$_MENU['biens'] = array('url' => 'biens', 'title' => 'Biens', 'icon' => '&#9871;', 'sousmenu' => array(
		'list' => array('url' => 'list', 'title' => 'Biens à vendre / louer'),
		'vendus' => array('url' => 'vendus', 'title' => 'Biens vendus'),
		'add' => array('url' => 'add', 'title' => 'Ajouter un bien')
	));
	$_MENU['utilisateurs'] = array('url' => 'utilisateurs', 'title' => 'Mon compte', 'icon' => '&#128100;');
}
$_MENU['logout'] = array('url' => 'logout', 'title' => 'Déconnexion', 'icon' => '&#10006;');


//sélection du controller
if( !empty($_GET['controller']) ) {
	if( !file_exists(_DIR_CONTROLLERS.$_GET['controller'].'_controller.php') ) {
		header('location: '._ROOT_ADMIN);
		exit;
	}
}
else
	$_GET['controller'] = array_keys($_MENU)[0];


include _DIR_CONTROLLERS.$_GET['controller'].'_controller.php';
