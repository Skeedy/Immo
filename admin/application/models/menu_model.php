<?php
class Model_menu extends Model {

	public function getAll() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->menu.'
			ORDER BY label;');
		$sql->execute();
		$res = $sql->fetchAll();
		foreach($res as &$v)
			$v->data = json_decode($v->data);
		return $res;
	}
	
	public function getMenu($id) {
		$sql = $this->db->prepare('SELECT data
			FROM '.$this->menu.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetchColumn()) {
			return $res;
		}
		else
			return false;
	}
	
	
	public function checkPostHome($p) {
		$error = array();
		if(empty($p['home']))
			$error[] = 'Le champ "Page d\'accueil" n\'est pas renseigné.';
		return $error;
	}
	
	
	public function checkPostMenu($p) {
		$error = array();
		if(!empty($p['menu'])) {
			foreach($p['menu'] as $k => $menus) {
				foreach($menus as $v) {
					if($v['type'] == 'page') {
						if(empty($v['page']))
							$error[] = 'Le champ "Page" d\'une entrée du menu n\'est pas renseigné.';
					}
					else if($v['type'] == 'url') {
						if(empty($v['url']))
							$error[] = 'Le champ "URL" d\'une entrée du menu n\'est pas renseigné.';
					}
				}
			}
		}
		return $error;
	}

	
	
	public function modifyMenu($p) {
		$error = $this->checkPostMenu($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		
		if(!empty($p['menu'])) {
			foreach($p['menu'] as $k => $menus) {
				$menu = array();
				foreach($menus as $v) {
					if(!empty($v['type']))
						$menu[] = $v;
				}
				$sql = $this->db->prepare('UPDATE '.$this->menu.' SET data = ? WHERE id = ?;');
				if($sql->execute(array(json_encode($menu), $k))) {
					throwAlert('success', '', '<p>Le menu '.$k.' a bien été modifié.</p>');
					historique_write('MENU'.$id.'ADMIN'.$_SESSION['id'], 'Modification menu '.$k, array('description' => 'Modification menu '.$k.' depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_SESSION['id'].' ('.$_SESSION['prenom'].' '.$_SESSION['nom'].')', 'POST' => cleanPost($p)));
				}
				else {
					$e = $this->db->errorInfo();
					throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification du menu "'.$k.'".</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
					historique_write('MENU'.$k.'ADMIN'.$_SESSION['id'], 'Erreur modification menu', array('description' => 'Erreur modification menu '.$k.' depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_SESSION['id'].' ('.$_SESSION['prenom'].' '.$_SESSION['nom'].')', 'error' => $e, 'POST' => cleanPost($p)));
					return false;
				}
			}
		}
		return true;
	}
	
}
