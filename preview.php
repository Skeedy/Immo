<?php
session_start();


if(empty($_GET['key']))
	exit;

//renouvellement de session
if(!empty($_POST['_ks']))
	exit;


//chargement init
require_once 'lib/init.php';


//mobile detect
require_once _DIR_LIB.'MobileDetect/Mobile_Detect.php';
$mobile_detect = new Mobile_Detect;


//cache control
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");




//paramètres requête en cours
$_query = array();
foreach($_GET as $k => $v) {
	if($k != 'q') {
		if(is_array($v)) {
			foreach($v as $vv)
				$_query[] = $k.'[]='.$vv;
		}
		else
			$_query[] = $k.'='.$v;
	}
}
$_query = !empty($_query) ? '?'.implode('&', $_query) : '';


//definition du langage
$_cookie_time = !empty($_PARAMS['cookie_duree']) && is_numeric($_PARAMS['cookie_duree']) ? $_PARAMS['cookie_duree'] : 60 * 60 * 24 * 30;
if(count($_LANGS) > 1) {

	if(!empty($_COOKIE['_lang']) && array_key_exists($_COOKIE['_lang'], $_LANGS))
		$_lang = $_COOKIE['_lang'];
	else
		$_lang = _LANG_DEFAULT;
		
	//constante de chemin langue
	define('_ROOT_LANG', _ROOT.$_lang.'/');
}
else {
	$_lang = _LANG_DEFAULT;
	define('_ROOT_LANG', _ROOT);
}

$_req = array();
	
require_once _DIR_LANGUAGE.$_lang.'.php';

setlocale (LC_TIME, $_LANGS[$_lang]['locale'].'.utf8','fra');



//
// chargement des modèles
//
//menu
$db_menu = loadModel('menu');
$db_annonce = loadModel('annonce');
$db_user = loadModel('user');
$db_page = loadModel('page');



//récupération utilisateur courant
$_wishlist = $db_user->getCurrentWishlist();




$_controller = 'page';


$_page = $db_page->getPreview($_GET['id']);

if(!$_page)
	exit;

$_data = json_decode($_page->content)->{$_GET['key']};



//classe menu
if(!empty($_data->menustyle))
	$_menu_class = $_data->menustyle;



$_view = $_controller;


//rendu
include _DIR_LAYOUT.'layout.php';

