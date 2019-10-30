<?php
class Model_historique extends Model {
	
	public function write($elements, $label, $data = array()) {
		$params = array(
			null,
			date('Y-m-d H:i:s'),
			$elements,
			json_encode(array('label' => $label, 'data' => $data))
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->historique.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		$sql->execute($params);
	}
	
	public function read($element) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->historique.'
			WHERE elements LIKE ?
			ORDER BY id desc;');
		$sql->execute(array('%'.$element.'%'));
		return $sql->fetchAll();
	}
	
	public function getLatest($limit = 100) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->historique.'
			ORDER BY id desc
			LIMIT 0 , '.$limit.';');
		$sql->execute();
		return $sql->fetchAll();
	}
	
	public function search($elements, $debut, $fin) {
		$params = array();
		$and = '';
		foreach(explode(' ', $elements) as $v) {
			$and .= 'AND elements LIKE ? ';
			$params[] = '%'.$v.'%';
		}
		if(!empty($debut) && !empty($fin)) {
			$and .= 'AND date BETWEEN ? AND ?';
			array_push($params, $debut->format('Y-m-d H:i:s'), $fin->format('Y-m-d H:i:s'));
		}
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->historique.'
			WHERE 1
			'.$and.'
			ORDER BY id desc;');
		$sql->execute($params);
		return $sql->fetchAll();
	}
	
}
