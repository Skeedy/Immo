<?php
class Model_parametre extends Model {
	
	public function getAllAssoc() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->parametre.';');
		$sql->execute();
		$res = $sql->fetchAll();
		foreach($res as $r)
			$params[$r->id] = $r->value;
		return $params;
	}
    public function getAll() {
        $sql = $this->db->prepare('SELECT *
			FROM '.$this->parametre.';');
        $sql->execute();
        return $sql->fetchAll();
    }
    public function getContactMail(){
        $sql= $this->db->prepare('SELECT `value`
	    FROM '. $this->parametre .'
	    WHERE id = ?');
        $sql->execute(array('mail_contact'));
        return $sql->fetch();
    }
}
