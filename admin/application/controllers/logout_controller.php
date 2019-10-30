<?php
//destruction totale de la session et des données de session
$_SESSION = array();

if(ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

session_destroy();

//destruction des cookies
setcookie('_bo_token', '', time() - 42000, _ROOT_ADMIN);

//redirection vers login
redirect(_ROOT_ADMIN);
