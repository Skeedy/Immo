<?php
class Model_renovation extends Model {


    public function getRenovationDirImg($id) {
        return _DIR_IMG_RENOVATION.preg_replace('/(\d{1})/', '$1/', $id);
    }

    public function getRenovationDirComp($id) {
        return _DIR_IMG_COMPARAISON.preg_replace('/(\d{1})/', '$1/', $id);
    }


    public function getRenovations($_filter = array(), $page = false, $sort = 'ordre') {
        $where = array();
        $params = array();
        $return = new stdClass();

        //
        //recherche
        //
        //
        // page
        //
        if($page) {
            $sql = $this->db->prepare('SELECT COUNT(DISTINCT '.$this->renovation.'.id)
				FROM '.$this->renovation.'
				LEFT JOIN '.$this->ville.' ON '.$this->renovation.'.ville = '.$this->ville.'.id
				WHERE '.$this->renovation.'.active = 1
				'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').';');
            $sql->execute($params);
            $total = $sql->fetchColumn();
            $page_max = ceil($total / _SITE_NB_RENOVATIONS);
            if($page_max == 0)
                $page_max = 1;
            $page = !empty($page) && is_numeric($page) ? $page : 1;
            if($page > $page_max)
                $page = $page_max;
            $min = ($page - 1) * _SITE_NB_RENOVATIONS;
        }

        $sql = $this->db->prepare('SELECT DISTINCT '.$this->renovation.'.*, '.$this->ville.'.nom_reel
			FROM '.$this->renovation.'
			LEFT JOIN '.$this->ville.' ON '.$this->renovation.'.ville = '.$this->ville.'.id
			WHERE '.$this->renovation.'.active = 1
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').'
			GROUP BY '.$this->renovation.'.id
			ORDER BY '.addslashes($sort).', '.$this->renovation.'.id DESC
			LIMIT '.($page ? _SITE_NB_RENOVATIONS.' OFFSET '.$min : _SITE_NB_RENOVATIONS).';');
        $sql->execute($params);
        $return->annonces = $sql->fetchAll();

        if($page) {
            $return->total = $total;
            $return->page_max = $page_max;
            $return->page = $page;
        }
        return $return;
    }




    public function getRenovation($id) {
        $sql = $this->db->prepare('SELECT '.$this->renovation.'.*, '.$this->ville.'.nom_reel
			FROM  '.$this->renovation.'
			LEFT JOIN '.$this->ville.' ON '.$this->renovation.'.ville = '.$this->ville.'.id
			WHERE '.$this->renovation.'.active = 1
			AND '.$this->renovation.'.id = ?;');
        $sql->execute(array($id));
        if($res = $sql->fetch()) {
            return $res;
        }
        else
            return false;
    }





    public function getRenovationLink($_bien) {
        $titre = json_decode($_bien->titre);
        return _PROTOCOL . $_SERVER['HTTP_HOST'] . _ROOT_LANG . $_bien->id . '-' . clean_str(__lang($titre));
    }


    public function getAnnoncePrevNext($id, $_filter = array(), $sort = 'ordre') {
        $where = array();
        $params = array();

        //
        //recherche
        //
        if( !empty($_filter['ref']) ) {
            $where[] = $this->annonce.'.ref LIKE ?';
            $params[] = '%' . $_filter['ref'] . '%';
        }


        if(!empty($_filter['superficie'])) {
            $superficie = str_replace(' ', '', $_filter['superficie']);
            if(preg_match('/^-(\d+)$/', $superficie, $matches)) {
                $where[] = $this->annonce.'.superficie <= ?';
                $params[] = $matches[1];
            }
            else if(preg_match('/^\+(\d+)$/', $superficie, $matches)) {
                $where[] = $this->annonce.'.superficie >= ?';
                $params[] = $matches[1];
            }
            else if(preg_match('/^(\d+)-(\d+)$/', $superficie, $matches)) {
                $where[] = $this->annonce.'.superficie BETWEEN ? AND ?';
                $params[] = $matches[1];
                $params[] = $matches[2];
            }
        }

        $sql = $this->db->prepare('SELECT DISTINCT '.$this->annonce.'.id
			FROM '.$this->type.', '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			WHERE '.$this->annonce.'.type = '.$this->type.'.id
			AND '.$this->annonce.'.active = 1
			'.(!empty($where) ? 'AND '.implode(' AND ', $where) : '').'
			GROUP BY '.$this->annonce.'.id
			ORDER BY '.addslashes($sort).', '.$this->annonce.'.id DESC;');
        $sql->execute($params);
        $res = $sql->fetchAll(PDO::FETCH_COLUMN);
        $return = new stdClass();
        if(($pos = array_search($id, $res)) !== false) {
            if( $pos != 0 ) {
                $sql = $this->db->prepare('SELECT id, titre
					FROM '.$this->annonce.'
					WHERE id = ?;');
                $sql->execute(array($res[$pos - 1]));
                $return->prev = $sql->fetch();
            }
            else
                $return->prev = false;

            if( $pos != count($res) - 1 ) {
                $sql = $this->db->prepare('SELECT id, titre
					FROM '.$this->annonce.'
					WHERE id = ?;');
                $sql->execute(array($res[$pos + 1]));
                $return->next = $sql->fetch();
            }
            else
                $return->next = false;
        }
        else {
            $return->prev = false;
            $return->next = false;
        }
        return $return;
    }



    public function searchAnnonces($str, $page = 1) {
        $str = !empty($str) ? trim($str) : '';
        $slug = !empty($str) ? utf8_encode(preg_replace('/[^a-z 0-9-]/', ' ', strtolower(strtr(trim(utf8_decode($str)),utf8_decode('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ'), 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')))) : '';
        $select = array();
        $params = array();
        $where = array();
        $orderexact = array();
        $orderdebut = array();
        $ordermilieu = array();

        if(empty($str))
            return $this->getAnnonces(false, false, $page);

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

        $return = new stdClass();
        $sql = $this->db->prepare('SELECT '.$this->annonce.'.id,
			'.implode(', ', $select).'
			FROM '.$this->annonce.'
			WHERE date_vente IS NULL
			AND active = 1
			'.(!empty($where) ? 'AND ('.implode(' OR ', $where).')' : '').'
			ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).';');
        $sql->execute($params);
        $total = count($sql->fetchAll());
        $page_max = ceil($total / _SITE_NB_ANNONCES);
        if($page_max == 0)
            $page_max = 1;
        $page = !empty($page) && is_numeric($page) ? $page : 1;
        if($page > $page_max)
            $page = $page_max;
        $min = ($page - 1) * _SITE_NB_ANNONCES;

        $sql = $this->db->prepare('SELECT '.$this->annonce.'.*, '.$this->ville.'.nom_reel, '.$this->type.'.label AS type_label,
			'.implode(', ', $select).'
			FROM '.$this->type.', '.$this->annonce.'
			LEFT JOIN '.$this->ville.' ON '.$this->annonce.'.ville = '.$this->ville.'.id
			WHERE '.$this->annonce.'.type = '.$this->type.'.id
			AND '.$this->annonce.'.active = 1
			AND '.$this->annonce.'.date_vente IS NULL
			'.(!empty($where) ? 'AND ('.implode(' OR ', $where).')' : '').'
			ORDER BY '.implode(', ', array_merge($orderexact, $orderdebut, $ordermilieu)).'
			LIMIT '._SITE_NB_ANNONCES.' OFFSET '.$min.';');
        $sql->execute($params);
        $return->annonces = $sql->fetchAll();
        $return->total = $total;
        $return->page_max = $page_max;
        $return->page = $page;
        return $return;
    }




    public function getAnnoncesSitemap() {
        $params = array();

        $sql = $this->db->prepare('SELECT DISTINCT '.$this->annonce.'.id, '.$this->annonce.'.titre
			FROM '.$this->annonce.'
			WHERE '.$this->annonce.'.active = 1 ;');
        $sql->execute($params);
        return $sql->fetchAll();
    }


}