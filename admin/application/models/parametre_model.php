<?php
class Model_parametre extends Model {
	
	public function getAll() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->parametre.';');
		$sql->execute();
		return $sql->fetchAll();
	}
	
	
	public function modify($p) {
		$this->db->exec('TRUNCATE '.$this->parametre.';');
		$params = array();
		foreach($p['parametres'] as $v) {
			$str[] = '(?, ?, ?)';
			array_push($params, $v['id'], !empty($v['value']) ? encodeDirs($v['value']) : '', $v['type']);
		}
		$sql = $this->db->prepare('INSERT INTO '.$this->parametre.' VALUES '.implode(', ', $str).';');
		$sql->execute($params);
		throwAlert('success', '', '<p>Les paramètres généraux ont bien été mis à jour.</p>');
		return true;
	}
	
	
	public function getAllAssoc() {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->parametre.';');
		$sql->execute();
		$res = $sql->fetchAll();
		foreach($res as $r)
			$params[$r->id] = $r->value;
		return $params;
	}


	public function makeCustomCss() {
		$_PARAMS = $this->getAllAssoc();
		$old = $_PARAMS['timestamp'];
		$new = time();
		$str = '#header{';
		if(!empty($_PARAMS['site_header_background_color']))
			$str .= 'background-color:'.$_PARAMS['site_header_background_color'].';';
		if(!empty($_PARAMS['site_header_background_image'])) {
			$str .= 'background-image:url("../'._DIR_MEDIA.$_PARAMS['site_header_background_image'].'");';
			$str .= 'background-position:'.$_PARAMS['site_header_background_align_horizontal'].' '.$_PARAMS['site_header_background_align_vertical'].';';
			$str .= 'background-repeat:'.$_PARAMS['site_header_background_repeat'].';';
			$str .= 'background-size:'.$_PARAMS['site_header_background_size'].';';
		}
		$str .= '}';
		$str .= '#header .bg{';
		if(!empty($_PARAMS['site_header_background2_image'])) {
			$str .= 'background-image:url("../'._DIR_MEDIA.$_PARAMS['site_header_background2_image'].'");';
			$str .= 'background-position:'.$_PARAMS['site_header_background2_align_horizontal'].' '.$_PARAMS['site_header_background2_align_vertical'].';';
			$str .= 'background-repeat:'.$_PARAMS['site_header_background2_repeat'].';';
			$str .= 'background-size:'.$_PARAMS['site_header_background2_size'].';';
		}
		$str .= '}';
		$fp = fopen('../'._DIR_CSS.$new.'.css', 'w');
		fwrite($fp, $str);
		fclose($fp);
		$sql = $this->db->prepare('UPDATE '.$this->parametre.'
			SET value = ?
			WHERE id = ?;');
		$sql->execute(array($new, 'timestamp'));
		if(file_exists('../'._DIR_CSS.$old.'.css'))
			unlink('../'._DIR_CSS.$old.'.css');
	}
	public function getContactMail(){
	    $sql= $this->db->prepare('SELECT `value`
	    FROM '. $this->parametre .'
	    WHERE id = ?');
	    $sql->execute(array('mail_contact'));
	    return $sql->fetch();
    }
	
}