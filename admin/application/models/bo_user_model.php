<?php
class Model_bo_user extends Model {

	public function logIn($user) {
		$bo = array(
			'id' => $user->id,
		);
		$_SESSION['_bo'] = $bo;
	}

	
	public function checkUserExists($login, $password) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			WHERE login = ?
			AND password = ?;');
		$sql->execute(array($login, $password));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}


	public function checkUserById($id) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}

	
	public function getHash($login) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			WHERE login = ?;');
		$sql->execute(array($login));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}


	public function getHashFromEmail($email) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			WHERE email = ?;');
		$sql->execute(array($email));
		if($res = $sql->fetch())
			return $res;
		else
			return false;
	}


	public function recoverPassword($id) {
		$user = $this->get($id);
		$passwd = rand_str(8);
		$sql = $this->db->prepare('UPDATE '.$this->bo_user.'
			SET password = ?,
			last_reset = ?
			WHERE id = ?;');
		$sql->execute(array(password_hash($passwd, PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $id));
		triggerHookMail('ADMIN_PASSWORD_RESET', $admin = $id, $client = null, $agence = null, $annonce = null, $vars = array('password' => $passwd));
		historique_write('ADMIN'.$id, 'Reset mot de passe admin', array('description' => 'Reset mot de passe admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id()));
	}

	
	public function get($id) {
		global $_current_user;

		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}

	
	public function getAll($sort = 'id DESC') {
		global $_current_user;

		$sql = $this->db->prepare('SELECT *
			FROM '.$this->bo_user.'
			ORDER BY '.addslashes($sort).';');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getAllUsers($sort = 'nom') {
		$sql = $this->db->prepare('SELECT id, nom, prenom
			FROM '.$this->bo_user.'
			ORDER BY '.addslashes($sort).';');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function checkPost($p) {
		global $_current_user;
		$error = array();
		if(empty($p['login']))
			$error[] = 'Le champ "Identifiant" n\'est pas renseigné.';
		else if(isset($p['action_add'])) {
			$sql = $this->db->prepare('SELECT COUNT(login) AS compt
				FROM '.$this->bo_user.'
				WHERE login = ?;');
			$sql->execute(array($p['login']));
			$res = $sql->fetch(PDO::FETCH_COLUMN);
			if($res > 0)
				$error[] = 'L\'identifiant <strong>"'.$p['login'].'"</strong> est déjà utilisé, veuillez en choisir un autre.';
		}
		else if(!empty($p['action_modify'])) {
			$u = $this->get($p['action_modify']);
			if($u->login != $p['login']) {
				$sql = $this->db->prepare('SELECT COUNT(login) AS compt
					FROM '.$this->bo_user.'
					WHERE login = ?;');
				$sql->execute(array($p['login']));
				$res = $sql->fetch(PDO::FETCH_COLUMN);
				if($res > 0)
					$error[] = 'L\'identifiant <strong>"'.$p['login'].'"</strong> est déjà utilisé, veuillez en choisir un autre.';
			}
		}
		if(empty($p['email']) || !preg_match(_REGEXP_EMAIL, $p['email']))
			$error[] = 'Le champ "Email" n\'est pas renseigné ou mal formaté (exemple@domaine.tld).';
		else if(isset($p['action_add'])) {
			$sql = $this->db->prepare('SELECT COUNT(email) AS compt
				FROM '.$this->bo_user.'
				WHERE email = ?;');
			$sql->execute(array($p['email']));
			$res = $sql->fetch(PDO::FETCH_COLUMN);
			if($res > 0)
				$error[] = 'L\'email <strong>"'.$p['email'].'"</strong> est déjà utilisé, veuillez en choisir un autre.';
		}
		else if(!empty($p['action_modify'])) {
			$u = $this->get($p['action_modify']);
			if($u->email != $p['email']) {
				$sql = $this->db->prepare('SELECT COUNT(email) AS compt
					FROM '.$this->bo_user.'
					WHERE email = ?;');
				$sql->execute(array($p['email']));
				$res = $sql->fetch(PDO::FETCH_COLUMN);
				if($res > 0)
					$error[] = 'L\'email <strong>"'.$p['email'].'"</strong> est déjà utilisé, veuillez en choisir un autre.';
			}
		}
		if(isset($p['action_add']) || !empty($p['chpasswd'])) {
			if($p['password'] != $p['password_confirm'])
				$error[] = 'Les champs "Mot de passe" et "confirmation" ne correspondent pas.';
			else if(!empty($p['password']) && strlen($p['password']) < 6)
				$error[] = 'Le champ "Mot de passe" est trop court (6 caractères minimum).';
		}
		if(empty($p['prenom']))
			$error[] = 'Le champ "Prenom" n\'est pas renseigné.';
		if(empty($p['nom']))
			$error[] = 'Le champ "Nom" n\'est pas renseigné.';
		if(($_current_user->role == 'root' || $_current_user->role == 'administrateur') && empty($p['role']))
			$error[] = 'Le champ "Rôle" n\'est pas renseigné.';
		return $error;
	}


	public function add($p) {
		global $_current_user;
		$error = $this->checkPost($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		$passwd = empty($p['password']) ? rand_str(8) : $p['password'];
		//mise en place des valeurs d'insertions
		$params = array(
			null,
			$p['login'],
			$p['email'],
			password_hash($passwd, PASSWORD_DEFAULT),
			$p['prenom'],
			$p['nom'],
			$p['role'],
			NULL
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->bo_user.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		if($sql->execute($params)) {
			$id = $this->db->lastInsertId();

			triggerHookMail('NEW_ADMIN', $admin = $id, $client = null, $agence = null, $annonce = null, $vars = empty($p['password']) ? array('password' => $passwd) : null);
			throwAlert('success', '', '<p>L\'utilisateur <strong>"'.$p['prenom'].' '.$p['nom'].'"</strong> a bien été ajouté.</p>');
			historique_write('ADMIN'.$id.'ADMIN'.$_current_user->id, 'Inscription admin', array('description' => 'Inscription admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de l\'ajout de l\'utilisateur "'.$p['prenom'].' '.$p['nom'].'".</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('ADMIN'.$_current_user->id, 'Erreur inscription admin', array('description' => 'Erreur inscription admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}


	public function modify($p) {
		global $_current_user;

		if(!$this->get($p['action_modify'])) {
			throwAlert('danger', '', '<p>Vous ne pouvez pas modifier cet utilisateur.</p>');
			return false;
		}
		$error = $this->checkPost($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		//mise en place des valeurs d'insertions
		$str = 'login = ?, ';
		$params = array($p['login']);
		if($_current_user->role == 'root') {
			$str .= 'role = ?, ';
			$params[] = $p['role'];
		}
		if(!empty($p['chpasswd'])) {
			$str .= 'password = ?, ';
			$passwd = password_hash(empty($p['password']) ? rand_str(8) : $p['password'], PASSWORD_DEFAULT);
			$params[] = $passwd;
		}
		$str .= 'email = ?, nom = ?, prenom = ?';
		array_push($params, $p['email'], $p['nom'], $p['prenom'], $p['action_modify']);
		$sql = $this->db->prepare('UPDATE '.$this->bo_user.'
			SET '.$str.'
			WHERE id = ?;');
		if($sql->execute($params)) {
			throwAlert('success', '', '<p>L\'utilisateur <strong>"'.$p['prenom'].' '.$p['nom'].'"</strong> a bien été modifié.</p>');
			historique_write('ADMIN'.$p['action_modify'].'ADMIN'.$_current_user->id, 'Modification admin', array('description' => 'Modification admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification de l\'utilisateur "'.$p['prenom'].' '.$p['nom'].'".</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('ADMIN'.$p['action_modify'].'ADMIN'.$_current_user->id, 'Erreur modification admin', array('description' => 'Erreur modification admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}
	

	public function delete($id) {
		global $_current_user;

		if($p = $this->get($id)) {
			$sql = $this->db->prepare('DELETE FROM '.$this->bo_user.'
				WHERE id = ?;');
			$sql->execute(array($id));
			throwAlert('success', '', '<p>L\'utilisateur <strong>"'.$p->prenom.' '.$p->nom.'"</strong> a bien été supprimé.</p>');
			historique_write('ADMIN'.$id.'ADMIN'.$_current_user->id, 'Suppression admin', array('description' => 'Suppression admin depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Cet utilisateur n\'existe pas.');
			return false;
		}
	}


	/*
	 * Chiffre une chaine (reversible) et retourne une chaine bas64
	 */
	public function encrypt($message) {
		$nonceSize = openssl_cipher_iv_length(_ENCRYPT_METHOD);
		$nonce = openssl_random_pseudo_bytes($nonceSize);
		$ciphertext = openssl_encrypt(
			$message,
			_ENCRYPT_METHOD,
			_ENCRYPT_KEY,
			OPENSSL_RAW_DATA,
			$nonce
		);
		return base64_encode($nonce.$ciphertext);
	}


	/*
	 * Déchiffre une chaine (reversible) et retourne une chaine
	 */
	public static function decrypt($message) {
		$message = base64_decode($message, true);
		if($message === false)
			return false;
		$nonceSize = openssl_cipher_iv_length(_ENCRYPT_METHOD);
		$nonce = mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');
		$plaintext = openssl_decrypt(
			$ciphertext,
			_ENCRYPT_METHOD,
			_ENCRYPT_KEY,
			OPENSSL_RAW_DATA,
			$nonce
		);
		return $plaintext;
	}


	public function decodeToken($token) {
		if($str = $this->decrypt($token)) {
			if($obj = json_decode($str))
				return $obj;
			else
				return false;
		}
		else
			return false;
	}



}
