<?php
class Model_mail extends Model {
	
	public function getMailsByHook($hook) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->mail.'
			WHERE hook = ?;');
		$sql->execute(array($hook));
		return $sql->fetchAll();
	}
	
	
	public function parseConditions($text, $var, $prefix = null) {
		//conditions valeur
		if(!empty($var) && preg_match_all('/\{\{(if '.(!empty($prefix) ? $prefix.'\.' : '').'(.+?)=(.+?))\}\}(.+?)\{\{\/if '.(!empty($prefix) ? $prefix.'\.' : '').'\2\}\}/s', $text, $matches, PREG_SET_ORDER)) {
			$var = json_decode(json_encode($var));
			for($i = 0; $i < count($matches); $i++)
				$text = str_replace($matches[$i][0], !empty($var->{$matches[$i][2]}) && $var->{$matches[$i][2]} == $matches[$i][3] ? $matches[$i][4] : '', $text);
		}
		//conditions simples
		if(!empty($var) && preg_match_all('/\{\{(if '.(!empty($prefix) ? $prefix.'\.' : '').'(.+?))\}\}(.+?)\{\{\/if '.(!empty($prefix) ? $prefix.'\.' : '').'\2\}\}/s', $text, $matches, PREG_SET_ORDER)) {
			$var = json_decode(json_encode($var));
			for($i = 0; $i < count($matches); $i++)
				$text = str_replace($matches[$i][0], !empty($var->{$matches[$i][2]}) ? $matches[$i][3] : '', $text);
		}
		return $text;
	}
	
	
	public function parseMail($text, $admin = null, $client = null, $annonce = null, $vars = null) {
		//get varriables
		if(!empty($admin)) {
			$sql = $this->db->prepare('SELECT *
				FROM '.$this->bo_user.'
				WHERE id = ?;');
			$sql->execute(array($admin));
			$admin = $sql->fetch();
		}

		if(!empty($client)) {
			$sql = $this->db->prepare('SELECT '.$this->user.'.*, '.$this->ville.'.nom_reel, '.$this->ville.'.cp
				FROM '.$this->user.', '.$this->ville.'
				WHERE '.$this->user.'.ville = '.$this->ville.'.id
				AND '.$this->user.'.id = ?;');
			$sql->execute(array($client));
			$client = $sql->fetch();
			$client->date = date_create($client->date)->format('d/m/Y à H:i:s');
		}

		if(!empty($annonce)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel
				FROM '.$this->annonce.', '.$this->ville.'
				WHERE '.$this->annonce.'.ville = '.$this->ville.'.id
				AND '.$this->annonce.'.id = ?;');
			$sql->execute(array($annonce));
			$annonce = $sql->fetch();
			$annonce->date = date_create($annonce->date)->format('d/m/Y à H:i:s');
			$titre = json_decode($annonce->titre);
			$annonce->titre = __lang($titre);
			$annonce->url = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$annonce->id.'-'.clean_str(__lang($titre));
			$images = json_decode($annonce->images);
			if(!empty($images))
				$annonce->image = '<p><img src="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT._DIR_IMG_ANNONCE.preg_replace('/(\d{1})/', '$1/', $annonce->id).$images[0]->image.'" style="max-width:100%;" width="500"></p>';
			else
				$annonce->image = '';
		}
		
		//parse admin
		$text = $this->parseConditions($text, $admin, 'admin');
		if(preg_match_all('/\{\{admin\.(.+?)\}\}/s', $text, $matches, PREG_SET_ORDER) && !empty($admin)) {
			for($i = 0; $i < count($matches); $i++)
				$text = str_replace($matches[$i][0], !empty($admin->{$matches[$i][1]}) ? $admin->{$matches[$i][1]} : '', $text);
		}

		//parse client
		$text = $this->parseConditions($text, $client, 'client');
		if(preg_match_all('/\{\{client\.(.+?)\}\}/s', $text, $matches, PREG_SET_ORDER) && !empty($client)) {
			for($i = 0; $i < count($matches); $i++)
				$text = str_replace($matches[$i][0], !empty($client->{$matches[$i][1]}) ? $client->{$matches[$i][1]} : '', $text);
		}

		//parse annonce
		$text = $this->parseConditions($text, $annonce, 'annonce');
		if(preg_match_all('/\{\{annonce\.(.+?)\}\}/s', $text, $matches, PREG_SET_ORDER) && !empty($annonce)) {
			for($i = 0; $i < count($matches); $i++)
				$text = str_replace($matches[$i][0], !empty($annonce->{$matches[$i][1]}) ? $annonce->{$matches[$i][1]} : '', $text);
		}
		
		//variables spéciales
		$text = $this->parseConditions($text, $vars);
		if(!empty($vars)) {
			foreach($vars as $k => $v)
				$text = str_replace('{{'.$k.'}}', $v, $text);
		}

		//fix url annonce avec un slash en trop au début du href
		$text = str_replace('"/http', '"http', $text);
		
		//netoyage du texte et retour
		return preg_replace('/\{\{(.+?)\}\}/s', '', $text);		
	}

	public function getTarget($target, $admin, $client, $annonce) {
		global $_PARAMS;
		if($target == 'client') {
			if(!empty($_POST['email']))
				return $_POST['email'];
			else {
				$sql = $this->db->prepare('SELECT email
					FROM '.$this->user.'
					WHERE id = ?;');
				$sql->execute(array($client));
				return escHtml($sql->fetchColumn());
			}
		}
		else if($target == 'annonce' && !empty($annonce)) {
			$sql = $this->db->prepare('SELECT email
				FROM '.$this->annonce.', '.$this->user.'
				WHERE '.$this->annonce.'.user = '.$this->user.'.id
				AND '.$this->annonce.'.id = ?;');
			$sql->execute(array($annonce));
			return $sql->fetchColumn();
		}
		else if($target == 'admin' && !empty($admin)) {
			$sql = $this->db->prepare('SELECT email
				FROM '.$this->bo_user.'
				WHERE id = ?;');
			$sql->execute(array($admin));
			return $sql->fetchColumn();
		}
		else if($target == 'contact') {
			if(!empty($agence)) {
				$sql = $this->db->prepare('SELECT data
					FROM '.$this->agence.'
					WHERE id = ?;');
				$sql->execute(array($agence));
				$data = json_decode($sql->fetchColumn());
				if(!empty($data->email))
					return $data->email;
				else
					return $_PARAMS['mail_contact'];
			}
			else
				return $_PARAMS['mail_contact'];
		}
	}
	
	
	
	/*
	 * 	admin
	 */
	 
	public function getAll($sort = 'id DESC') {
		$sql = $this->db->prepare('SELECT '.$this->mail.'.*, '.$this->hook.'.label
			FROM '.$this->mail.', '.$this->hook.'
			WHERE '.$this->mail.'.hook = '.$this->hook.'.id
			ORDER BY '.addslashes($sort).';');
		$sql->execute();
		return $sql->fetchAll();
	}
	
	
	public function getAllHooks() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->hook.'
			ORDER BY label;');
		$sql->execute();
		return $sql->fetchAll();
	}
	
	
	public function getMail($id) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->mail.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch())
			return $res;
		else
			return false;
	}
	
	
	public function addMail($p) {
		$params = array(
			null,
			$p['hook'],
			$p['target'],
			json_encode($p['mail'])
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->mail.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		if($sql->execute($params)) {
			throwAlert('success', '', '<p>Le mail <strong>"'.$p['mail']['objet'].'"</strong> a bien été ajouté.</p>');
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création du mail <strong>"'.$p['mail']['objet'].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			return false;
		}
	}
	
	
	public function modifyMail($p) {
		$params = array(
			$p['hook'],
			$p['target'],
			json_encode($p['mail']),
			$p['action_modify']
		);
		$sql = $this->db->prepare('UPDATE '.$this->mail.'
			SET hook = ?,
			target = ?,
			data = ?
			WHERE id = ?;');
		if($sql->execute($params)) {
			throwAlert('success', '', '<p>Le mail <strong>"'.$p['mail']['objet'].'"</strong> a bien été modifié.</p>');
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification du mail <strong>"'.$p['mail']['objet'].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			return false;
		}
	}
	
	
	public function deleteMail($id) {
		if($p = $this->getMail($id)) {
			$data = json_decode($p->data);
			$sql = $this->db->prepare('DELETE FROM '.$this->mail.'
				WHERE id = ?;');
			$sql->execute(array($id));
			throwAlert('success', '', '<p>Le mail <strong>"'.$data->objet->{_LANG_DEFAULT}.'"</strong> a bien été supprimé.</p>');
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Ce mail n\'existe pas.');
			return false;
		}
	}
	
	
}