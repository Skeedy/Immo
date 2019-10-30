<?php
//dev
define('_DEBUG_MODE', false);
define('_VERSION', 1.02);


//repertoires
define('_ROOT', '/lapalus/');

define('_DIR_APP', 'application/');
define('_DIR_CONTROLLERS', _DIR_APP.'controllers/');
define('_DIR_MODELS', _DIR_APP.'models/');
define('_DIR_VIEWS', _DIR_APP.'views/');
define('_DIR_FORMS', _DIR_APP.'forms/');
define('_DIR_LAYOUT', _DIR_APP.'layout/');
define('_DIR_LANGUAGE', _DIR_APP.'language/');
define('_DIR_LIB', 'lib/');
define('_DIR_MEDIA', 'media/');
define('_DIR_IMG', 'img/');
define('_DIR_IMG_ANNONCE', _DIR_IMG.'i/');
define('_DIR_IMG_EQUIPE', _DIR_IMG.'e/');
define('_DIR_IMG_RENOVATION', _DIR_IMG.'r/');
define('_DIR_IMG_COMPARAISON', _DIR_IMG.'c/');
define('_DIR_IMG_TMP', _DIR_IMG.'t/');
define('_DIR_THUMBS', 'thumbs/');
define('_DIR_CSS', 'css/');

//images
define('_IMG_MAX_WIDTH', 1600);
define('_IMG_MAX_HEIGHT', 1600);
define('_IMG_LG_WIDTH', 1140);
define('_IMG_LG_HEIGHT', 663);
define('_IMG_MD_WIDTH', 640);
define('_IMG_MD_HEIGHT', 564);
define('_IMG_SM_WIDTH', 200);
define('_IMG_SM_HEIGHT', 176);
define('_IMG_BA_WIDTH', 760);
define('_IMG_BA_HEIGHT', 428);


// biens
define('_SITE_NB_ANNONCES', 9);
define('_SITE_NB_RENOVATIONS', 9);
define('_SITE_NB_ANNONCES_VENDUES', 4);


//url
define('_PROTOCOL', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && ! in_array(strtolower($_SERVER['HTTPS']), array( 'off', 'no' ))) ? 'https' : 'http').'://');


//exit filemanager
if(empty($_IS_FILEMANAGER)) :


//regexp
define('_REGEXP_EMAIL', '/^[^@%*<> ]+@[^@%*<> ]{2,255}\.[^@%*<> ]{2,100}$/');
define('_REGEXP_TELEPHONE', '/^[+().0-9\s]{2,100}$/');
define('_REGEXP_SIRET', '/^[-.0-9\s]{9,100}$/');


//base de données
define('_DB_SERVER', 'localhost');
define('_DB_USERNAME', 'root');
define('_DB_PASSWD', 'ubuntu');
define('_DB_NAME', 'lapalus');
define('_DB_PREFIX', 'lap_');


//meta tags
define('_TITLE', 'Lapalus Immobilier');


//mails
define('_MAIL_ACTIVE', true);
define('_DEBUG_EMAIL', 'benjamin.gouaud@thekub.com');
define('_MAIL_NOTIF', 'benjamin.gouaud@thekub.com');
define('_MAIL_POSTMASTER', 'benjamin.gouaud@gmail.com');
define('_MAIL_SENDER', 'Lapalus Immobilier');


setlocale (LC_TIME, 'fr_FR.utf8','fra');



//----------- langues ------------
$_LANGS = array(
	'fr' => array('locale' => 'fr_FR', 'label' => 'Français', 'date_format' => 'd/m/Y', 'date_format_long' => '%A %-e %B %Y'),
	//'en' => array('locale' => 'en_US', 'label' => 'English', 'date_format' => 'Y/m/d', 'date_format_long' => '%A %-e %B %Y')
);

define('_LANG_DEFAULT', array_keys($_LANGS)[0]);

function printToggleLang($block = false) {
	global $_LANGS;
	if(count($_LANGS) > 1) {
		echo '<div class="btn-group lang_selector'.(!empty($block) ? ' lang_selector_block' : '').'">';
			foreach($_LANGS as $k => $v)
				echo '<button type="button" class="btn btn-xs btn-default" data-lang="'.$k.'"><img src="'._ROOT_ADMIN._DIR_IMG.'lang/'.$k.'.png" alt="'.$k.'"> '.$k.'</button>';
		echo '</div>';
	}
}

function printLangTag($l) {
	global $_LANGS;
	if(count($_LANGS) > 1)
		echo '('.strtoupper($l).')';
}


function __str($str) {
	global $_str;
	return !empty($_str[$str]) ? $_str[$str] : $str;
}


function __lang($obj) {
	global $_lang;
	return !empty($obj->{$_lang}) ? $obj->{$_lang} : $obj->{_LANG_DEFAULT};
}


//
//------------------ classe abstraite de modèle de données --------------------
//

abstract class Model {
	protected $db;

	protected $annonce = _DB_PREFIX.'annonce';
	protected $annonce_verrou = _DB_PREFIX.'annonce_verrou';
	protected $bo_user = _DB_PREFIX.'bo_user';
	protected $departement = _DB_PREFIX.'departement';
	protected $historique = _DB_PREFIX.'historique';
	protected $hook = _DB_PREFIX.'hook';
	protected $mail = _DB_PREFIX.'mail';
	protected $equipe = _DB_PREFIX.'equipe';
	protected $renovation = _DB_PREFIX.'renovation';
	protected $menu = _DB_PREFIX.'menu';
	protected $page = _DB_PREFIX.'page';
	protected $parametre = _DB_PREFIX.'parametre';
	protected $region = _DB_PREFIX.'region';
	protected $type = _DB_PREFIX.'type';
	protected $ville = _DB_PREFIX.'ville';
	protected $avertir = _DB_PREFIX.'avertir';
	
	function __construct() {
		$this->db = new PDO('mysql:host='._DB_SERVER.';dbname='._DB_NAME, _DB_USERNAME, _DB_PASSWD);
		$this->db->exec('SET NAMES "utf8"');
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}
}





//
//----------------- variables globales -----------
//
//initialisation du stack d'alertes
$_ALERTS = '';
if(!empty($_SESSION['_ALERTS'])) {
	$_ALERTS = $_SESSION['_ALERTS'];
	unset($_SESSION['_ALERTS']);
}

//initialisation du stack d'erreurs
$_ERRORS = array();
if(!empty($_SESSION['_ERRORS'])) {
	$_ERRORS = $_SESSION['_ERRORS'];
	unset($_SESSION['_ERRORS']);
}

//initialisation du stack de success
$_SUCCESS = array();
if(!empty($_SESSION['_SUCCESS'])) {
	$_SUCCESS = $_SESSION['_SUCCESS'];
	unset($_SESSION['_SUCCESS']);
}






//
//---------------------- fonctions -----------------------
//

function generateToken($name) {
	$token = uniqid(rand(), true);
	if(empty($_SESSION['_token']) || !is_array($_SESSION['_token']))
		$_SESSION['_token'] = array();
	if(!isset($_SESSION['_token'][$name]) || !is_array($_SESSION['_token'][$name]))
		$_SESSION['_token'][$name] = array();
	$_SESSION['_token'][$name][] = $token;
	return $token;
}

function verifyToken($token, $name) {
	if(isset($_SESSION['_token']) && isset($_SESSION['_token'][$name]) && in_array($token, $_SESSION['_token'][$name])) {
		$referer = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $_SERVER['HTTP_REFERER'];
		if(strstr($referer, _PROTOCOL.$_SERVER['SERVER_NAME']) !== false)
			return true;
	}
	return false;
}



function loadModel($id) {
	require_once _DIR_MODELS.$id.'_model.php';
	$classname = 'Model_'.$id;
	return new $classname();
}

function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

$_LOAD_CSS = array();
function loadCSS($url) {
	global $_LOAD_CSS;
	if(!in_array($url, $_LOAD_CSS))
		$_LOAD_CSS[] = $url;
}

$_LOAD_JS = array();
function loadJS($url) {
	global $_LOAD_JS;
	if(!in_array($url, $_LOAD_JS))
		$_LOAD_JS[] = $url;
}

function print_loadCSS() {
	global $_LOAD_CSS;
	foreach($_LOAD_CSS as $l)
		echo '<link rel="stylesheet" href="'.$l.'">';
}

function print_loadJS() {
	global $_LOAD_JS;
	foreach($_LOAD_JS as $l)
		echo '<script src="'.$l.'"></script>';
}

function debug_var($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function escHtml($str, $fixSpecials = false) {
	$str = htmlspecialchars($str, ENT_QUOTES);
	if($fixSpecials)
		$str = fixSpecials($str);
	return $str;
}

function rand_str($length) {
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz=_?!0123456789';
	$str = '';
	for($i = 0; $i < $length; $i++)
		$str	.= $chars[mt_rand(0,(strlen($chars)-1))];
	return $str;
}

function array_map_recursive($callback, $array) {
	if(is_array($array)) {
		foreach($array as $key => $value) {
			if(is_array($array[$key]) || is_object($array[$key]))
				$array[$key] = array_map_recursive($callback, $array[$key]);
			else
				$array[$key] = call_user_func($callback, $array[$key]);
		}
	}
	else if(is_object($array)) {
		foreach($array as $key => $value) {
			if(is_array($array->$key) || is_object($array->$key))
				$array->$key = array_map_recursive($callback, $array->$key);
			else
				$array->$key = call_user_func($callback, $array->$key);
		}
	}
	return $array;
}

//pagination
function printPagination($page_max, $page, $url, $nb = 9, $sep = '') {
	if($page_max > 1) {
		echo '<ul class="pagination">';
			if($page <= 1) {
				echo '<li class="disabled"><span>&laquo;</span></li>';
				echo '<li class="disabled"><span>&lt;</span></li>';
			}
			else {
				echo '<li><a href="'.$url.'1" title="Page 1">&laquo;</a></li>';
				echo '<li><a href="'.$url.($page - 1).'" title="Page '.($page - 1).'">&lt;</a></li>';
			}
			if($page_max > $nb) {
				if($page > floor($nb / 2) && $page < $page_max - floor($nb / 2)) {
					$start = $page - floor($nb / 2);
					$end = $page + ceil($nb / 2);
				}
				else if($page <= floor($nb / 2)) {
					$start = 1;
					$end = $nb;
				}
				else {
					$start = $page_max - $nb;
					$end = $page_max;
				}
			}
			else {
				$start = 1;
				$end = $page_max;
			}
			for($i = $start; $i <= $end; $i++) {
				echo '<li'.($i == $page ? ' class="active"' : '').'><a href="'.$url.$i.'" title="Page '.$i.'">'.(($start > 1 && $i == $start) || ($end < $page_max && $i == $end) ? ($start > 1 && $i == $start ? '&hellip;'.$i : $i.'&hellip;') : $i).'</a></li>';
				if(!empty($sep) && $i < $end)
					echo '<li class="sep"><span>'.$sep.'</span></li>';
			}
			if($page >= $page_max) {
				echo '<li class="disabled"><span>&gt;</span></li>';
				echo '<li class="disabled"><span>&raquo;</span></li>';
			}
			else {
				echo '<li><a href="'.$url.($page + 1).'" title="Page '.($page + 1).'">&gt;</a></li>';
				echo '<li><a href="'.$url.$page_max.'" title="Page '.$page_max.'">&raquo;</a></li>';
			}
		echo '</ul>';
	}
}

//pagination front
function printPaginationFront($page_max, $page, $url, $query, $nb = 9) {
	if($page_max > 1) {
		echo '<ul class="pagination">';
			if($page > 1) {
				echo '<li><a class="nav" href="'.$url.($page - 1).$query.'" title="Page '.($page - 1).'">&lt; Précédent</a></li>';
			}
			if($page_max > $nb) {
				if($page > floor($nb / 2) && $page < $page_max - floor($nb / 2)) {
					$start = $page - floor($nb / 2);
					$end = $page + ceil($nb / 2);
				}
				else if($page <= floor($nb / 2)) {
					$start = 1;
					$end = $nb;
				}
				else {
					$start = $page_max - $nb;
					$end = $page_max;
				}
			}
			else {
				$start = 1;
				$end = $page_max;
			}
			if($start > 1) {
				echo '<li><a href="'.$url.'1'.$query.'" title="Page 1">1</a></li>';
				if($start > 2)
					echo '<li><span class="sep">&hellip;</span></li>';
			}
			for($i = $start; $i <= $end; $i++) {
				echo '<li><a'.($i == $page ? ' class="active"' : '').' href="'.$url.$i.$query.'" title="Page '.$i.'">'.$i.'</a></li>';
			}
			if($end < $page_max) {
				if($end < $page_max -1)
					echo '<li><span class="sep">&hellip;</span></li>';
				echo '<li><a href="'.$url.$page_max.$query.'" title="Page '.$page_max.'">'.$page_max.'</a></li>';
			}
			if($page < $page_max) {
				echo '<li><a class="nav" href="'.$url.($page + 1).$query.'" title="Page '.($page + 1).'">Suivant &gt;</a></li>';
			}
		echo '</ul>';
	}
}

//alertes
function throwAlert($class, $titre, $txt) {
	global $_ALERTS;
	$titre =  !empty($titre) ? '<big><strong>'.$titre.'</strong></big><hr>' : '';
	$_ALERTS .= '<div class="alert alert-'.$class.' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.$titre.$txt.'</div>';
}

//erreurs
function throwError($errors, $frm) {
	global $_ERRORS;
	$_ERRORS[$frm] = $errors;
}

//succes
function throwSuccess($success, $frm) {
	global $_SUCCESS;
	$_SUCCESS[$frm] = $success;
}

//redirection avec priorité à $_GET['urlreturn']
function redirect($url) {
	header('location: '.(!empty($_GET['urlreturn']) ? urldecode($_GET['urlreturn']) : $url));
	exit;
}

//sorting list
function printSortList($prefix, $label, $champ, $sort, $url, $hash) {
	if(preg_match('/^(.+) (ASC|DESC)$/', $sort, $m)) {
		$link = $url.$prefix.'sortby='.$champ.' '.($champ == $m[1] && $m[2] == 'ASC' ? 'DESC' : 'ASC').$hash;
		$up = $champ == $m[1] && $m[2] == 'ASC' ? 'active ' : '';
		$down = $champ == $m[1] && $m[2] == 'DESC' ? 'active ' : '';
		echo '<a class="sort'.($champ == $m[1] ? ' selected' : '').'" href="'.$link.'">'.$label;
			echo '<span class="sortarrows">';
				if($champ != $m[1] || ($champ == $m[1] && $m[2] == 'ASC'))
					echo '<span class="'.$up.'pictogram">&#9652;</span><br>';
				else
					echo '<span class="'.$down.'pictogram">&#9662;</span>';
			echo '</span>';
		echo '</a>';
	}
}

//encodage et décodage des chemins
function encodeDirs($str) {
	return str_replace(
		'src="'._ROOT, 'src="{{ROOT}}', str_replace(
			'href="'._ROOT, 'href="{{ROOT}}', str_replace(
				_PROTOCOL.$_SERVER['SERVER_NAME']._ROOT, '{{ROOT}}', $str
			)
		)
	);
}
function decodeDirs($str) {
	return str_replace(array('{{ROOT}}'), array(_PROTOCOL.$_SERVER['SERVER_NAME']._ROOT), $str);
}


//fonction de nétoyage de chaine
function clean_str($str) {
	return preg_replace('/-+/', '-', utf8_encode(preg_replace(array('/\s+/', '/[^-a-z0-9]/'), array('-', ''), strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))));
}

function slug($str) {
	return utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'))));
}


function fixSpecials($str) {
	global $_PARAMS;
	
	$str = str_replace(array('&nbsp;', '²'), array(' ', '<sup>2</sup>'), $str);

	//[facebook url title]
	if(preg_match_all('/\[facebook(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="fb" target="_blank" href="'.$matches[$i][1].'"><span class="icon-facebook"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[twitter url title]
	if(preg_match_all('/\[twitter(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="tw" target="_blank" href="'.$matches[$i][1].'"><span class="icon-twitter"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[youtube url title]
	if(preg_match_all('/\[youtube(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="yt" target="_blank" href="'.$matches[$i][1].'"><span class="icon-youtube"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[instagram url title]
	if(preg_match_all('/\[instagram(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="ig" target="_blank" href="'.$matches[$i][1].'"><span class="icon-instagram"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[googleplus url title]
	if(preg_match_all('/\[googleplus(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="gp" target="_blank" href="'.$matches[$i][1].'"><span class="icon-gplus"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[linkedin url title]
	if(preg_match_all('/\[linkedin(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="lk" target="_blank" href="'.$matches[$i][1].'"><span class="icon-linkedin"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[pinterest url title]
	if(preg_match_all('/\[pinterest(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="pt" target="_blank" href="'.$matches[$i][1].'"><span class="icon-pinterest"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[skype url title]
	if(preg_match_all('/\[skype(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="sk" target="_blank" href="'.$matches[$i][1].'"><span class="icon-skype"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[vimeo url title]
	if(preg_match_all('/\[vimeo(?:\s+)([^\s]+?)(?:\s*)([^\s]*?)(?:\s*)\]/s', $str, $matches, PREG_SET_ORDER)) {
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], '<a class="vm" target="_blank" href="'.$matches[$i][1].'"><span class="icon-vimeo"></span>'.(!empty($matches[$i][2]) ? ' '.$matches[$i][2] : '').'</a>', $str);
	}

	//[all_socials]
	if(preg_match_all('/\[all_socials]/s', $str, $matches, PREG_SET_ORDER)) {
		$replace = '<div class="socials">';
		if(!empty($_PARAMS['social_facebook']))
			$replace .= '<a class="fb" href="https://www.facebook.com/'.$_PARAMS['social_facebook'].'" target="_blank" title="Facebook"><span class="icon-facebook"></span></a>';
		if(!empty($_PARAMS['social_twitter']))
			$replace .= '<a class="tw" href="https://twitter.com/'.$_PARAMS['social_twitter'].'" target="_blank" title="Twitter"><span class="icon-twitter"></span></a>';
		if(!empty($_PARAMS['social_youtube']))
			$replace .= '<a class="yt" href="https://www.youtube.com/channel/'.$_PARAMS['social_youtube'].'" target="_blank" title="YouTube"><span class="icon-youtube"></span></a>';
		if(!empty($_PARAMS['social_instagram']))
			$replace .= '<a class="ig" href="https://www.instagram.com/'.$_PARAMS['social_instagram'].'" target="_blank" title="Instagram"><span class="icon-instagram"></span></a>';
		if(!empty($_PARAMS['social_google_plus']))
			$replace .= '<a class="gp" href="https://plus.google.com/'.$_PARAMS['social_google_plus'].'" target="_blank" title="Google +"><span class="icon-gplus"></span></a>';
		if(!empty($_PARAMS['social_linkedin']))
			$replace .= '<a class="lk" href="https://www.linkedin.com/in/'.$_PARAMS['social_linkedin'].'" target="_blank" title="LinkedIn"><span class="icon-linkedin"></span></a>';
		if(!empty($_PARAMS['social_pinterest']))
			$replace .= '<a class="pt" href="https://pinterest.com/'.$_PARAMS['social_pinterest'].'" target="_blank" title="Pinterest"><span class="icon-pinterest"></span></a>';
		if(!empty($_PARAMS['social_skype']))
			$replace .= '<a class="sk" href="skype:'.$_PARAMS['social_skype'].'?chat" title="Skype"><span class="icon-skype"></span></a>';
		if(!empty($_PARAMS['social_vimeo']))
			$replace .= '<a class="vm" href="https://vimeo.com/'.$_PARAMS['social_vimeo'].'" target="_blank" title="vimeo"><span class="icon-vimeo"></span></a>';
		$replace .= '</div>';
		for($i = 0; $i < count($matches); $i++)
			$str = str_replace($matches[$i][0], $replace, $str);
	}
	
	return $str;
}


function base64url_encode($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function base64url_decode($data) { 
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 


function cleanPost($p) {
	$keys = array_keys($p);    
    $tohide = preg_grep('/password/', $keys);
    foreach($tohide as $v)
		$p[$v] = '*****';
	return $p;
}


function associative2array($a) {
	if(!is_array($a))
		return $a;
	$b = array();
	$iter = true;
	foreach(array_keys($a) as $k)
		if(!preg_match('/^iteration([0-9]+)$/', $k))
			$iter = false;
	if($iter) {
		foreach($a as $k => $v)
			$b[] = associative2array($v);
		return $b;
	}
	else {
		foreach($a as $k => $v)
			$b[$k] = associative2array($v);
		return $b;
	}
}

function jsonToAssocInArray($a) {
	foreach($a as &$v) {
		if(is_array($v))
			$v = jsonToAssocInArray($v);
		else {
			$t = json_decode($v); 
			if(json_last_error() === JSON_ERROR_NONE)
				$v = $t;
		}
	}
	return $a;			
}


//
//paramètres
//
$db_parametre = loadModel('parametre');
$_PARAMS = $db_parametre->getAllAssoc();






//
//mails
//
$db_mail = loadModel('mail');
function triggerHookMail($hook, $admin = null, $client = null, $annonce = null, $vars = null) {
	global $db_mail, $_PARAMS, $_lang;
	
	$mails = $db_mail->getMailsByHook($hook);
	foreach($mails as $mail) {
		$data = json_decode($mail->data);
		$obj = $db_mail->parseMail($data->objet, $admin, $client, $annonce, $vars);
		$msg = $db_mail->parseMail($data->body, $admin, $client, $annonce, $vars);
		$to = $db_mail->getTarget($mail->target, $admin, $client, $annonce);
		if(!empty($to)) {
			send_mail($to, $obj, $msg);
			if(!empty($data->notify)) {
				$msg = '<p>Copie du mail envoyé à <a href="mailto:'.$to.'">'.$to.'</a></p><hr>'.$msg;
				foreach(explode(' ', $_PARAMS['mail_notif']) as $v)
					send_mail($v, 'Notification '.$obj, $msg);
			}
		}
	}
}

function send_mail($to, $obj, $msg) {
	if(_DEBUG_MODE && !empty(_DEBUG_EMAIL))
		$to = _DEBUG_EMAIL;
	global $_PARAMS;
	if(_MAIL_ACTIVE) {
		$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body yahoo bgcolor="#ffffff" style="min-width:100% !important; margin:0; padding:0;">
<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="25"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table align="center" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:500px;">
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="middle" width="100%" style="text-align:center;">
												<a href="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.'">
													<img src="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT._DIR_IMG.'LOGO.png" width="133" height="80" alt="'.escHtml($_PARAMS['meta_title']).'" />
												</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="25" style="border-bottom:1px solid #646464;"></td>
	</tr>
	<tr>
		<td height="25"></td>
	</tr>
	<tr>
		<td>		
			<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:500px;">
				<tr>
					<td width="15"></td>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="font-family:Helvetica, sans-serif; color:#444444; font-size:13px;">'.$msg.'</td>
							</tr>
						</table>
					</td>
					<td width="15"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="25" style="border-bottom:1px solid #646464;"></td>
	</tr>
	<tr>
		<td height="25"></td>
	</tr>
	<tr>
		<td>		
			<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:500px;">
				<tr>
					<td width="15"></td>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="center" style="font-family:Helvetica, sans-serif; color:#646464; font-size:13px; text-align:center;">LAPALUS IMMOBILIER</td>
							</tr>
						</table>
					</td>
					<td width="15"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>';
		require_once('swift/lib/swift_required.php');
		$message = Swift_Message::newInstance();
		$message->setSubject(strip_tags($obj));
		$message->setCharset('utf-8');
		$message->setFrom(array($_PARAMS['mail_postmaster'] => $_PARAMS['mail_sender']));
		$message->setTo(is_array($to) ? $to : array($to));
		if(!empty($_POST['replyto']))
			$message->setReplyTo($_POST['replyto']);
		$message->setBody($body, 'text/html');
		$message->addPart(strip_tags($body), 'text/plain');
		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		$mailer->send($message);
	}
}






//
//historique
//
$db_historique = loadModel('historique');
function historique_write($elements, $label, $data = array()) {
	global $db_historique;
	$db_historique->write($elements, $label, $data);
}
function historique_read($element) {
	global $db_historique;
	return $db_historique->read($element);
}







//fin exit filemanager
endif;
