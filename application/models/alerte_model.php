<?php
class Model_alerte extends Model{
    public function addAlerte($p)
    {
        $params = array(
            $p['email'],
            $p['bien'],

        );
        $sql = $this->db->prepare('SELECT * 
        FROM ' . $this->avertir . '
        WHERE email = ?
        AND bien = ?;');
        $sql->execute($params);
        $result = $sql->fetch();

        if (!$result) {
            $params = array(
                null,
                $p['email'],
                $p['bien'],
                $p['nom'],
                $p['prenom']
            );
            $sql = $this->db->prepare('INSERT INTO ' . $this->avertir . ' VALUES (' . implode(',', array_fill(0, count($params), '?')) . ');');
            $sql->execute($params);
            $test = true;
            return $test;
        }
        else{
            $test = false;
            return $test;
        }
    }
}