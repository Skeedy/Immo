<?php
class Model_annonce extends Model {


	public function getAllTypes() {
		$sql = $this->db->prepare('SELECT id, label
			FROM '.$this->type.'
			ORDER BY ordre;');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getType($id) {
		$sql = $this->db->prepare('SELECT *
			FROM '.$this->type.'
			WHERE id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch())
			return $res;
		else
			return false;
	}


	public function getTypes($types) {
		$sql = $this->db->prepare('SELECT id, label
			FROM '.$this->type.'
			WHERE id IN ('.implode(',', (array) $types).');');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getVilles($villes) {
		$sql = $this->db->prepare('SELECT id, nom_reel
			FROM '.$this->ville.'
			WHERE id IN ('.implode(',', (array) $villes).');');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function addType($p) {
		global $_LANGS, $_current_user;
		$error = array();
		foreach($_LANGS as $l => $ll) {
			if(empty($p['label'][$l]))
				$error[] = 'Le champ "Nom '.printLangTag($l).'" n\'est pas renseigné.';
		}
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}
		$sql = $this->db->prepare('SELECT MAX(ordre)
			FROM '.$this->type.';');
		$sql->execute();
		if(!($max = $sql->fetchColumn()))
			$max = 0;
		$params = array(
			null,
			json_encode($p['label']),
			$max + 1
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->type.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		if($sql->execute($params)) {
			$id = $this->db->lastInsertId();
			throwAlert('success', '', '<p>Le type <strong>"'.$p['label'][_LANG_DEFAULT].'"</strong> a bien été ajouté.</p>');
			historique_write('TYPE'.$id.'ADMIN'.$_current_user->id, 'Ajout type', array('description' => 'Ajout type depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création du type <strong>"'.$p['label'][_LANG_DEFAULT].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('ADMIN'.$_current_user->id, 'Erreur ajout type', array('description' => 'Erreur ajout type depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}


	public function modifyTypes($p) {
		global $_LANGS, $_current_user;
		$error = array();
		foreach($_LANGS as $l => $ll) {
			foreach($p['label'][$l] as $v)
				if(empty($v))
					$error[] = 'Un champ "Nom '.printLangTag($l).'" n\'est pas renseigné.';
		}
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}

		//on supprime les types non utilisés
		$sql = $this->db->prepare('SELECT id
			FROM '.$this->type.';');
		$sql->execute();
		$res = $sql->fetchAll(PDO::FETCH_COLUMN);
		foreach ($res as $v) {
			if(!in_array($v, $p['types_ordre'])) {
				$sql = $this->db->prepare('DELETE FROM '.$this->type.'
					WHERE id = ?;');
				$sql->execute(array($v));
			}
		}

		//boucle
		$i = 0;
		for($i = 0; $i < count($p['types_ordre']); $i++) {
			foreach($_LANGS as $l => $ll)
				$labels->{$l} = $p['label'][$l][$i];
			//nouveau
			if(empty($p['types_ordre'][$i])) {
				$params = array(
					null,
					json_encode($labels),
					$i
				);
				$sql = $this->db->prepare('INSERT INTO '.$this->type.'
					VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
				$sql->execute($params);
			}
			//modification
			else {

				$sql = $this->db->prepare('UPDATE '.$this->type.'
					SET label = ?, ordre = ?
					WHERE id = ?;');
				$sql->execute(array(json_encode($labels), $i, $p['types_ordre'][$i]));
			}
		}
		throwAlert('success', '', '<p>Les types ont bien été modifiés.</p>');
		return true;
	}


	public function deleteType($id) {
		global $_current_user;
		if($p = $this->getType($id)) {
			$p->label = json_decode($p->label);
			$sql = $this->db->prepare('DELETE FROM '.$this->type.'
				WHERE id = ?;');
			$sql->execute(array($id));
			throwAlert('success', '', '<p>Le type <strong>"'.$p->label->{_LANG_DEFAULT}.'"</strong> a bien été supprimé.</p>');
			historique_write('TYPE'.$id.'ADMIN'.$_current_user->id, 'Suppression type', array('description' => 'Suppression type depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Ce type n\'existe pas.');
			return false;
		}
	}


	public function searchTags($str, $type, $langue) {
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT label
				FROM '.$this->tag.'
				WHERE type = ?
				AND langue = ?
				ORDER BY utilisations DESC
				LIMIT 30;');
			$sql->execute(array($type, $langue));
			return $sql->fetchAll();
		}

		$params['type'] = $type;
		$params['langue'] = $langue;
		
		$t = preg_split('/[\s]+/', $slug);
		for($i = 0; $i < count($t); $i++) {
			if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(label) - '.strlen($t[$i]).') AS diff_length'.$i.',
					(label LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					(label LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					(label LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$where[] = 'label LIKE :slug_milieu'.$i;
				$j = preg_replace('/[\s]+/', '', $t[$i]);
				$orderexact[strlen($j)][] = 'slug_exact'.$i.' DESC, utilisations DESC';
				$orderdebut[strlen($j)][] = 'slug_debut'.$i.' DESC, utilisations DESC';
				$ordermilieu[strlen($j)][] = 'slug_milieu'.$i.' DESC, utilisations DESC, diff_length'.$i;
			}
		}

		if(empty($where))
			return array();

		krsort($orderexact);
		krsort($orderdebut);
		krsort($ordermilieu);
		$sort = array();
		foreach($orderexact as $v)
			foreach($v as $vv)
				$sort[] = $vv;
		foreach($orderdebut as $v)
			foreach($v as $vv)
				$sort[] = $vv;
		foreach($ordermilieu as $v)
			foreach($v as $vv)
				$sort[] = $vv;

		if(!empty($marque))
			$params['marque'] = $marque;

		$sql = $this->db->prepare('SELECT DISTINCT label,
			'.implode(', ', $select).'
			FROM '.$this->tag.'
			WHERE type = :type
			AND langue = :langue
			'.(!empty($where) ? 'AND ('.implode(' AND ', $where).')' : '').'
			ORDER BY '.implode(', ', $sort).'
			LIMIT 30;');
		$sql->execute($params);
		return $sql->fetchAll();
	}


	public function searchTagValeurs($str, $type, $langue, $tag) {
		if(empty($type) || empty($langue) || empty($tag))
			return array();

		$sql = $this->db->prepare('SELECT id
			FROM '.$this->tag.'
			WHERE type = ?
			AND langue = ?
			AND label = ?
			LIMIT 1;');
		$sql->execute(array($type, $langue, $tag));
		$id = $sql->fetchColumn();
		if(empty($id))
			return array();
			
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT label
				FROM '.$this->tag_valeur.'
				WHERE tag = ?
				ORDER BY utilisations DESC, label
				LIMIT 30;');
			$sql->execute(array($id));
			return $sql->fetchAll(PDO::FETCH_COLUMN);
		}

		$params['tag'] = $id;
		
		$t = preg_split('/[\s]+/', $slug);
		for($i = 0; $i < count($t); $i++) {
			if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(label) - '.strlen($t[$i]).') AS diff_length'.$i.',
					(label LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					(label LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					(label LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$where[] = 'label LIKE :slug_milieu'.$i;
				$j = preg_replace('/[\s]+/', '', $t[$i]);
				$orderexact[strlen($j)][] = 'slug_exact'.$i.' DESC, utilisations DESC, label';
				$orderdebut[strlen($j)][] = 'slug_debut'.$i.' DESC, utilisations DESC, label';
				$ordermilieu[strlen($j)][] = 'slug_milieu'.$i.' DESC, utilisations DESC, label, diff_length'.$i;
			}
		}

		if(empty($where))
			return array();

		krsort($orderexact);
		krsort($orderdebut);
		krsort($ordermilieu);
		$sort = array();
		foreach($orderexact as $v)
			foreach($v as $vv)
				$sort[] = $vv;
		foreach($orderdebut as $v)
			foreach($v as $vv)
				$sort[] = $vv;
		foreach($ordermilieu as $v)
			foreach($v as $vv)
				$sort[] = $vv;

		if(!empty($marque))
			$params['marque'] = $marque;

		$sql = $this->db->prepare('SELECT DISTINCT label,
			'.implode(', ', $select).'
			FROM '.$this->tag_valeur.'
			WHERE tag = :tag
			'.(!empty($where) ? 'AND ('.implode(' AND ', $where).')' : '').'
			ORDER BY '.implode(', ', $sort).'
			LIMIT 30;');
		$sql->execute($params);
		return $sql->fetchAll(PDO::FETCH_COLUMN);
	}


	public function searchVille($str, $limit = 10) {
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$wherecp = array();
		$whereville = array();
		$ordercpexact = array();
		$ordercpdebut = array();
		$ordercpmilieu = array();
		$ordervilleexact = array();
		$ordervilledebut = array();
		$ordervillemilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT DISTINCT '.$this->ville.'.id, '.$this->ville.'.nom_reel, '.$this->ville.'.cp, COUNT('.$this->annonce.'.id) AS nb
				FROM '.$this->ville.', '.$this->annonce.'
				WHERE '.$this->ville.'.id = '.$this->annonce.'.ville
				GROUP BY '.$this->ville.'.id
				ORDER BY nb DESC
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_FUNC, function($id, $nom, $cp) {
				$t = new stdClass;
				$t->cp = $cp;
				if(strlen($cp) > 11)
					$cp = preg_replace('/^([^-]+)-(.*)-([^-]+)$/', "$1-$3", $cp);
				$t->id = $id;
				$t->str = $nom.' '.$cp;
				$t->nom = $nom;
				return $t;
			});
		}
		
		$t = preg_split('/[\s]+/', $slug);
		array_unshift($t, $str);
		for($i = 0; $i < count($t); $i++) {
			if(is_numeric($t[$i])) {
				$select[] = '(cp LIKE :cp_exact'.$i.') AS cp_exact'.$i.',
					(cp LIKE :cp_debut'.$i.') AS cp_debut'.$i.',
					(cp LIKE :cp_milieu'.$i.') AS cp_milieu'.$i;
				$params['cp_exact'.$i] = $t[$i];
				$params['cp_debut'.$i] = $t[$i].'%';
				$params['cp_milieu'.$i] = '%'.$t[$i].'%';
				$wherecp[] = 'cp like :cp_milieu'.$i;
				$ordercpexact[strlen($t[$i])] = 'cp_exact'.$i.' DESC';
				$ordercpdebut[strlen($t[$i])] = 'cp_debut'.$i.' DESC';
				$ordercpmilieu[strlen($t[$i])] = 'cp_milieu'.$i.' DESC';
			}
			else if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(nom_reel) - '.strlen($t[$i]).') AS diff_length'.$i.',
					('.$this->ville.'.slug LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_exact'.$i.') AS nom_simple_exact'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_exact'.$i.') AS nom_reel_exact'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_exact'.$i.') AS soundex_exact'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_debut'.$i.') AS soundex_debut'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_milieu'.$i.') AS soundex_milieu'.$i.',
					('.$this->ville.'.slug LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_debut'.$i.') AS nom_simple_debut'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_debut'.$i.') AS nom_reel_debut'.$i.',
					('.$this->ville.'.slug LIKE :slug_milieu'.$i.') AS slug_milieu'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_milieu'.$i.') AS nom_simple_milieu'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_milieu'.$i.') AS nom_reel_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$soundex = soundex($t[$i]);
				$params['soundex_exact'.$i] = $soundex;
				$params['soundex_debut'.$i] = $soundex.'%';
				$params['soundex_milieu'.$i] = '%'.$soundex.'%';
				$whereville[] = '('.$this->ville.'.slug LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.nom_simple LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.nom_reel LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.soundex LIKE :soundex_milieu'.$i.')';
				$ordervilleexact[strlen($t[$i])] = 'nom_reel_exact'.$i.' DESC, nom_simple_exact'.$i.' DESC, slug_exact'.$i.' DESC';
				$ordervilledebut[strlen($t[$i])] = 'nom_reel_debut'.$i.' DESC, nom_simple_debut'.$i.' DESC, slug_debut'.$i.' DESC, soundex_exact'.$i.' DESC, soundex_debut'.$i.' DESC, diff_length'.$i;
				$ordervillemilieu[strlen($t[$i])] = 'nom_reel_milieu'.$i.' DESC, nom_simple_milieu'.$i.' DESC, slug_milieu'.$i.' DESC, soundex_milieu'.$i.' DESC';
			}
		}

		if(empty($select))
			return array();

		krsort($ordercpexact);
		krsort($ordercpdebut);
		krsort($ordercpmilieu);
		krsort($ordervilleexact);
		krsort($ordervilledebut);
		krsort($ordervillemilieu);

		if(!empty($region))
			$params['region'] = $region;

		$sql = $this->db->prepare('SELECT '.$this->ville.'.id, '.$this->ville.'.nom_reel, '.$this->ville.'.cp,
			'.implode(', ', $select).'
			FROM '.$this->ville.', '.$this->departement.', '.$this->region.'
			WHERE '.$this->ville.'.departement = '.$this->departement.'.id
			AND '.$this->departement.'.region = '.$this->region.'.id
			'.(!empty($wherecp) ? 'AND ('.implode(' OR ', $wherecp).')' : '').'
			'.(!empty($whereville) ? 'AND ('.implode(' OR ', $whereville).')' : '').'
			ORDER BY '.implode(', ', array_merge($ordercpexact, $ordervilleexact, $ordercpdebut, $ordervilledebut, $ordercpmilieu, $ordervillemilieu)).'
			'.($limit != -1 ? 'LIMIT '.$limit : '').';');
		$sql->execute($params);
		return $sql->fetchAll(PDO::FETCH_FUNC, function($id, $nom, $cp) {
			$t = new stdClass;
			$t->cp = $cp;
			if(strlen($cp) > 11)
				$cp = preg_replace('/^([^-]+)-(.*)-([^-]+)$/', "$1-$3", $cp);
			$t->id = $id;
			$t->str = $nom.' '.$cp;
			$t->nom = $nom;
			return $t;
		});
	}


	public function getAnnonceDirImg($id) {
		return _DIR_IMG_ANNONCE.preg_replace('/(\d{1})/', '$1/', $id);
	}

	public function delTree($dir) {
		if(!empty($dir) && preg_match('#^('._DIR_IMG_TMP.'(.+))$#', $dir)) {
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach($files as $file) { 
				(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
			} 
			rmdir($dir);
		}
	}

	public function delTreeVid($dir) {
		if(!empty($dir) && preg_match('#^('._DIR_VIDEO_TMP.'(.+))$#', $dir)) {
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach($files as $file) { 
				(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
			} 
			rmdir($dir);
		}
	}


	public function getNewRef() {
		$sql = $this->db->prepare('SELECT COUNT(id)
			FROM '.$this->annonce.'
			WHERE date LIKE ?;');
		$sql->execute( array( date('Y').'-%' ) );
		$res = $sql->fetchColumn();
		return 'L' . date('Y') . str_pad($res + 1, 3, '0', STR_PAD_LEFT);
	}


	public function searchAnnonceIds($str, $limit = 10) {
		global $_current_user;

		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.id
				FROM '.$this->annonce.'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			return $sql->fetchAll();
		}
		
		$t = preg_split('/[\s]+/', $slug);
		array_unshift($t, $str);
		for($i = 0; $i < count($t); $i++) {
			if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(id) - '.strlen($t[$i]).') AS diff_length'.$i.',
					('.$this->annonce.'.id LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					('.$this->annonce.'.id LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					('.$this->annonce.'.id LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$where[] = '('.$this->annonce.'.id LIKE :slug_milieu'.$i.')';
				$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
				$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC, diff_length'.$i;
				$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
			}
		}

		if(empty($select))
			return array();

		krsort($orderexact);
		krsort($orderdebut);
		krsort($ordermilieu);

		$sql = $this->db->prepare('SELECT '.$this->annonce.'.id,
			'.implode(', ', $select).'
			FROM '.$this->annonce.'
			'.(!empty($where) ? 'WHERE ('.implode(' OR ', $where).')' : '').'
			ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
			'.($limit != -1 ? 'LIMIT '.$limit : '').';');
		$sql->execute($params);
		return $sql->fetchAll();
	}


	public function searchAnnonceRefs($str, $limit = 10) {
		global $_current_user;

		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.ref
				FROM '.$this->annonce.'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			return $sql->fetchAll();
		}
		
		$t = preg_split('/[\s]+/', $slug);
		array_unshift($t, $str);
		for($i = 0; $i < count($t); $i++) {
			if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(ref) - '.strlen($t[$i]).') AS diff_length'.$i.',
					('.$this->annonce.'.ref LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					('.$this->annonce.'.ref LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					('.$this->annonce.'.ref LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$where[] = '('.$this->annonce.'.ref LIKE :slug_milieu'.$i.')';
				$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
				$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC, diff_length'.$i;
				$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
			}
		}

		if(empty($select))
			return array();

		krsort($orderexact);
		krsort($orderdebut);
		krsort($ordermilieu);

		$sql = $this->db->prepare('SELECT '.$this->annonce.'.ref,
			'.implode(', ', $select).'
			FROM '.$this->annonce.'
			'.(!empty($where) ? 'WHERE ('.implode(' OR ', $where).')' : '').'
			ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
			'.($limit != -1 ? 'LIMIT '.$limit : '').';');
		$sql->execute($params);
		return $sql->fetchAll();
	}


	public function searchAnnonceForNewsletter($str, $limit = 10) {
		global $_current_user, $db_page;

		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		$_pages_urls = array();
		foreach (array(1 => 'usage_shooting', 2 => 'usage_tournage', 3 => 'usage_evenementiel') as $k => $v) {
			if($t = $db_page->getPageFromTemplate($v))
				$_pages_urls[$k] = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$t->url;
			else
				$_pages_urls[$k] = '';
		}

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel, '.$this->localisation.'.nom AS localisation_label
				FROM '.$this->localisation.', '.$this->annonce.'
				LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
				WHERE '.$this->annonce.'.localisation = '.$this->localisation.'.id
				'.($_current_user->role != 'root' ? 'AND agence = '.$_current_user->agence : '').'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			$res = $sql->fetchAll();
			foreach ($res as &$v) {
				$images = !empty($v->images) && json_decode($v->images) ? json_decode($v->images) : array();
				$v->image = !empty($images) ? _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$this->getAnnonceDirImg($v->id).'md_'.$images[0]->image : '';

				$titre = json_decode($v->titre);
				$v->titre = $titre->fr;

				$sql = $this->db->prepare('SELECT offre
					FROM '.$this->annonce_offre.'
					WHERE annonce = ?;');
				$sql->execute(array($v->id));
				$offre = $sql->fetchColumn();
				$v->url = $_pages_urls[$offre].'/'.$v->id.'-'.clean_str($v->titre);

				$v->str = $v->ref.' - '.$v->cp.' '.$v->nom_reel.' - '.$v->titre;

				$localisation = json_decode($v->localisation_label);
				$v->localisation = $localisation->fr;
			}
			return $res;
		}
		
		$t = preg_split('/[\s]+/', $slug);
		array_unshift($t, $str);
		for($i = 0; $i < count($t); $i++) {
			if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(ref) - '.strlen($t[$i]).') AS diff_length'.$i.',
					('.$this->annonce.'.ref LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					('.$this->annonce.'.ref LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					('.$this->annonce.'.ref LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$where[] = '('.$this->annonce.'.ref LIKE :slug_milieu'.$i.')';
				$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
				$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC, diff_length'.$i;
				$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
			}
		}

		if(empty($select))
			return array();

		krsort($orderexact);
		krsort($orderdebut);
		krsort($ordermilieu);

		$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel, '.$this->localisation.'.nom AS localisation_label,
			'.implode(', ', $select).'
			FROM '.$this->localisation.', '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			WHERE '.$this->annonce.'.localisation = '.$this->localisation.'.id
			'.(!empty($where) ? 'AND ('.implode(' OR ', $where).')' : '').'
			'.($_current_user->role != 'root' ? 'AND agence = '.$_current_user->agence : '').'
			ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
			'.($limit != -1 ? 'LIMIT '.$limit : '').';');
		$sql->execute($params);
		$res = $sql->fetchAll();
		foreach ($res as &$v) {
			$images = !empty($v->images) && json_decode($v->images) ? json_decode($v->images) : array();
			$v->image = !empty($images) ? _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$this->getAnnonceDirImg($v->id).'md_'.$images[0]->image : '';

			$titre = json_decode($v->titre);
			$v->titre = $titre->fr;

			$sql = $this->db->prepare('SELECT offre
				FROM '.$this->annonce_offre.'
				WHERE annonce = ?;');
			$sql->execute(array($v->id));
			$offre = $sql->fetchColumn();
			$v->url = $_pages_urls[$offre].'/'.$v->id.'-'.clean_str($v->titre);

			$v->str = $v->ref.' - '.$v->cp.' '.$v->nom_reel.' - '.$v->titre;

			$localisation = json_decode($v->localisation_label);
			$v->localisation = $localisation->fr;
		}
		return $res;
	}


	public function searchAnnoncesVille($str, $limit = 10) {
		global $_current_user;
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$wherecp = array();
		$whereville = array();
		$ordercpexact = array();
		$ordercpdebut = array();
		$ordercpmilieu = array();
		$ordervilleexact = array();
		$ordervilledebut = array();
		$ordervillemilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT DISTINCT '.$this->ville.'.id, '.$this->ville.'.nom_reel, '.$this->ville.'.cp
				FROM '.$this->ville.', '.$this->departement.', '.$this->region.', '.$this->annonce.'
				WHERE '.$this->ville.'.departement = '.$this->departement.'.id
				AND '.$this->departement.'.region = '.$this->region.'.id
				AND '.$this->annonce.'.ville = '.$this->ville.'.id
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute($params);
			return $sql->fetchAll(PDO::FETCH_FUNC, function($id, $nom, $cp) {
				$t = new stdClass;
				if(strlen($cp) > 11)
					$cp = preg_replace('/^([^-]+)-(.*)-([^-]+)$/', "$1-$3", $cp);
				$t->id = $id;
				$t->str = $nom.' '.$cp;
				$t->nom = $nom;
				return $t;
			});
		}
		
		$t = preg_split('/[\s]+/', $slug);
		array_unshift($t, $str);
		for($i = 0; $i < count($t); $i++) {
			if(is_numeric($t[$i])) {
				$select[] = '('.$this->ville.'.cp LIKE :cp_exact'.$i.') AS cp_exact'.$i.',
					('.$this->ville.'.cp LIKE :cp_debut'.$i.') AS cp_debut'.$i.',
					('.$this->ville.'.cp LIKE :cp_milieu'.$i.') AS cp_milieu'.$i;
				$params['cp_exact'.$i] = $t[$i];
				$params['cp_debut'.$i] = $t[$i].'%';
				$params['cp_milieu'.$i] = '%'.$t[$i].'%';
				$wherecp[] = ''.$this->ville.'.cp like :cp_milieu'.$i;
				$ordercpexact[strlen($t[$i])] = 'cp_exact'.$i.' DESC';
				$ordercpdebut[strlen($t[$i])] = 'cp_debut'.$i.' DESC';
				$ordercpmilieu[strlen($t[$i])] = 'cp_milieu'.$i.' DESC';
			}
			else if(!empty($t[$i])) {
				$select[] = 'ABS(CHAR_LENGTH(nom_reel) - '.strlen($t[$i]).') AS diff_length'.$i.',
					('.$this->ville.'.slug LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_exact'.$i.') AS nom_simple_exact'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_exact'.$i.') AS nom_reel_exact'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_exact'.$i.') AS soundex_exact'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_debut'.$i.') AS soundex_debut'.$i.',
					('.$this->ville.'.soundex LIKE :soundex_milieu'.$i.') AS soundex_milieu'.$i.',
					('.$this->ville.'.slug LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_debut'.$i.') AS nom_simple_debut'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_debut'.$i.') AS nom_reel_debut'.$i.',
					('.$this->ville.'.slug LIKE :slug_milieu'.$i.') AS slug_milieu'.$i.',
					('.$this->ville.'.nom_simple LIKE :slug_milieu'.$i.') AS nom_simple_milieu'.$i.',
					('.$this->ville.'.nom_reel LIKE :slug_milieu'.$i.') AS nom_reel_milieu'.$i;
				$params['slug_exact'.$i] = $t[$i];
				$params['slug_debut'.$i] = $t[$i].'%';
				$params['slug_milieu'.$i] = '%'.$t[$i].'%';
				$soundex = soundex($t[$i]);
				$params['soundex_exact'.$i] = $soundex;
				$params['soundex_debut'.$i] = $soundex.'%';
				$params['soundex_milieu'.$i] = '%'.$soundex.'%';
				$whereville[] = '('.$this->ville.'.slug LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.nom_simple LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.nom_reel LIKE :slug_milieu'.$i.' OR
				'.$this->ville.'.soundex LIKE :soundex_milieu'.$i.')';
				$ordervilleexact[strlen($t[$i])] = 'nom_reel_exact'.$i.' DESC, nom_simple_exact'.$i.' DESC, slug_exact'.$i.' DESC';
				$ordervilledebut[strlen($t[$i])] = 'nom_reel_debut'.$i.' DESC, nom_simple_debut'.$i.' DESC, slug_debut'.$i.' DESC, soundex_exact'.$i.' DESC, soundex_debut'.$i.' DESC, diff_length'.$i;
				$ordervillemilieu[strlen($t[$i])] = 'nom_reel_milieu'.$i.' DESC, nom_simple_milieu'.$i.' DESC, slug_milieu'.$i.' DESC, soundex_milieu'.$i.' DESC';
			}
		}

		if(empty($select))
			return array();

		krsort($ordercpexact);
		krsort($ordercpdebut);
		krsort($ordercpmilieu);
		krsort($ordervilleexact);
		krsort($ordervilledebut);
		krsort($ordervillemilieu);

		$sql = $this->db->prepare('SELECT DISTINCT '.$this->ville.'.id, '.$this->ville.'.nom_reel, '.$this->ville.'.cp,
			'.implode(', ', $select).'
			FROM '.$this->ville.', '.$this->departement.', '.$this->region.', '.$this->annonce.'
			WHERE '.$this->ville.'.departement = '.$this->departement.'.id
			AND '.$this->departement.'.region = '.$this->region.'.id
			AND '.$this->annonce.'.ville = '.$this->ville.'.id
			'.(!empty($wherecp) ? 'AND ('.implode(' OR ', $wherecp).')' : '').'
			'.(!empty($whereville) ? 'AND ('.implode(' OR ', $whereville).')' : '').'
			ORDER BY '.implode(', ', array_merge($ordercpexact, $ordervilleexact, $ordercpdebut, $ordervilledebut, $ordercpmilieu, $ordervillemilieu)).'
			'.($limit != -1 ? 'LIMIT '.$limit : '').';');
		$sql->execute($params);
		return $sql->fetchAll(PDO::FETCH_FUNC, function($id, $nom, $cp) {
			$t = new stdClass;
			if(strlen($cp) > 11)
				$cp = preg_replace('/^([^-]+)-(.*)-([^-]+)$/', "$1-$3", $cp);
			$t->id = $id;
			$t->str = $nom.' '.$cp;
			$t->nom = $nom;
			return $t;
		});
	}


	public function searchAnnoncesPrix($str) {
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.prix
				FROM '.$this->annonce.'
				LIMIT 10;');
			$sql->execute();
			$res = $sql->fetchAll();
			foreach($res as &$r)
				$r->prix = number_format($r->prix, floor($r->prix) == $r->prix ? 0 : 2, ',', ' ');
			return $res;
		}
		else {
			$t = preg_split('/[\s]+/', $slug);
			array_unshift($t, $str);
			for($i = 0; $i < count($t); $i++) {
				if(!empty($t[$i])) {
					$select[] = 'ABS(CHAR_LENGTH(prix) - '.strlen($t[$i]).') AS diff_length'.$i.',
						('.$this->annonce.'.prix LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
						('.$this->annonce.'.prix LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
						('.$this->annonce.'.prix LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
					$params['slug_exact'.$i] = $t[$i];
					$params['slug_debut'.$i] = $t[$i].'%';
					$params['slug_milieu'.$i] = '%'.$t[$i].'%';
					$where[] = '('.$this->annonce.'.prix LIKE :slug_milieu'.$i.')';
					$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
					$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC, diff_length'.$i;
					$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
				}
			}

			if(empty($select))
				return array();

			krsort($orderexact);
			krsort($orderdebut);
			krsort($ordermilieu);

			$sql = $this->db->prepare('SELECT '.$this->annonce.'.prix,
				'.implode(', ', $select).'
				FROM '.$this->annonce.'
				'.(!empty($where) ? 'WHERE ('.implode(' OR ', $where).')' : '').'
				ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
				LIMIT 10;');
			$sql->execute($params);
		}
		$res = $sql->fetchAll();
		foreach($res as &$r)
			$r->prix = number_format($r->prix, floor($r->prix) == $r->prix ? 0 : 2, ',', ' ');
		return $res;
	}


	public function searchTitres($str, $limit = 10) {
		global $_LANGS, $_current_user;
		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.id, '.$this->annonce.'.titre
				FROM '.$this->annonce.'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			$return = $sql->fetchAll();
			for($i = 0; $i < count($return); $i++)
				$return[$i]->titre = json_decode($return[$i]->titre)->{_LANG_DEFAULT};
			return $return;
		}
		else {
			$tt = preg_split('/[\s]+/', $slug);
			$tt_sav = $tt;
			$loop1 = count($tt);
			if($loop1 > 1) {
				for($k = 1; $k < $loop1; $k++) {
					$t[] = implode(' ', $tt);
					$loop2 = count($tt);
					$tt_cp = $tt;
					for($i = 2; $i < $loop2; $i++) {
						array_pop($tt_cp);
						$t[] = implode(' ', $tt_cp);
					}
					array_shift($tt);
				}
				$list = array_multisort(array_map('strlen', $tt_sav), SORT_DESC, $tt_sav);
				$i = 0;
				$continue = true;
				while($continue && $i < count($tt_sav)) {
					if(strlen($tt_sav[$i]) >= 5)
						$t[] = $tt_sav[$i];
					else
						$continue = false;
					$i++;
				}
			}
			else
				$t = array($slug);
			for($i = 0; $i < count($t); $i++) {
				if(!empty($t[$i])) {
					$select[] = '('.$this->annonce.'.slug LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
						('.$this->annonce.'.slug LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
						('.$this->annonce.'.slug LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
					$params['slug_exact'.$i] = $t[$i];
					$params['slug_debut'.$i] = $t[$i].'%';
					$params['slug_milieu'.$i] = '%'.$t[$i].'%';
					$where[] = '('.$this->annonce.'.slug LIKE :slug_milieu'.$i.')';
					$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
					$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC';
					$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
				}
			}

			if(empty($select))
				return array();

			krsort($orderexact);
			krsort($orderdebut);
			krsort($ordermilieu);

			$sql = $this->db->prepare('SELECT '.$this->annonce.'.id, '.$this->annonce.'.titre,
				'.implode(', ', $select).'
				FROM '.$this->annonce.'
				'.(!empty($where) ? 'WHERE ('.implode(' OR ', $where).')' : '').'
				ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute($params);
		}
		$return = $sql->fetchAll();
		for($i = 0; $i < count($return); $i++)
			$return[$i]->titre = json_decode($return[$i]->titre)->{_LANG_DEFAULT};
		return $return;
	}


	public function getAllAnnonces($sort = 'titre') {
		$sql = $this->db->prepare('SELECT id, titre
			FROM '.$this->annonce.'
			ORDER BY '.addslashes($sort).';');
		$sql->execute();
		return $sql->fetchAll();
	}


	public function getAnnonces($sort = 'date DESC', $vendus = false, $pagekey = 'p') {
		global $_current_user;

		$where = array();
		$params = array();

		//filtres annonces
		if(!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$where[] = $this->annonce.'.id = ?';
			$params[] = $_GET['id'];
		}
		else if(!empty($_GET['s_id'])) {
			$t = $this->searchAnnonceIds($_GET['s_id'], -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->id;
			if(!empty($a))
				$where[] = $this->annonce.'.id IN ('.implode(',', $a).')';
		}

		if(!empty($_GET['ref']) && is_numeric($_GET['ref'])) {
			$where[] = $this->annonce.'.ref = ?';
			$params[] = $_GET['ref'];
		}
		else if(!empty($_GET['s_ref'])) {
			$t = $this->searchAnnonceRefs($_GET['s_ref'], -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->ref;
			if(!empty($a))
				$where[] = $this->annonce.'.ref IN ("'.implode('","', $a).'")';
		}

		if(!empty($_GET['s_titre'])) {
			$t = $this->searchTitres($_GET['s_titre'], -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->id;
			if(!empty($a))
				$where[] = $this->annonce.'.id IN ('.implode(',', $a).')';
		}

		if(!empty($_GET['ville']) && is_numeric($_GET['ville'])) {
			$where[] = $this->annonce.'.ville = ?';
			$params[] = $_GET['ville'];
		}
		else if(!empty($_GET['s_ville'])) {
			if(preg_match('/(\d+)/', trim($_GET['s_ville']), $matches)) {
				$where[] = $this->annonce.'.cp LIKE "%'.$matches[1].'%"';
			}
			else {
				$t = $this->searchAnnoncesVille($_GET['s_ville'], -1);
				$a = array();
				for($i = 0; $i < count($t); $i++)
					$a[] = $t[$i]->id;
				if(!empty($a))
					$where[] = $this->annonce.'.ville IN ('.implode(',', $a).')';
			}
		}
		
		$return = new stdClass();
		$sql = $this->db->prepare('SELECT COUNT('.$this->annonce.'.id)
			FROM '.$this->annonce.'
			WHERE '.$this->annonce.'.date_vente IS '.(!empty($vendus) ? 'NOT ' : '').'NULL
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').';');
		$sql->execute($params);
		$total = $sql->fetchColumn();
		$page_max = ceil($total / _NB_PAR_PAGE);
		if($page_max == 0)
			$page_max = 1;
		$page = !empty($_GET[$pagekey]) && is_numeric($_GET[$pagekey]) ? $_GET[$pagekey] : 1;
		if($page > $page_max)
			$page = $page_max;
		$min = ($page - 1) * _NB_PAR_PAGE;

		$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel, '.$this->type.'.label AS type_label, '.$this->equipe.'.nom AS equipe_nom, '
            .$this->equipe.'.prenom AS equipe_prenom
			FROM '.$this->type.', '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			LEFT JOIN '.$this->equipe.' ON '.$this->annonce.'.equipe = '.$this->equipe.'.id
			WHERE '.$this->annonce.'.type = '.$this->type.'.id
			AND '.$this->annonce.'.date_vente IS '.(!empty($vendus) ? 'NOT ' : '').'NULL
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').'
			ORDER BY '.addslashes($sort).', '.$this->annonce.'.id DESC
			LIMIT '._NB_PAR_PAGE.' OFFSET '.$min.';');
		$sql->execute($params);
		$return->annonces = $sql->fetchAll();
		$return->total = $total;
		$return->page_max = $page_max;
		$return->page = $page;
		return $return;
	}


	public function getAnnonce($id) {
		global $_current_user;

		$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel AS ville_nom
			FROM '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			WHERE '.$this->annonce.'.id = ?;');
		$sql->execute(array($id));
		if($res = $sql->fetch()) {
			return $res;
		}
		else
			return false;
	}
    public function getEquipe($id){
        $sql = $this->db->prepare('SELECT *
			FROM '.$this->equipe.'
			WHERE '.$this->equipe.'.id = ?;');
        $sql->execute(array($id));
        if($res = $sql->fetch()) {
            return $res;
        }
        else
            return false;
    }

	public function switchAnnonce($id) {
		global $_current_user;

		if(!($p = $this->getAnnonce($id)))
			return false;
		$active = $p->active == 1 ? 0 : 1;
		$sql = $this->db->prepare('UPDATE '.$this->annonce.'
			SET active = ?
			WHERE id = ?;');
		$sql->execute(array($active, $id));
		historique_write('ANNONCE'.$id.'ADMIN'.$_current_user->id, ($active == 1 ? 'Activation' : 'Désactivation').' annonce', array('description' => ($active == 1 ? 'Activation' : 'Désactivation').' annonce depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
		return array('active' => $active);
	}


	public function featureAnnonce($id) {
		if(!($p = $this->getAnnonce($id)))
			return false;
		$return = array();
		$sql = $this->db->prepare('SELECT annonce FROM '.$this->agence_annonce_featured.'
			WHERE agence = ?;');
		$sql->execute(array($p->agence));
		if($res = $sql->fetchColumn()) {
			$return[] = array('id' => $res, 'active' => 0);
			$sql = $this->db->prepare('DELETE FROM '.$this->agence_annonce_featured.'
				WHERE agence = ?;');
			$sql->execute(array($p->agence));
		}
		if(empty($p->featured)) {
			$sql = $this->db->prepare('INSERT INTO '.$this->agence_annonce_featured.' VALUES (?, ?);');
			$sql->execute(array($p->agence, $id));
			$return[] = array('id' => $id, 'active' => 1);
		}
		return $return;
	}


	public function checkPostAnnonce($p) {
		global $_LANGS, $_current_user;
		$error = array();
		if(empty($p['titre'][_LANG_DEFAULT]))
			$error[] = 'Le champ "Titre '.printLangTag(_LANG_DEFAULT).'" n\'est pas renseigné.';
		if($p['ref'] == '')
			$error[] = 'Le champ "Ref" n\'est pas renseigné.';
		if(empty($p['type']))
			$error[] = 'Le champ "Type" n\'est pas renseigné.';
		if(empty($p['images']))
			$error[] = 'Il n\'y a aucune photo.';
		if($p['superficie'] != '' && !is_numeric($p['superficie']))
			$error[] = 'Le champ "Superficie" n\'est pas numérique.';
		if($p['prix'] != '' && !is_numeric($p['prix']))
			$error[] = 'Le champ "Prix" n\'est pas numérique.';
		return $error;
	}


	public function makeAnnonceSlug($p) {
		global $_LANGS;
		$slug = array();
		
		$sql = $this->db->prepare('SELECT label FROM '.$this->type.' WHERE id = ?;');
		$sql->execute(array($p['type']));
		$type = json_decode($sql->fetchColumn());
		
		if(!empty($p['ville'])) {
			$sql = $this->db->prepare('SELECT slug FROM '.$this->ville.' WHERE id = ?;');
			$sql->execute(array($p['ville']));
			$ville = $sql->fetchColumn();
		}
		else
			$ville = '';
		
		foreach($_LANGS as $l => $ll) {
			$slug[] = $type->{$l};
			$slug[] = $ville;
			$slug[] = $p['cp'];
			if($p['superficie'] != '')
				$slug[] = $p['superficie'].'m2';
			$slug[] = $p['titre'][$l];
		}
		return utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode(implode(' ', $slug))),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'))));
	}


	public function addAnnonce($p) {
		global $_LANGS, $_current_user;
		$error = $this->checkPostAnnonce($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}

		if(!empty($p['vente'])) {
			if(!empty($p['date_vente']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_vente']))
				$date_vente = $date->format('Y-m-d');
			else
				$date_vente = date('Y-m-d');
		}
		else
			$date_vente = NULL;

        if(!empty($p['isDispo'])) {
            if(!empty($p['date_dispo']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_dispo']))
                $date_dispo = $date->format('Y-m-d');
            else
                $date_dispo = date('Y-m-d');
        }
        else
            $date_dispo = NULL;

		$params = array(
			null,
			$p['ref'],
			date('Y-m-d H:i:s'),
			$date_vente,
			$p['type'],
			json_encode($p['titre']),
			$p['superficie'] != '' ? $p['superficie'] : NULL,
			$p['adresse'],
			$p['cp'],
			$p['ville'] != '' ? $p['ville'] : NULL,
			$p['lat'] != '' ? $p['lat'] : NULL,
			$p['lng'] != '' ? $p['lng'] : NULL,
			$p['prix'] != '' ? $p['prix'] : NULL,
			'',
			json_encode($p['data']),
			!empty($p['active']) ? 1 : 0,
			$this->makeAnnonceSlug($p),
			0,
            $p['equipe'],
            $p['pieces'],
            $p['isLocation'],
            $p['isDispo'],
            $date_dispo
		);
		$sql = $this->db->prepare('INSERT INTO '.$this->annonce.'
			VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
		if($sql->execute($params)) {
			$id = $this->db->lastInsertId();

			$sql = $this->db->prepare('UPDATE '.$this->annonce.'
				SET ordre = ordre + 1
				WHERE 1;');
			$sql->execute();
			
			//si images -> traitement
			$images = array();
			if(!empty($p['images'])) {
				require_once _DIR_LIB.'phpthumb/ThumbLib.inc.php';
				umask(0002);
				//chemins
				$_dir_annonce = '../'.$this->getAnnonceDirImg($id);
				//creation dossier
				mkdir($_dir_annonce, 0775, true);
				//transfert des fichiers
				$old_dir = array();
				for($i = 0; $i < count($p['images']); $i++) {
					rename($p['images'][$i], $_dir_annonce.$i.'.jpg');
					$t = pathinfo($p['images'][$i]);
					if(!in_array($t['dirname'], $old_dir))
						$old_dir[] = $t['dirname'];
					$img = $i.'.jpg';
					foreach(array(
						array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
						array(_IMG_MD_WIDTH, _IMG_MD_HEIGHT, 'md'),
						array(_IMG_LG_WIDTH, _IMG_LG_HEIGHT, 'lg')
					) as $dim) {
						$im = PhpThumbFactory::create($_dir_annonce.$img);
						$im->adaptiveResize($dim[0], $dim[1]);
						$im->save($_dir_annonce.$dim[2].'_'.$img);
					}
					$im = PhpThumbFactory::create($_dir_annonce.$img);
					$im->save($_dir_annonce.$img);
					$t = new stdClass();
					$t->image = $img;
					$t->legende = new stdClass();
					foreach($_LANGS as $l => $ll)
						$t->legende->{$l} = $p['images_legend'][$l][$i];
					$images[] = $t;
				}
				foreach($old_dir as $v) {
					$this->delTree($v);
				}
			}
			$sql = $this->db->prepare('UPDATE '.$this->annonce.'
				SET images = ?
				WHERE id = ?;');
			$sql->execute(array(json_encode($images), $id));

			
			throwAlert('success', '', '<p>Le bien <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong> a bien été ajouté.</p>');
			historique_write('BIEN'.$id.'ADMIN'.$_current_user->id, 'Ajout bien', array('description' => 'Ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));
			return true;
		}
		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création du bien <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('ADMIN'.$_current_user->id, 'Erreur ajout bien', array('description' => 'Erreur ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}


	public function modifyAnnonce($p) {
		global $_LANGS, $_current_user;
		if(!($annonce = $this->getAnnonce($p['action_modify']))) {
			throwAlert('danger', 'Erreur', 'Ce bien n\'existe pas.');
			return false;
		}
		$error = $this->checkPostAnnonce($p);
		if(!empty($error)) {
			throwAlert('danger', 'Erreur', '<p>'.implode('</p><p>', $error).'</p>');
			return false;
		}

		//traitement images
		require_once _DIR_LIB.'phpthumb/ThumbLib.inc.php';
		umask(0002);
		//chemins
		$_dir_annonce = '../'.$this->getAnnonceDirImg($annonce->id);
		//creation dossier
		if(!is_dir($_dir_annonce))
			mkdir($_dir_annonce, 0775, true);
		
		//anciennes images
		$annonce->images = json_decode($annonce->images);
		$old_images = array();
		foreach($annonce->images as $v)
			$old_images[] = $v->image;

		//nouvel index de début pour les nouvelles images
		$index = 0;
		if(!empty($old_images)) {
			for($i = 0; $i < count($old_images); $i++) {
				if(preg_match('/^(\d+)\.jpg(.*)$/', $old_images[$i], $matches)) {
					if($matches[1] + 1 > $index)
						$index = $matches[1] + 1;
				}
			}
		}

		//suppression des anciennes images inutilisées
		foreach($old_images as $v) {
			if(!in_array($v, $p['images'])) {
				$file = preg_replace('/\.jpg(.*)$/', '.jpg', $v);
				unlink($_dir_annonce.$file);
				unlink($_dir_annonce.'sm_'.$file);
				unlink($_dir_annonce.'md_'.$file);
				unlink($_dir_annonce.'lg_'.$file);
			}
		}
		//renommer + déplacer images selon nouvel index
		$new_images = array();
		$old_dir = array();
		for($i = 0; $i < count($p['images']); $i++) {
			//si image déjà présente
			if(basename($p['images'][$i]) == $p['images'][$i]) {
				$new_name = preg_replace('/\.jpg(.*)$/', '.jpg', $p['images'][$i]);
				/*rename($_dir_annonce.$p['images'][$i], $_dir_annonce.$new_name);
				rename($_dir_annonce.'sm_'.$p['images'][$i], $_dir_annonce.'sm_'.$new_name);
				rename($_dir_annonce.'md_'.$p['images'][$i], $_dir_annonce.'md_'.$new_name);
				rename($_dir_annonce.'lg_'.$p['images'][$i], $_dir_annonce.'lg_'.$new_name);*/
			}
			else {	//si nouvelle image
				$new_name = $index.'.jpg';
				rename($p['images'][$i], $_dir_annonce.$new_name);
				$t = pathinfo($p['images'][$i]);
				if(!in_array($t['dirname'], $old_dir))
					$old_dir[] = $t['dirname'];
				foreach(array(
					array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
					array(_IMG_MD_WIDTH, _IMG_MD_HEIGHT, 'md'),
					array(_IMG_LG_WIDTH, _IMG_LG_HEIGHT, 'lg')
				) as $dim) {
					$im = PhpThumbFactory::create($_dir_annonce.$new_name);
					$im->adaptiveResize($dim[0], $dim[1]);
					$im->save($_dir_annonce.$dim[2].'_'.$new_name);
				}
				$im = PhpThumbFactory::create($_dir_annonce.$new_name);
				$im->save($_dir_annonce.$new_name);
				$index++;
			}
			$t = new stdClass();
			$t->image = $new_name.'?'.time();
			$t->legende = new stdClass();
			foreach($_LANGS as $l => $ll)
				$t->legende->{$l} = $p['images_legend'][$l][$i];
			$new_images[] = $t;
		}
		foreach($old_dir as $v) {
			$this->delTree($v);
		}

		//vente
		if(!empty($p['vente'])) {
			if(!empty($p['date_vente']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_vente']))
				$date_vente = $date->format('Y-m-d');
			else
				$date_vente = date('Y-m-d');
		}
		else
			$date_vente = NULL;

        if(!empty($p['isDispo'])) {
            if(!empty($p['date_dispo']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_dispo']))
                $date_dispo = $date->format('Y-m-d');
            else
                $date_dispo = date('Y-m-d');
        }
        else
            $date_dispo = NULL;

        //paramètres de modif db
		$params = array(
			'ref' => $p['ref'],
			'date_vente' => $date_vente,
			'type' => $p['type'],
			'titre' => json_encode($p['titre']),
			'superficie' => $p['superficie'] != '' ? $p['superficie'] : NULL,
			'adresse' => $p['adresse'],
			'cp' => $p['cp'],
			'ville' => $p['ville'] != '' ? $p['ville'] : NULL,
			'lat' => $p['lat'] != '' ? $p['lat'] : NULL,
			'lng' => $p['lng'] != '' ? $p['lng'] : NULL,
			'prix' => $p['prix'] != '' ? $p['prix'] : NULL,
			'images' => json_encode($new_images),
			'data' => json_encode($p['data']),
			'active' => !empty($p['active']) ? 1 : 0,
			'slug' => $this->makeAnnonceSlug($p),
            'equipe'=> $p['equipe'],
            'pieces'=>$p['pieces'],
            'isLocation' => $p['isLocation'],
            'isDispo' => $p['isDispo'],
            'date_dispo' => $date_dispo
		);
		$sql = $this->db->prepare('UPDATE '.$this->annonce.'
			SET '.implode(' = ?, ', array_keys($params)).' = ?
			WHERE id = ?;');

		if( $sql->execute(array_merge(array_values($params), array($annonce->id))) ) {			
			throwAlert('success', '', '<p>Le bien <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong> a bien été modifié.</p>');
			historique_write('BIEN'.$annonce->id.'ADMIN'.$_current_user->id, 'Modification bien', array('description' => 'Modification bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'POST' => cleanPost($p)));

            if ($p['isDispo'] == 1) {
                $sql = $this->db->prepare('SELECT email 
			    FROM ' . $this->avertir . '
		    	WHERE bien = ? ');
                $sql->execute(array($annonce->id));
                $emails = $sql->fetchAll(PDO::FETCH_COLUMN);
                $id = $annonce->id;
                foreach ($emails as $email) {
                    $_POST['email'] = $email;
                    triggerHookMail('AVERTIR', $admin = null, $client = null, $annonce = $id, $vars = array());
                }
            }
            return true;

		}

		else {
			$e = $this->db->errorInfo();
			throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification du bien <strong>"'.$p['titre'][_LANG_DEFAULT].'"</strong>.</p>'.(_DEBUG ? '<p>'.$e[2].'</p>' : ''));
			historique_write('BIEN'.$annonce->id.'ADMIN'.$_current_user->id, 'Erreur modification bien', array('description' => 'Erreur modification bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')', 'error' => $e, 'POST' => cleanPost($p)));
			return false;
		}
	}


	public function deleteAnnonce($id) {
		global $_current_user;

		if($p = $this->getAnnonce($id)) {
			foreach(json_decode($p->images) as $img) {
				$dir = '../'.$this->getAnnonceDirImg($p->id);
				$file = preg_replace('/\.jpg(.*)$/', '.jpg', $img->image);
				unlink($dir.$file);
				unlink($dir.'lg_'.$file);
				unlink($dir.'md_'.$file);
				unlink($dir.'sm_'.$file);
			}
			foreach(json_decode($p->videos) as $video) {
				$dir = '../'.$this->getAnnonceDirVideo($p->id);
				$file = preg_replace('/\.mp4(.*)$/', '.mp4', $video->video);
				unlink($dir.$file);
			}
			$sql = $this->db->prepare('DELETE FROM '.$this->annonce.'
				WHERE id = ?;');
			$sql->execute(array($id));

			$sql = $this->db->prepare('UPDATE '.$this->annonce.'
				SET ordre = ordre - 1
				WHERE ordre > ?;');
			$sql->execute(array($p->ordre));
			
			throwAlert('success', '', '<p>Le bien <strong>"'.$p->ref.'"</strong> a bien été supprimé.</p>');
			historique_write('BIEN'.$id.'ADMIN'.$_current_user->id, 'Suppression bien', array('description' => 'Suppression bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id.' ('.$_current_user->prenom.' '.$_current_user->nom.')'));
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Ce bien n\'existe pas.');
			return false;
		}
	}


	public function changePositionAnnonces($id, $direction, $sort = 'date DESC', $vendus = false) {
		global $_current_user;

		$where = array();
		$params = array();

		//filtres annonces
		if(!empty($_GET['id']) && is_numeric($_GET['id'])) {
			$where[] = $this->annonce.'.id = ?';
			$params[] = $_GET['id'];
		}
		else if(!empty($_GET['s_id'])) {
			$t = $this->searchAnnonceIds($_GET['s_id'], $vendus, -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->id;
			if(!empty($a))
				$where[] = $this->annonce.'.id IN ('.implode(',', $a).')';
		}

		if(!empty($_GET['ref']) && is_numeric($_GET['ref'])) {
			$where[] = $this->annonce.'.ref = ?';
			$params[] = $_GET['ref'];
		}
		else if(!empty($_GET['s_ref'])) {
			$t = $this->searchAnnonceRefs($_GET['s_ref'], $vendus, -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->ref;
			if(!empty($a))
				$where[] = $this->annonce.'.ref IN ("'.implode('","', $a).'")';
		}

		if(!empty($_GET['s_titre'])) {
			$t = $this->searchTitres($_GET['s_titre'], $vendus, -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->id;
			if(!empty($a))
				$where[] = $this->annonce.'.id IN ('.implode(',', $a).')';
		}

		if(!empty($_GET['ville']) && is_numeric($_GET['ville'])) {
			$where[] = $this->annonce.'.ville = ?';
			$params[] = $_GET['ville'];
		}
		else if(!empty($_GET['s_ville'])) {
			$t = $this->searchAnnoncesVille($_GET['s_ville'], $vendus, -1);
			$a = array();
			for($i = 0; $i < count($t); $i++)
				$a[] = $t[$i]->id;
			if(!empty($a))
				$where[] = $this->annonce.'.ville IN ('.implode(',', $a).')';
		}
		
		$sql = $this->db->prepare('SELECT '.$this->annonce.'.id, ordre
			FROM '.$this->annonce.'
			WHERE '.$this->annonce.'.date_vente IS '.(!empty($vendus) ? 'NOT ' : '').'NULL
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').'
			ORDER BY '.addslashes($sort).', '.$this->annonce.'.id DESC;');
		$sql->execute($params);
		$res = $sql->fetchAll();
		$ids = array();
		$ordres = array();
		for($i = 0; $i < count($res); $i++) {
			$ids[] = $res[$i]->id;
			$ordres[] = $res[$i]->ordre;
		}
		$position = array_search($id, $ids);
		if($position === false)
			return 'Bien ID invalide';
		if($position == 0 && $direction == 'up')
			return 'Ce bien est déjà en haut de la liste';
		if($position == (count($ids) - 1) && $direction == 'down')
			return 'Ce bien est déjà en bas de la liste';
		$other_id = $direction == 'up' ? $ids[$position - 1] : $ids[$position + 1];
		$ordre = $ordres[$position];
		$other_ordre = $direction == 'up' ? $ordres[$position - 1] : $ordres[$position + 1];
		$sql = $this->db->prepare('UPDATE '.$this->annonce.' SET ordre = ? WHERE id = ?;');
		$sql->execute(array($other_ordre, $id));
		$sql = $this->db->prepare('UPDATE '.$this->annonce.' SET ordre = ? WHERE id = ?;');
		$sql->execute(array($ordre, $other_id));
	}


	public function addTags($tags, $type) {
		global $_LANGS;
		foreach($tags as $v) {
			foreach($_LANGS as $l => $ll) {
				$sql = $this->db->prepare('SELECT id FROM '.$this->tag.'
					WHERE type = ?
					AND langue = ?
					AND label = ?;');
				$sql->execute(array($type, $l, $v->label->{$l}));
				$tag = $sql->fetchColumn();
				if(!empty($tag)) {
					$sql = $this->db->prepare('UPDATE '.$this->tag.'
						SET utilisations = utilisations + 1
						WHERE id = ?;');
					$sql->execute(array($tag));
				}
				else {
					$params = array(
						null,
						$v->label->{$l},
						$l,
						$type,
						1
					);
					$sql = $this->db->prepare('INSERT INTO '.$this->tag.'
						VALUES ('.implode(',', array_fill(0, count($params), '?')).');');
					$sql->execute($params);
					$tag = $this->db->lastInsertId();
				}
				//insertion valeur
				if(isset($v->valeur->{$l})) {
					$sql = $this->db->prepare('SELECT id
						FROM '.$this->tag_valeur.'
						WHERE tag = ?
						AND label = ?;');
					$sql->execute(array($tag, $v->valeur->{$l}));
					if($id = $sql->fetchColumn()) {
						$sql = $this->db->prepare('UPDATE '.$this->tag_valeur.'
							SET utilisations = utilisations + 1
							WHERE id = ?;');
						$sql->execute(array($id));
					}
					else {
						$sql = $this->db->prepare('INSERT INTO '.$this->tag_valeur.'
							VALUES(?, ?, ?, ?);');
						$sql->execute(array('', $tag, $v->valeur->{$l}, 1));
					}
				}
			}
		}
	}


	public function removeTags($tags, $type) {
		global $_LANGS;
		foreach($tags as $v) {
			foreach($_LANGS as $l => $ll) {
				$sql = $this->db->prepare('SELECT id FROM '.$this->tag.'
					WHERE type = ?
					AND langue = ?
					AND label = ?;');
				$sql->execute(array($type, $l, $v->label->{$l}));
				$tag = $sql->fetchColumn();
				if(!empty($tag)) {
					$sql = $this->db->prepare('UPDATE '.$this->tag.'
						SET utilisations = utilisations - 1
						WHERE id = ?;');
					$sql->execute(array($tag));
					//supression valeur associée
					if(isset($v->valeur->{$l})) {
						$sql = $this->db->prepare('UPDATE '.$this->tag_valeur.'
							SET utilisations = utilisations - 1
							WHERE tag = ? AND label = ?;');
						$sql->execute(array($tag, $v->valeur->{$l}));
					}
				}
			}
		}
		$sql = $this->db->prepare('DELETE FROM '.$this->tag.'
			WHERE utilisations <= 0;');
		$sql->execute();
		$sql = $this->db->prepare('DELETE FROM '.$this->tag_valeur.'
			WHERE utilisations <= 0;');
		$sql->execute();
	}


	public function make_all_tags() {
		global $_LANGS;
		$sql = $this->db->prepare('SELECT data FROM '.$this->annonce.';');
		$sql->execute();
		$res = $sql->fetchAll(PDO::FETCH_COLUMN);
		foreach($res as $v) {
			$data = json_decode($v);
			foreach(array('infos') as $type) {
				if(!empty($data->{$type})) {
					foreach($data->{$type} as $d) {
						foreach($_LANGS as $l => $ll) {
							$sql = $this->db->prepare('SELECT id FROM '.$this->tag.' WHERE type = ? AND langue = ? AND label = ? LIMIT 1;');
							$sql->execute(array($type, $l, $d->label->{$l}));
							if($id = $sql->fetchColumn()) {
								$sql = $this->db->prepare('SELECT id FROM '.$this->tag_valeur.' WHERE tag = ? AND label = ?;');
								$sql->execute(array($id, $d->valeur->{$l}));
								if($i = $sql->fetchColumn()) {
									$sql = $this->db->prepare('UPDATE '.$this->tag_valeur.'
										SET utilisations = utilisations + 1
										WHERE id = ?;');
									$sql->execute(array($i));
								}
								else {
									$sql = $this->db->prepare('INSERT INTO '.$this->tag_valeur.' VALUES(?, ?, ?, ?);');
									$sql->execute(array('', $id, $d->valeur->{$l}, 1));
								}
							}
						}
					}
				}
			}
		}
	}


	public function getVerrou($annonce) {
		$sql = $this->db->prepare('SELECT '.$this->annonce_verrou.'.token, '.$this->bo_user.'.*
			FROM '.$this->annonce_verrou.', '.$this->bo_user.'
			WHERE '.$this->annonce_verrou.'.user = '.$this->bo_user.'.id
			AND '.$this->annonce_verrou.'.annonce = ?;');
		$sql->execute(array($annonce));
		return $sql->fetch();
	}


	public function addVerrou($annonce, $token) {
		global $_current_user;
		$sql = $this->db->prepare('INSERT INTO '.$this->annonce_verrou.' VALUES (?, ?, ?);');
		$sql->execute(array($annonce, $_current_user->id, $token));
	}


	public function deleteVerrou($annonce, $token = null) {
		$sql = $this->db->prepare('DELETE FROM '.$this->annonce_verrou.'
			WHERE annonce = ?
			'.(!empty($token) ? 'AND token = ?' : '').';');
		if(!empty($token))
			$sql->execute(array($annonce, $token));
		else
			$sql->execute(array($annonce));
	}


	public function processAlerte($id) {
		$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel, '.$this->type.'.label AS type_label, '.$this->offre.'.label AS offre_label, '.$this->agence_localisation.'.nom AS localisation_label, '.$this->agence.'.url AS agence_url
			FROM '.$this->type.', '.$this->offre.', '.$this->agence_localisation.', '.$this->agence.', '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			WHERE '.$this->annonce.'.type = '.$this->type.'.id
			AND '.$this->annonce.'.offre = '.$this->offre.'.id
			AND '.$this->annonce.'.localisation = '.$this->agence_localisation.'.id
			AND '.$this->annonce.'.agence = '.$this->agence.'.id
			AND '.$this->annonce.'.active = 1
			AND '.$this->annonce.'.date_vente IS NULL
			AND '.$this->annonce.'.id = ?;');
		$sql->execute(array($id));
		if(!($annonce = $sql->fetch()))
			return false;

		$t = json_decode($annonce->titre);
		$titre['fr'] = $t->fr;
		$titre['en'] = $t->en;
		$url['fr'] = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$annonce->agence_url.'/'.($annonce->offre == 1 ? 'vente' : 'location').'/'.$annonce->id.'-'.clean_str($titre['fr']);
		$url['en'] = _PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.$annonce->agence_url.'/'.($annonce->offre == 1 ? 'vente' : 'location').'/'.$annonce->id.'-'.clean_str($titre['en']);
		$images = json_decode($annonce->images);
		$d = json_decode($annonce->data);
		if($annonce->nom_reel == 'Paris' && !empty($annonce->localisation_label))
			$localisation = preg_replace('/^\d+ - /', '', $annonce->localisation_label);
		else if(!empty($annonce->nom_reel))
			$localisation = $annonce->nom_reel;
		else
			$localisation = $annonce->localisation_label;
		if(!is_null($annonce->prix) && (!isset($d->afficher_prix) || (isset($d->afficher_prix) && !empty($d->afficher_prix)))) {
			$prix['fr'] = number_format($annonce->prix, floor($annonce->prix) == $annonce->prix ? 0 : 2, ',', ' ').' €';
			$prix['en'] = $prix['fr'];
		}
		else {
			$prix['fr'] = 'Nous consulter';
			$prix['en'] = 'Contact us';
		}

		$message['fr'] = '<p>Bonjour [[nom]]</p>';
		$message['fr'] .= '<p>Ateliers Lofts &amp; Associés a repéré pour vous le bien suivant :</p>';
		$message['fr'] .= '<div><a href="'.$url['fr'].'"><img src="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT._DIR_IMG_ANNONCE.preg_replace('/(\d{1})/', '$1/', $annonce->id).'md_'.$images[0]->image.'" style="max-width:100%; display:block; margin-bottom:7px;" width="500"><span style="display:block; text-transform:uppercase; font-weight:bold; font-size:15px; margin-bottom:5px;">'.$titre['fr'].'</a></div>';
		$message['fr'] .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:500px; font-size:12px;"><tr>';
		$message['fr'] .= '<td style="font-weight:bold; text-transform:uppercase;">'.$localisation.'</td>';
		$message['fr'] .= '<td style="font-weight:bold; text-align:center">'.$annonce->superficie.'m<sup>2</sup></td>';
		$message['fr'] .= '<td style="font-weight:bold; text-transform:uppercase; text-align:right;">';
		$message['fr'] .=  $prix['fr'];
		$message['fr'] .= '</tr></table>';
		$message['fr'] .= '<p>&nbsp;</p>';
		$message['fr'] .= '<p style="text-align:center;"><a href="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.'?delete_alerte=[[id]]&email=[[email]]">Supprimer cette alerte email</a></p>';

		$message['en'] = '<p>Hello [[nom]]</p>';
		$message['en'] .= '<p>Ateliers Lofts &amp; Associés has identified the following good for you :</p>';
		$message['en'] .= '<div><a href="'.$url['en'].'"><img src="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT._DIR_IMG_ANNONCE.preg_replace('/(\d{1})/', '$1/', $annonce->id).'md_'.$images[0]->image.'" style="max-width:100%; display:block; margin-bottom:7px;" width="500"><span style="display:block; text-transform:uppercase; font-weight:bold; font-size:15px; margin-bottom:5px;">'.$titre['en'].'</a></div>';
		$message['en'] .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:500px; font-size:12px;"><tr>';
		$message['en'] .= '<td style="font-weight:bold; text-transform:uppercase;">'.$localisation.'</td>';
		$message['en'] .= '<td style="font-weight:bold; text-align:center">'.$annonce->superficie.'m<sup>2</sup></td>';
		$message['en'] .= '<td style="font-weight:bold; text-transform:uppercase; text-align:right;">';
		$message['en'] .=  $prix['en'];
		$message['en'] .= '</tr></table>';
		$message['en'] .= '<p>&nbsp;</p>';
		$message['en'] .= '<p style="text-align:center;"><a href="'._PROTOCOL.$_SERVER['SERVER_NAME']._ROOT.'?delete_alerte=[[id]]&email=[[email]]">Delete this email alert</a></p>';

		$sql = $this->db->prepare('SELECT '.$this->contact_form.'.*, '.$this->contact.'.email, '.$this->contact.'.nom, '.$this->contact.'.prenom 
			FROM '.$this->contact_form.', '.$this->contact.'
			WHERE '.$this->contact_form.'.contact = '.$this->contact.'.id
			AND '.$this->contact_form.'.type = ?
			AND '.$this->contact_form.'.agence = ?;');
		$sql->execute(array('alerte', $annonce->agence));
		$res = $sql->fetchAll();
		foreach($res as $v) {
			$data = json_decode($v->data);
			if(empty($data->localisation) || $data->localisation == $annonce->localisation) {
				if(!empty($data->surface)) {
					$surface = str_replace(' ', '', $data->surface);
					if(preg_match('/^-(\d+)$/', $surface, $matches))
						$inf = $matches[1];
					else if(preg_match('/^\+(\d+)$/', $surface, $matches))
						$sup = $matches[1];
					else if(preg_match('/^(\d+)-(\d+)$/', $surface, $matches)) {
						$val1 = $matches[1];
						$val2 = $matches[2];
					}
				}
				else
					$surface = false;
				if(empty($surface) || (!empty($inf) && $annonce->superficie <= $inf) || (!empty($sup) && $annonce->superficie >= $sup) || (!empty($val1) && !empty($val2) && $annonce->superficie >= $val1 && $annonce->superficie <= $val2)) {
					if(!empty($data->budget)) {
						$budget = str_replace(' ', '', $data->budget);
						if(preg_match('/^-(\d+)$/', $budget, $matches))
							$inf = $matches[1];
						else if(preg_match('/^\+(\d+)$/', $budget, $matches))
							$sup = $matches[1];
						else if(preg_match('/^(\d+)-(\d+)$/', $budget, $matches)) {
							$val1 = $matches[1];
							$val2 = $matches[2];
						}
					}
					else
						$budget = false;
					if(empty($budget) || (!empty($inf) && $annonce->prix <= $inf) || (!empty($sup) && $annonce->prix >= $sup) || (!empty($val1) && !empty($val2) && $annonce->prix >= $val1 && $annonce->prix <= $val2)) {

						$lang = !empty($data->lang) ? $data->lang : _LANG_DEFAULT;
						$obj = $lang == 'en' ? 'Ateliers Lofts & Associés has identified real estate for you' : 'Ateliers Lofts & Associés a repéré pour vous un bien immobilier';
						$message = str_replace(array('[[nom]]', '[[id]]', '[[email]]'), array(escHtml($v->prenom.' '.$v->nom), $v->id, $v->email), $message[$lang]);
						send_mail($v->email, $obj, $message);

					}
				}

			}
		}
	}


	public function searchAnnoncesPage($str, $limit = 10) {
		global $_current_user;

		$str = !empty($str) ? trim($str) : '';
		$slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
		$select = array();
		$params = array();
		$where = array();
		$orderexact = array();
		$orderdebut = array();
		$ordermilieu = array();

		if(empty($str)) {
			$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->type.'.label AS type_label, '.$this->ville.'.nom_reel AS ville_nom
				FROM '.$this->type.', '.$this->annonce.'
				LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
				WHERE '.$this->annonce.'.type = '.$this->type.'.id
				AND '.$this->annonce.'.active = 1
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute();
			$res = $sql->fetchAll();
		}
		else {

			$search = 'CONCAT(' . $this->annonce.'.id, " ", ' . $this->annonce.'.ref, " ", ' . $this->annonce.'.slug)';
			
			$t = preg_split('/[\s]+/', $slug);
			array_unshift($t, $str);
			for($i = 0; $i < count($t); $i++) {
				if(!empty($t[$i])) {
					$select[] = 'ABS(CHAR_LENGTH('.$search.') - '.strlen($t[$i]).') AS diff_length'.$i.',
						('.$search.' LIKE :slug_exact'.$i.') AS slug_exact'.$i.',
						('.$search.' LIKE :slug_debut'.$i.') AS slug_debut'.$i.',
						('.$search.' LIKE :slug_milieu'.$i.') AS slug_milieu'.$i;
					$params['slug_exact'.$i] = $t[$i];
					$params['slug_debut'.$i] = $t[$i].'%';
					$params['slug_milieu'.$i] = '%'.$t[$i].'%';
					$where[] = '('.$search.' LIKE :slug_milieu'.$i.')';
					$orderexact[strlen($t[$i])] = 'slug_exact'.$i.' DESC';
					$orderdebut[strlen($t[$i])] = 'slug_debut'.$i.' DESC, diff_length'.$i;
					$ordermilieu[strlen($t[$i])] = 'slug_milieu'.$i.' DESC';
				}
			}

			if(empty($select))
				return array();

			krsort($orderexact);
			krsort($orderdebut);
			krsort($ordermilieu);

			$sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->type.'.label AS type_label, '.$this->ville.'.nom_reel AS ville_nom,
				'.implode(', ', $select).'
				FROM '.$this->type.', '.$this->annonce.'
				LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
				WHERE '.$this->annonce.'.type = '.$this->type.'.id
				AND '.$this->annonce.'.active = 1
				'.(!empty($where) ? 'AND ('.implode(' OR ', $where).')' : '').'
				ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
				'.($limit != -1 ? 'LIMIT '.$limit : '').';');
			$sql->execute($params);
			$res = $sql->fetchAll();

		}

		$return = array();
		foreach ($res as $v) {
			$line = new stdClass();
			$line->id = $v->id;
			$line->ref = $v->ref;
			$titre = json_decode($v->titre);
			$line->titre = $titre->{_LANG_DEFAULT};
			$line->superficie = $v->superficie;
			$line->ville = $v->ville_nom;
			$line->cp = $v->cp;
			$line->prix = $v->prix;
			$line->prix_formated = number_format($v->prix, floor($v->prix) == $v->prix ? 0 : 2, ',', ' ');
			$images = json_decode($v->images);
			$line->image = _ROOT . $this->getAnnonceDirImg($v->id) . 'sm_' . $images[0]->image;
			$line->str = '<strong>ID : </strong>' . $v->id . ' &nbsp;&nbsp; <strong>Ref : </strong>' . $v->ref . ' &nbsp;&nbsp; <strong>Titre : </strong>' . $line->titre . ' &nbsp;&nbsp; <strong>Ville : </strong>' . $line->ville;
			$return[] = $line;
		}
		return $return;
	}

}
