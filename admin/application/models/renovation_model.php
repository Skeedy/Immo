<?php
class Model_renovation extends Model{

    public function getRenovationDirImg($id) {
        return _DIR_IMG_RENOVATION.preg_replace('/(\d{1})/', '$1/', $id);
    }

    public function getRenovationDirComp($id) {
        return _DIR_IMG_COMPARAISON.preg_replace('/(\d{1})/', '$1/', $id);
    }

    public function getVilles($villes) {
        $sql = $this->db->prepare('SELECT id, nom_reel
			FROM '.$this->ville.'
			WHERE id IN ('.implode(',', (array) $villes).');');
        $sql->execute();
        return $sql->fetchAll();
    }

    public function getRenovations($sort = 'ordre ASC', $pagekey = 'p') {
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
        $sql = $this->db->prepare('SELECT COUNT('.$this->renovation.'.id)
			FROM '.$this->renovation.'
			'.(!empty($where) ? 'WHERE '.implode(' AND ', $where) : '').';');
        $sql->execute($params);
        $total = $sql->fetchColumn();
        $page_max = ceil($total / _NB_PAR_PAGE);
        if($page_max == 0)
            $page_max = 1;
        $page = !empty($_GET[$pagekey]) && is_numeric($_GET[$pagekey]) ? $_GET[$pagekey] : 1;
        if($page > $page_max)
            $page = $page_max;
        $min = ($page - 1) * _NB_PAR_PAGE;

        $sql = $this->db->prepare('SELECT '.$this->renovation.'.*, '.$this->ville.'.nom_reel
			FROM '.$this->renovation.'
			LEFT JOIN '.$this->ville.' ON '.$this->renovation.'.ville = '.$this->ville.'.id
			'.(!empty($where) ? 'WHERE '.implode(' AND ', $where) : '').'
			ORDER BY '.addslashes($sort).', '.$this->renovation.'.id DESC
			LIMIT '._NB_PAR_PAGE.' OFFSET '.$min.';');
        $sql->execute($params);
        $return->annonces = $sql->fetchAll();
        $return->total = $total;
        $return->page_max = $page_max;
        $return->page = $page;
        return $return;
    }

    public function getRenovation($id)
    {
        global $_current_user;

        $sql = $this->db->prepare('SELECT ' . $this->renovation . '.*, '.$this->ville.'.nom_reel
			FROM ' . $this->renovation . '
			LEFT JOIN '.$this->ville.' ON '.$this->renovation.'.ville = '.$this->ville.'.id
			WHERE ' . $this->renovation . '.id = ?;');
        $sql->execute(array($id));
        if ($res = $sql->fetch()) {
            return $res;
        } else
            return false;
    }

    public function addRenovation($p)
    {
        if($p['isOver']) {
            if(!empty($p['date_livraison']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_livraison']))
                $date_livraison = $date->format('Y-m-d');
            else
                $date_livraison = date('Y-m-d');
        }
        global $_LANGS, $_current_user;
        $params = array(
            null,
            $p['ref'],
            date('Y-m-d H:i:s'),
            $p['titre'],
            $p['adresse'],
            $p['cp'],
            $p['ville'],
            $p['lat'] != '' ? $p['lat'] : NULL,
			$p['lng'] != '' ? $p['lng'] : NULL,
            '',
            '',
            json_encode($p['data']),
            0,
            $p['active'],
            $p['isOver'],
            $date_livraison
        );
        $sql = $this->db->prepare('INSERT INTO ' . $this->renovation . '
			VALUES (' . implode(',', array_fill(0, count($params), '?')) . ');');
        if($sql->execute($params)) {
            $id = $this->db->lastInsertId();

            $sql = $this->db->prepare('UPDATE '.$this->renovation.'
				SET ordre = ordre + 1
				WHERE 1;');
            $sql->execute();

            //si images -> traitement
            //chemins
            $_dir_renovation = '../' . $this->getRenovationDirImg($id);
            //creation dossier
            mkdir($_dir_renovation, 0775, true);
            $images = array();
            $comparaisons= array();
            if ($p['images']) {
                if (!empty($p['images'])) {
                    require_once _DIR_LIB . 'phpthumb/ThumbLib.inc.php';
                    umask(0002);

                    //transfert des fichiers
                    $old_dir = array();
                    for ($i = 0; $i < count($p['images']); $i++) {
                        rename($p['images'][$i], $_dir_renovation . $i . '.jpg');
                        $t = pathinfo($p['images'][$i]);
                        if (!in_array($t['dirname'], $old_dir))
                            $old_dir[] = $t['dirname'];
                        $img = $i . '.jpg';
                        foreach (array(
                                     array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
                                     array(_IMG_MD_WIDTH, _IMG_MD_HEIGHT, 'md'),
                                     array(_IMG_LG_WIDTH, _IMG_LG_HEIGHT, 'lg')
                                 ) as $dim) {
                            $im = PhpThumbFactory::create($_dir_renovation . $img);
                            $im->adaptiveResize($dim[0], $dim[1]);
                            $im->save($_dir_renovation . $dim[2] . '_' . $img);
                        }
                        $im = PhpThumbFactory::create($_dir_renovation . $img);
                        $im->save($_dir_renovation . $img);
                        $t = new stdClass();
                        $t->image = $img;
                        $t->legende = new stdClass();
                        foreach ($_LANGS as $l => $ll)
                            $t->legende->{$l} = $p['images_legend'][$l][$i];
                        $images[] = $t;
                    }

                }
                $sql = $this->db->prepare('UPDATE ' . $this->renovation . '
				SET images = ?
				WHERE id = ?;');
                $sql->execute(array(json_encode($images), $id));
            }
            if ($p['comparaisons']) {
                if (!empty($p['comparaisons'])) {
                    require_once _DIR_LIB . 'phpthumb/ThumbLib.inc.php';
                    umask(0002);
                    //chemins
                    $_dir_comparaison = '../' . $this->getRenovationDirComp($id);
                    //creation dossier
                    mkdir($_dir_comparaison, 0775, true);
                    //transfert des fichiers
                    $old_dir = array();
                    for ($i = 0; $i < count($p['comparaisons']); $i++) {
                        rename($p['comparaisons'][$i], $_dir_comparaison . $i . '.jpg');
                        $t = pathinfo($p['comparaisons'][$i]);
                        if (!in_array($t['dirname'], $old_dir))
                            $old_dir[] = $t['dirname'];
                        $img = $i . '.jpg';
                        foreach (array(
                                     array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
                                     array(_IMG_BA_WIDTH, _IMG_BA_HEIGHT, 'lg')
                                 ) as $dim) {
                            $im = PhpThumbFactory::create($_dir_comparaison . $img);
                            $im->adaptiveResize($dim[0], $dim[1]);
                            $im->save($_dir_comparaison . $dim[2] . '_' . $img);
                        }
                        $im = PhpThumbFactory::create($_dir_comparaison . $img);
                        $im->save($_dir_comparaison . $img);
                        $t = new stdClass();
                        $t->image = $img;
                        $t->legende = new stdClass();
                        foreach ($_LANGS as $l => $ll)
                            $t->legende->{$l} = $p['comparaisons_legend'][$l][$i];
                        $comparaisons[] = $t;
                    }
                    foreach ($old_dir as $v) {
                        $this->delTree($v);
                    }
                }
                $sql = $this->db->prepare('UPDATE ' . $this->renovation . '
				SET comparaisons = ?
				WHERE id = ?;');
                $sql->execute(array(json_encode($comparaisons), $id));
            }

            throwAlert('success', '', '<p>La rénovation <strong>"' . $p['titre'].'</strong> a bien été ajoutée.</p>');
            historique_write('BIEN' . $id . 'ADMIN' . $_current_user->id, 'Ajout bien', array('description' => 'Ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'POST' => cleanPost($p)));
            return true;
        } else {
            $e = $this->db->errorInfo();
            throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création du bien <strong>"' . $p['titre'][_LANG_DEFAULT] . '"</strong>.</p>' . (_DEBUG ? '<p>' . $e[2] . '</p>' : ''));
            historique_write('ADMIN' . $_current_user->id, 'Erreur ajout bien', array('description' => 'Erreur ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'error' => $e, 'POST' => cleanPost($p)));
            return false;
        }
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

    public function modifyRenovation($p)
    {
        global $_LANGS, $_current_user;
        if (!($renovation = $this->getRenovation($p['action_modify']))) {
            throwAlert('danger', 'Erreur', 'Cette rénovation n\'existe pas.');
            return false;
        }
//traitement images
        require_once _DIR_LIB.'phpthumb/ThumbLib.inc.php';
        umask(0002);
        //chemins
        $_dir_renovation = '../'.$this->getRenovationDirImg($renovation->id);
        $_dir_comparaison = '../'.$this->getRenovationDirComp($renovation->id);
        //creation dossier
        if(!is_dir($_dir_renovation))
            mkdir($_dir_renovation, 0775, true);

        //anciennes images
        $renovation->images = json_decode($renovation->images);
        $old_images = array();
        foreach($renovation->images as $v)
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

        //renommer + déplacer images selon nouvel index
        $new_images = array();
        $new_comparaisons = array();
        $old_dir = array();
        if($p['images']) {
            for ($i = 0; $i < count($p['images']); $i++) {
                //si image déjà présente
                if (basename($p['images'][$i]) == $p['images'][$i]) {
                    $new_name = preg_replace('/\.jpg(.*)$/', '.jpg', $p['images'][$i]);
                    /*rename($_dir_annonce.$p['images'][$i], $_dir_annonce.$new_name);
                    rename($_dir_annonce.'sm_'.$p['images'][$i], $_dir_annonce.'sm_'.$new_name);
                    rename($_dir_annonce.'md_'.$p['images'][$i], $_dir_annonce.'md_'.$new_name);
                    rename($_dir_annonce.'lg_'.$p['images'][$i], $_dir_annonce.'lg_'.$new_name);*/
                } else {    //si nouvelle image
                    $new_name = $index . '.jpg';
                    rename($p['images'][$i], $_dir_renovation . $new_name);
                    $t = pathinfo($p['images'][$i]);
                    if (!in_array($t['dirname'], $old_dir))
                        $old_dir[] = $t['dirname'];
                    foreach (array(
                                 array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
                                 array(_IMG_MD_WIDTH, _IMG_MD_HEIGHT, 'md'),
                                 array(_IMG_LG_WIDTH, _IMG_LG_HEIGHT, 'lg')
                             ) as $dim) {
                        $im = PhpThumbFactory::create($_dir_renovation . $new_name);
                        $im->adaptiveResize($dim[0], $dim[1]);
                        $im->save($_dir_renovation . $dim[2] . '_' . $new_name);
                    }
                    $im = PhpThumbFactory::create($_dir_renovation . $new_name);
                    $im->save($_dir_renovation . $new_name);
                    $index++;
                }
                $t = new stdClass();
                $t->image = $new_name . '?' . time();
                $t->legende = new stdClass();
                foreach ($_LANGS as $l => $ll)
                    $t->legende->{$l} = $p['images_legend'][$l][$i];
                $new_images[] = $t;
            }
        }

        //anciennes images
        $renovation->comparaisons = json_decode($renovation->comparaisons);
        $old_images = array();
        foreach($renovation->comparaisons as $v)
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

        if($p['comparaisons']) {
            for ($i = 0; $i < count($p['comparaisons']); $i++) {
                //si image déjà présente
                if (basename($p['comparaisons'][$i]) == $p['comparaisons'][$i]) {
                    $new_name = preg_replace('/\.jpg(.*)$/', '.jpg', $p['comparaisons'][$i]);
                    /*rename($_dir_annonce.$p['images'][$i], $_dir_annonce.$new_name);
                    rename($_dir_annonce.'sm_'.$p['images'][$i], $_dir_annonce.'sm_'.$new_name);
                    rename($_dir_annonce.'md_'.$p['images'][$i], $_dir_annonce.'md_'.$new_name);
                    rename($_dir_annonce.'lg_'.$p['images'][$i], $_dir_annonce.'lg_'.$new_name);*/
                } else {    //si nouvelle image
                    $new_name = $index . '.jpg';
                    rename($p['comparaisons'][$i], $_dir_comparaison . $new_name);
                    $t = pathinfo($p['comparaisons'][$i]);
                    if (!in_array($t['dirname'], $old_dir))
                        $old_dir[] = $t['dirname'];
                    foreach (array(
                                 array(_IMG_SM_WIDTH, _IMG_SM_HEIGHT, 'sm'),
                                 array(_IMG_MD_WIDTH, _IMG_MD_HEIGHT, 'md'),
                                 array(_IMG_BA_WIDTH, _IMG_BA_HEIGHT, 'lg')
                             ) as $dim) {
                        $im = PhpThumbFactory::create($_dir_comparaison . $new_name);
                        $im->adaptiveResize($dim[0], $dim[1]);
                        $im->save($_dir_comparaison . $dim[2] . '_' . $new_name);
                    }
                    $im = PhpThumbFactory::create($_dir_comparaison . $new_name);
                    $im->save($_dir_comparaison . $new_name);
                    $index++;
                }
                $t = new stdClass();
                $t->image = $new_name . '?' . time();
                $t->legende = new stdClass();
                foreach ($_LANGS as $l => $ll)
                    $t->legende->{$l} = $p['comparaisons_legend'][$l][$i];
                $new_comparaisons[] = $t;
            }
            foreach ($old_dir as $v) {
                $this->delTree($v);
            }
        }
        if($p['isOver']) {
            if(!empty($p['date_livraison']) && $date = DateTime::createFromFormat('d/m/Y', $p['date_livraison']))
                $date_livraison = $date->format('Y-m-d');
            else
                $date_livraison = date('Y-m-d');
        }
        else
            $date_livraison = NULL;
        //paramètres de modif db
        $params = array(
            'ref' => $p['ref'],
            'titre' => $p['titre'],
            'adresse' => $p['adresse'],
            'cp' => $p['cp'],
            'ville' => $p['ville'],
            'lat' => $p['lat'] != '' ? $p['lat'] : NULL,
			'lng' => $p['lng'] != '' ? $p['lng'] : NULL,
            'images' => json_encode($new_images),
            'comparaisons' => json_encode($new_comparaisons),
            'data' => json_encode($p['data']),
            'active' => !empty($p['active']) ? 1 : 0,
            'isOver' => !empty($p['isOver']) ? 1 : 0,
            'date_livraison'=> $date_livraison
        );
        $sql = $this->db->prepare('UPDATE ' . $this->renovation . '
			SET ' . implode(' = ?, ', array_keys($params)) . ' = ?
			WHERE id = ?;');
        if ($sql->execute(array_merge(array_values($params), array($renovation->id)))) {
            throwAlert('success', '', '<p>La rénovation <strong>"' . $p['titre'].' "</strong> a bien été modifiée.</p>');
            historique_write('Rénovation' . $renovation->id . 'ADMIN' . $_current_user->id, 'Modification bien', array
            ('description' => 'Modification bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'POST' => cleanPost($p)));
            return true;
        } else {
            $e = $this->db->errorInfo();
            throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification du bien <strong>"' . $p['titre'][_LANG_DEFAULT] . '"</strong>.</p>' . (_DEBUG ? '<p>' . $e[2] . '</p>' : ''));
            return false;
        }
    }


    public function changePositionRenovations($id, $direction, $sort = 'date DESC', $vendus = false) {
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
		
		$sql = $this->db->prepare('SELECT '.$this->renovation.'.id, ordre
			FROM '.$this->renovation.'
			'.(!empty($where) ? 'WHERE '.implode(' AND ', $where) : '').'
			ORDER BY '.addslashes($sort).', '.$this->renovation.'.id DESC;');
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
		$sql = $this->db->prepare('UPDATE '.$this->renovation.' SET ordre = ? WHERE id = ?;');
		$sql->execute(array($other_ordre, $id));
		$sql = $this->db->prepare('UPDATE '.$this->renovation.' SET ordre = ? WHERE id = ?;');
		$sql->execute(array($ordre, $other_id));
	}


    public function deleteRenovation($id) {
		global $_current_user;

		if($p = $this->getRenovation($id)) {
			foreach(json_decode($p->images) as $img) {
				$dir = '../'.$this->getRenovationDirImg($p->id);
				$file = preg_replace('/\.jpg(.*)$/', '.jpg', $img->image);
				unlink($dir.$file);
				unlink($dir.'lg_'.$file);
				unlink($dir.'md_'.$file);
				unlink($dir.'sm_'.$file);
			}
			foreach(json_decode($p->comparaisons) as $img) {
				$dir = '../'.$this->getRenovationDirComp($p->id);
				$file = preg_replace('/\.jpg(.*)$/', '.jpg', $img->image);
				unlink($dir.$file);
				unlink($dir.'lg_'.$file);
				unlink($dir.'sm_'.$file);
			}
			$sql = $this->db->prepare('DELETE FROM ' . $this->renovation . '
				WHERE id = ?;');
            $sql->execute(array($id));

			$sql = $this->db->prepare('UPDATE '.$this->renovation.'
				SET ordre = ordre - 1
				WHERE ordre > ?;');
			$sql->execute(array($p->ordre));
			
			throwAlert('success', '', '<p>La rénovation <strong>"'.$p->ref.'"</strong> a bien été supprimée.</p>');
			return true;
		}
		else {
			throwAlert('danger', 'Erreur', 'Cette rénovation n\'existe pas.');
			return false;
		}
	}
}
