<?php

namespace DAO;

use Manager\Billet;

class BilletDAO extends DAO
{

    /**
     * retourne la liste de tout les billets, trié par date (plus récent en premier).
     *
     * @return array A list of all billets.
     */
    public function findAll() {
        $sql = "SELECT * FROM billets ORDER BY id_billet DESC";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $billets = array();
        foreach ($result as $row) {
            $billetId = $row['id_billet'];
            $billets[$billetId] = $this->buildDomainObject($row);
        }
        return $billets;
    }

    /**
     * Returns an billet matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Manager\Billet|throws an exception if no matching billet is found
     */
    public function find($id) {
        $sql = "SELECT * FROM billets WHERE id_billet=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No billet matching id " . $id);
    }


    /**
     * Saves an billet into the database.
     *
     * @param \Manager\Billet $billet The billet to save
     */
    public function save(Billet $billet) {
        $billetData = array(
            'titre' => $billet->getTitre(),
            'contenu' => $billet->getContenu(),
            );

        if ($billet->getId()) {
            // The billet has already been saved : update it
            $this->getDb()->update('billets', $billetData, array('id_billet' => $billet->getId()));
        } else {
            // The billet has never been saved : insert it
            $this->getDb()->insert('billets', $billetData);
            // Get the id of the newly created billet and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $billet->setId($id);
        }
    }

    /**
     * Removes an billet from the database.
     *
     * @param integer $id The billet id.
     */
    public function delete($id) {
        // Delete the billet
        $this->getDb()->delete('billets', array('id_billet' => $id));
    }



    /**
     * Creates an Billet object based on a DB row.
     *
     * @param array $row The DB row containing Billet data.
     * @return Manager\Billet
     */
    protected function buildDomainObject($row) {
        $billet = new Billet();
        $billet->setId($row['id_billet']);
        $billet->setTitre($row['titre']);
        $billet->setContenu($row['contenu']);
        $billet->setDateCreation($row['date_creation']);
        return $billet;
    }
}
