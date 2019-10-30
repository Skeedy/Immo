<?php
class Model_page extends Model {
	
	public function getPage($id) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}


	public function getPageFromTemplate($template) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template = ?;');
		$sql->execute(array($template));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}


	public function getPagesFromTemplate($template) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template = ?;');
		$sql->execute(array($template));
		return $sql->fetchAll();
	}
	
	
	public function getAllPages($sort = 'id DESC') {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			ORDER BY '.addslashes($sort).';');
		$sql->execute();
		return $sql->fetchAll();
	}
	
	
	public function checkPostPage($p) {
		$error = array();
		if(preg_match('/^\d+-/', $p['url']))
			$error[] = 'Le schéma d\'url <strong>"^\d+-*"</strong> est réservé aux biens, veuillez choisir une autre url.';
		else if(isset($p['action_add']) && in_array($p['url'], $this->getAllUrls())) {
			$error[] = 'L\'url <strong>"'.$p['url'].'"</strong> est déjà utilisée, veuillez en choisir une autre.';
		}
		else if(!empty($p['action_modify'])) {
			$u = $this->getPage($p['action_modify']);
			if($u->url != $p['url'] && in_array($p['url'], $this->getAllUrls()))
				$error[] = 'L\'url <strong>"'.$p['url'].'"</strong> est déjà utilisée, veuillez en choisir une autre.';
		}
		return $error;
	}
	
	
	public function addPage($p) {
		global $_current_user;

		$error = $this->checkPostPage($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		$params = array(
			null,
			date('Y-m-d H:i:s'),
			date('Y-m-d H:i:s'),
			json_encode($p['titre']),
			$p['url'],
			$p['template'],
			json_encode(array_map_recursive('encodeDirs', jsonToAssocInArray($p['page']))),
			!empty($p['active']) ? 1 : 0
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->page.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		if($sql->execute($params)) {
			$id = $this->db->lastInsertId();
			throwAlert('success', '', '<p>La page <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong> a bien été ajoutée.</p>');
			historique_write('PAGE'.$id.'ADMIN'.$_current_user->id, 'Ajout page', array('description' => 'Ajout page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création de la page <strong>"'.$p['titre'].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('ADMIN'.$_current_user->id, 'Erreur ajout page', array('description' => 'Erreur ajout page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}

	
	public function modifyPage($p) {
		global $_current_user;

		$error = $this->checkPostPage($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		$str = '';
		$params = array(
			date('Y-m-d H:i:s'),
			json_encode($p['titre']),
			$p['url'],
			$p['template'],
			json_encode(array_map_recursive('encodeDirs', jsonToAssocInArray($p['page']))),
			!empty($p['active']) ? 1 : 0,
			$p['action_modify']
		);
		$sql = $this->db->prepare('UPDATE '.$this->page.'
			SET date_modification = ?, titre = ?, url = ?, template = ?, data = ?, active = ?
			WHERE id = ?;');
		if($sql->execute($params)) {
			throwAlert('success', '', '<p>La page <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong> a bien été modifiée.</p>');
			historique_write('PAGE'.$p['action_modify'].'ADMIN'.$_current_user->id, 'Modification page', array('description' => 'Modification page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification de la page "'.$p['titre'][_LANG_DEFAULT].'".</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('PAGE'.$p['action_modify'].'ADMIN'.$_current_user->id, 'Erreur modification page', array('description' => 'Erreur modification page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}
	
	
	public function deletePage($id) {
		global $_current_user;

		if($p = $this->getPage($id)) {
			$sql = $this->db->prepare('DELETE FROM '.$this->page.'
				WHERE id = ?;');
			$sql->execute(array($id));
			throwAlert('success', '', '<p>La page <strong>"'.$p->titre.'"</strong> a bien été supprimée.</p>');
			historique_write('PAGE'.$id.'ADMIN'.$_current_user->id, 'Suppression page', array('description' => 'Suppression page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Cette page n\'existe pas.');
			return false;
		}
	}
	
	
	public function switchPage($id) {
		global $_current_user;

		if(($page = $this->getPage($id)) == false)
			return false;
		$active = $page->active == 1 ? 0 : 1;
		$sql = $this->db->prepare('UPDATE '.$this->page.'
			SET active = ?
			WHERE id = ?;');
		$sql->execute(array($active, $id));
		historique_write('PAGE'.$id.'ADMIN'.$_current_user->id, ($active == 1 ? 'Activation' : 'Désactivation').' page', array('description' => ($active == 1 ? 'Activation' : 'Désactivation').' page depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
		return array('active' => $active);
	}


	public function getAllUrls() {
		$sql = $this->db->prepare('SELECT url
			FROM '.$this->page.';');
		$sql->execute();
		$return = $sql->fetchAll(PDO::FETCH_COLUMN);

		return $return;
	}


	public function addPreview($p) {
		$params = array(
			null,
			date('Y-m-d H:i:s'),
			json_encode(array_map_recursive('encodeDirs', jsonToAssocInArray($p)))
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->preview.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		$sql->execute($params);
		return $this->db->lastInsertId();
	}
	
}
