<?php
class Model_equipe extends Model
{


    public function getEquipeDirImg($id)
    {
        return _DIR_IMG_EQUIPE . preg_replace('/(\d{1})/', '$1/', $id);
    }


    public function delTree($dir)
    {
        if (!empty($dir) && preg_match('#^(' . _DIR_IMG_TMP . '(.+))$#', $dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
            }
            rmdir($dir);
        }
    }

    public function getAllEquipes($sort = 'id')
    {
        $sql = $this->db->prepare('SELECT *
			FROM ' . $this->equipe . '
			ORDER BY ' . addslashes($sort) . ';');
        $sql->execute();
        return $sql->fetchAll();
    }


    public function getEquipes($sort = 'id DESC', $pagekey = 'p')
    {
        global $_current_user;

        $where = array();
        $params = array();

        //filtres annonces
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $where[] = $this->annonce . '.id = ?';
            $params[] = $_GET['id'];
        } else if (!empty($_GET['s_id'])) {
            $t = $this->searchAnnonceIds($_GET['s_id'], -1);
            $a = array();
            for ($i = 0; $i < count($t); $i++)
                $a[] = $t[$i]->id;
            if (!empty($a))
                $where[] = $this->annonce . '.id IN (' . implode(',', $a) . ')';
        }

        if (!empty($_GET['ref']) && is_numeric($_GET['ref'])) {
            $where[] = $this->annonce . '.ref = ?';
            $params[] = $_GET['ref'];
        } else if (!empty($_GET['s_ref'])) {
            $t = $this->searchAnnonceRefs($_GET['s_ref'], -1);
            $a = array();
            for ($i = 0; $i < count($t); $i++)
                $a[] = $t[$i]->ref;
            if (!empty($a))
                $where[] = $this->annonce . '.ref IN ("' . implode('","', $a) . '")';
        }

        if (!empty($_GET['s_titre'])) {
            $t = $this->searchTitres($_GET['s_titre'], -1);
            $a = array();
            for ($i = 0; $i < count($t); $i++)
                $a[] = $t[$i]->id;
            if (!empty($a))
                $where[] = $this->annonce . '.id IN (' . implode(',', $a) . ')';
        }

        if (!empty($_GET['ville']) && is_numeric($_GET['ville'])) {
            $where[] = $this->annonce . '.ville = ?';
            $params[] = $_GET['ville'];
        } else if (!empty($_GET['s_ville'])) {
            if (preg_match('/(\d+)/', trim($_GET['s_ville']), $matches)) {
                $where[] = $this->annonce . '.cp LIKE "%' . $matches[1] . '%"';
            } else {
                $t = $this->searchAnnoncesVille($_GET['s_ville'], -1);
                $a = array();
                for ($i = 0; $i < count($t); $i++)
                    $a[] = $t[$i]->id;
                if (!empty($a))
                    $where[] = $this->annonce . '.ville IN (' . implode(',', $a) . ')';
            }
        }

        $return = new stdClass();
        $sql = $this->db->prepare('SELECT COUNT(' . $this->annonce . '.id)
			FROM ' . $this->annonce . '
			WHERE ' . $this->annonce . '.date_vente IS ' . (!empty($vendus) ? 'NOT ' : '') . 'NULL
			' . (!empty($where) ? 'AND ' . implode(' AND ', $where) : '') . ';');
        $sql->execute($params);
        $total = $sql->fetchColumn();
        $page_max = ceil($total / _NB_PAR_PAGE);
        if ($page_max == 0)
            $page_max = 1;
        $page = !empty($_GET[$pagekey]) && is_numeric($_GET[$pagekey]) ? $_GET[$pagekey] : 1;
        if ($page > $page_max)
            $page = $page_max;
        $min = ($page - 1) * _NB_PAR_PAGE;

        $sql = $this->db->prepare('SELECT ' . $this->annonce . '.*, ' . $this->ville . '.nom_reel, ' . $this->type . '.label AS type_label
			FROM ' . $this->type . ', ' . $this->annonce . '
			LEFT JOIN ' . $this->ville . ' ON ' . $this->annonce . '.ville = ' . $this->ville . '.id
			WHERE ' . $this->annonce . '.type = ' . $this->type . '.id
			AND ' . $this->annonce . '.date_vente IS ' . (!empty($vendus) ? 'NOT ' : '') . 'NULL
			' . (!empty($where) ? 'AND ' . implode(' AND ', $where) : '') . '
			ORDER BY ' . addslashes($sort) . ', ' . $this->annonce . '.id DESC
			LIMIT ' . _NB_PAR_PAGE . ' OFFSET ' . $min . ';');
        $sql->execute($params);
        $return->annonces = $sql->fetchAll();
        $return->total = $total;
        $return->page_max = $page_max;
        $return->page = $page;
        return $return;
    }


    public function getEquipe($id)
    {
        global $_current_user;

        $sql = $this->db->prepare('SELECT ' . $this->equipe . '.*
			FROM ' . $this->equipe . '
			WHERE ' . $this->equipe . '.id = ?;');
        $sql->execute(array($id));
        if ($res = $sql->fetch()) {
            return $res;
        } else
            return false;
    }

    public function checkPostEquipe($p)
    {
        global $_LANGS, $_current_user;
        $error = array();
        if (empty($p['nom']))
            $error[] = 'Le champ "Nom" n\'est pas renseigné.';
        if ($p['prenom'] == '')
            $error[] = 'Le champ "Prénom" n\'est pas renseigné.';
        if (empty($p['email']))
            $error[] = 'Le champ "Email" n\'est pas renseigné.';
        if (empty($p['telephone']))
            $error[] = 'Le champ "Téléphone" n\'est pas renseigné.';
        if (empty($p['profession']))
            $error[] = 'Le champ "Profession" n\'est pas renseigné.';
        echo var_dump($p['nom'], $p['profession']);
        return $error;
    }


    public function addEquipe($p)
    {
        global $_LANGS, $_current_user;
        $error = $this->checkPostEquipe($p);
        if (!empty($error)) {
            throwAlert('danger', 'Erreur', '<p>' . implode('</p><p>', $error) . '</p>');
            return false;
        }

        $params = array(
            null,
            $p['nom'],
            $p['prenom'],
            $p['email'],
            $p['telephone'],
            $p['profession'],
            !empty($p['image']) ? $p['image'] : '',
            $p['description'],
            $p['isActive']
        );
        $sql = $this->db->prepare('INSERT INTO ' . $this->equipe . '
			VALUES (' . implode(',', array_fill(0, count($params), '?')) . ');');
        if ($sql->execute($params)) {
            $id = $this->db->lastInsertId();

            throwAlert('success', '', '<p>L\'utilisateur <strong>"' . $p['nom'].''.$p['prenom'] . '"</strong> a bien été ajouté.</p>');
            historique_write('BIEN' . $id . 'ADMIN' . $_current_user->id, 'Ajout bien', array('description' => 'Ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'POST' => cleanPost($p)));
            return true;
        } else {
            $e = $this->db->errorInfo();
            throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la création du bien <strong>"' . $p['titre'][_LANG_DEFAULT] . '"</strong>.</p>' . (_DEBUG ? '<p>' . $e[2] . '</p>' : ''));
            historique_write('ADMIN' . $_current_user->id, 'Erreur ajout bien', array('description' => 'Erreur ajout bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'error' => $e, 'POST' => cleanPost($p)));
            return false;
        }
    }


    public function modifyEquipe($p)
    {
        global $_LANGS, $_current_user;
        if (!($equipe = $this->getEquipe($p['action_modify']))) {
            throwAlert('danger', 'Erreur', 'Cet équipier n\'existe pas.');
            return false;
        }
        $error = $this->checkPostEquipe($p);
        if (!empty($error)) {
            throwAlert('danger', 'Erreur', '<p>' . implode('</p><p>', $error) . '</p>');
            return false;
        }

        //paramètres de modif db
        $params = array(
            'nom' => $p['nom'],
            'prenom' => $p['prenom'],
            'email' => $p['email'],
            'telephone' => $p['telephone'],
            'profession' => $p['profession'],
            'img' =>  !empty($p['image']) ? $p['image'] : '',
            'description' => $p['description'],
            'isActive' =>$p['isActive']
        );
        $sql = $this->db->prepare('UPDATE ' . $this->equipe . '
			SET ' . implode(' = ?, ', array_keys($params)) . ' = ?
			WHERE id = ?;');
        if ($sql->execute(array_merge(array_values($params), array($equipe->id)))) {
            throwAlert('success', '', '<p>L\'utilisateur <strong>"' . $p['prenom'].''.$p['nom']. '"</strong> a bien été modifié.</p>');
            historique_write('BIEN' . $equipe->id . 'ADMIN' . $_current_user->id, 'Modification bien', array('description' => 'Modification bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'POST' => cleanPost($p)));
            return true;
        } else {
            $e = $this->db->errorInfo();
            throwAlert('danger', 'Erreur', '<p>Une erreur est survenue lors de la modification du bien <strong>"' . $p['titre'][_LANG_DEFAULT] . '"</strong>.</p>' . (_DEBUG ? '<p>' . $e[2] . '</p>' : ''));
            historique_write('BIEN' . $equipe->id . 'ADMIN' . $_current_user->id, 'Erreur modification bien', array('description' => 'Erreur modification bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')', 'error' => $e, 'POST' => cleanPost($p)));
            return false;
        }
    }


    public function deleteEquipe($id)
    {
        if ($p = $this->getEquipe($id)) {

            $sql = $this->db->prepare('DELETE FROM ' . $this->equipe . '
				WHERE id = ?;');
            $sql->execute(array($id));

            throwAlert('success', '', '<p>L\'utilisateur <strong>"'.$p->prenom.' '.$p->nom.'"</strong> a bien été supprimé.</p>');
            historique_write('BIEN' . $id . 'ADMIN' . $_current_user->id, 'Suppression bien', array('description' => 'Suppression bien depuis le backoffice', 'IP' => $_SERVER['REMOTE_ADDR'], 'session_id' => session_id(), 'OP' => $_current_user->id . ' (' . $_current_user->prenom . ' ' . $_current_user->nom . ')'));
            return true;
        } else {
            throwAlert('danger', 'Erreur', 'Cet équipier n\'existe pas.');
            return false;
        }
    }

}
