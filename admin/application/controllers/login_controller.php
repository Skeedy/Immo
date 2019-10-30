<?php
$_controller = 'login';

$db_user = loadModel('bo_user');


//si le formulaire de login est posté
if( !empty($_POST['login']) && !empty($_POST['password']) ) {
	if( $user = $db_user->getHash($_POST['login']) ) {
		if( password_verify($_POST['password'], $user->password) ) {
			$db_user->logIn($user);

			//cookie
			$cookie = array(
				'_bo_id' 	=> $user->id,
				'_bo_login' 	=> $user->login,
				'_bo_hash' => $user->password,
			);
			setcookie('_bo_token', $db_user->encrypt(json_encode($cookie)), time() + 60*60*24*7, _ROOT_ADMIN);

			// go !
			redirect(_ROOT_ADMIN);
		}
		else
			throwAlert('danger', '', 'Échec d\'authentification');
	}
	else
		throwAlert('danger', '', 'Échec d\'authentification');
}

//si le formulaire de recover est posté
if( !empty($_POST['recover_email']) ) {
	$error = false;
	if( $user = $db_user->getHashFromEmail($_POST['recover_email']) ) {
		if( !empty($user->last_reset) ) {
			$date = new DateTime($user->last_reset);
			$date->modify('+3 hours');
			if($date->format('U') > date('U')) {
				throwAlert('danger', '', 'Il faut un intervalle de 3 heures entre deux regénération de mot de passe.');
				$error = true;
				$_entites_recover = true;
			}
		}
		if( !$error ) {
			$db_user->recoverPassword($user->id);
			throwAlert('info', '', 'Votre nouveau mot de passe vous a été envoyé par email.');
		}
	}
	else {
		throwAlert('danger', '', 'Email incorrect');
		$_entites_recover = true;
	}
}


$_body_class = 'login';




//rendu
include _DIR_LAYOUT.'head.php';

include _DIR_VIEWS.$_controller.'_view.php';

include _DIR_LAYOUT.'footer.php';
