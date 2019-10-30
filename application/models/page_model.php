<?php
class Model_page extends Model {
	
	public function getPage($url) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE url = ?
			AND active = 1;');
		$sql->execute(array($url));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}
	
	
	public function getHome() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template = ?
			AND active = 1;');
		$sql->execute(array('home'));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}

    public function getImmeublePage(){
        $sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template = ?
			AND active = 1;');
        $sql->execute(array('immeuble'));
        if($res = $sql->fetch()) {
            return $res;
        }
        else
            return false;
    }

	public function getPageFromTemplate($template) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template = ?
			AND active = 1;');
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
			WHERE template = ?
			AND active = 1;');
		$sql->execute(array($template));
		return $sql->fetchAll();
	}


	public function getServices() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->page.'
			WHERE template LIKE ?
			AND active = 1;');
		$sql->execute(array('service_%'));
		return $sql->fetchAll();
	}

	
	public function getSiteMap() {
		$return = array('');
		$sql = $this->db->prepare('SELECT url
			FROM '.$this->page.'
			WHERE active = 1;');
		$sql->execute();
		$return = array_merge($return, $sql->fetchAll(PDO::FETCH_COLUMN));
		return $return;
	}


	public function getPreview($id) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->preview.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch()) {
			$sql = $this->db->prepare('DELETE FROM '.$this->preview.'
				WHERE id = ?;');
			$sql->execute(array($id));
			return $res;
		}
		else
			return false;
	}


	public function getActualiteDirImg($id) {
		return _DIR_IMG_ACTUALITE.preg_replace('/(\d{1})/', '$1/', $id);
	}


	public function getActualites($page = false) {
		$where = array();
		$params = array();
		$return = new stdClass();

		//filtres annonces
		if(!empty($agence) && is_numeric($agence)) {
			$where[] = $this->actualite.'.agence = ?';
			$params[] = $agence;
		}

		//filtres
		if(!empty($_POST['agence']) && is_numeric($_POST['agence'])) {
			$where[] = $this->actualite.'.agence = ?';
			$params[] = $_POST['agence'];
		}

		if(!empty($_POST['type']) && is_numeric($_POST['type'])) {
			$where[] = $this->actualite.'.type = ?';
			$params[] = $_POST['type'];
		}
		
		if($page) {
			$sql = $this->db->prepare('SELECT COUNT('.$this->actualite.'.id)
				FROM '.$this->actualite.'
				'.(!empty($where) ? 'WHERE '.implode(' AND ', $where) : '').';');
			$sql->execute($params);
			$total = $sql->fetchColumn();
			$page_max = ceil($total / _SITE_NB_ANNONCES);
			if($page_max == 0)
				$page_max = 1;
			$page = !empty($page) && is_numeric($page) ? $page : 1;
			if($page > $page_max)
				$page = $page_max;
			$min = ($page - 1) * _SITE_NB_ANNONCES;
		}

		$sql = $this->db->prepare('SELECT '.$this->actualite.'.*, '.$this->type_actualite.'.label AS type_label, '.$this->agence.'.nom AS agence_nom
			FROM '.$this->type_actualite.', '.$this->actualite.', '.$this->agence.'
			WHERE '.$this->actualite.'.type = '.$this->type_actualite.'.id
			AND '.$this->actualite.'.agence = '.$this->agence.'.id
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').'
			ORDER BY id DESC
			LIMIT '.($page ? _SITE_NB_ANNONCES.' OFFSET '.$min : _SITE_AGENCE_NB_ANNONCES).';');
		$sql->execute($params);
		$return->annonces = $sql->fetchAll();
		if($page) {
			$return->total = $total;
			$return->page_max = $page_max;
			$return->page = $page;
		}
		return $return;
	}


	public function getAllActualites() {
		$sql = $this->db->prepare('SELECT '.$this->actualite.'.*
			FROM '.$this->actualite.';');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getActualitesAssociees($date, $agence, $type, $limit = 2) {
		$return = new stdClass();
		$sql = $this->db->prepare('SELECT '.$this->actualite.'.*, '.$this->type_actualite.'.label AS type_label, '.$this->agence.'.nom AS agence_nom, ABS('.$this->actualite.'.date - :date) AS diff_date, ABS('.$this->actualite.'.agence - :agence) AS diff_agence, ABS('.$this->actualite.'.type - :type) AS diff_type
			FROM '.$this->type_actualite.', '.$this->actualite.', '.$this->agence.'
			WHERE '.$this->actualite.'.type = '.$this->type_actualite.'.id
			AND '.$this->actualite.'.agence = '.$this->agence.'.id
			ORDER BY diff_type, diff_agence, diff_date, id DESC
			LIMIT '.((int) $limit).';');
		$sql->execute(array('date' => $date, 'agence' => $agence, 'type' => $type));
		$return->annonces = $sql->fetchAll();
		return $return;
	}


	public function getActualitesTypes() {
		$sql = $this->db->prepare('SELECT DISTINCT '.$this->type_actualite.'.id, '.$this->type_actualite.'.label
			FROM '.$this->type_actualite.'
			ORDER BY ordre;');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getActualite($id) {
		$sql = $this->db->prepare('SELECT '.$this->actualite.'.*, '.$this->type_actualite.'.label AS type_label, '.$this->agence.'.nom AS agence_nom
			FROM '.$this->type_actualite.', '.$this->actualite.', '.$this->agence.'
			WHERE '.$this->actualite.'.type = '.$this->type_actualite.'.id
			AND '.$this->actualite.'.agence = '.$this->agence.'.id
			AND '.$this->actualite.'.id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch())
			return $res;
		else
			return false;
	}


	public function getAllPages() {
		$sql = $this->db->prepare('SELECT url
			FROM '.$this->page.'
			WHERE active = 1;');
		$sql->execute();
		return $sql->fetchAll();
	}


}
