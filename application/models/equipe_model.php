<?php
class Model_equipe extends Model
{

    public function getAllEquipiers()
    {
        $sql = $this->db->prepare('SELECT *
                FROM ' . $this->equipe . '
                ');
        $sql->execute();
        return $sql->fetchAll();
    }

    public function getAllEmails()
    {
        $sql = $this->db->prepare('SELECT email
			FROM ' . $this->equipe . '
			');
        $sql->execute();
        return $sql->fetchAll( PDO::FETCH_COLUMN);

    }
}
