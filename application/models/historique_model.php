<?php
class Model_historique extends Model {
	
	public function write($elements, $label, $data = array()) {
		$params = array(
			'',
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
	
}
