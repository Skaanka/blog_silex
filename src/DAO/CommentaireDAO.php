<?php

namespace DAO;

use Manager\Commentaire;

class CommentaireDAO extends DAO
{
    /**
     * @var DAO\BilletDAO
     */
    private $billetDAO;

    /**
     * @var \DAO\UserDAO
     */
    private $userDAO;


    public function setBilletDAO(BilletDAO $billetDAO) {
        $this->billetDAO = $billetDAO;
    }

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Returns a list of all comments, sorted by date (most recent first).
     *
     * @return array A list of all comments.
     */
    public function findAll() {
        $sql = "SELECT * FROM commentaires ORDER BY user_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Return a list of all comments for an billet, sorted by date (most recent last).
     *
     * @param integer $billetID The billet id.
     *
     * @return array A list of all commentaires for the billet.
     */
    public function findAllByBillet($billetId) {
        // The associated billet is retrieved only once
        $billet = $this->billetDAO->find($billetId);

        // art_id is not selected by the SQL query
        // The billet won't be retrieved during domain objet construction
        $sql = "SELECT id, user_id, contenu, date_creation FROM commentaires WHERE id_billet=? ORDER BY id";
        $result = $this->getDb()->fetchAll($sql, array($billetId));

        // Convert query result to an array of domain objects
        $commentaires = array();
        foreach ($result as $row) {
            $commentaireId = $row['id'];
            $commentaire = $this->buildDomainObject($row);
            // The associated billet is defined for the constructed commentaire
            $commentaire->setBillet($billet);
            $commentaires[$commentaireId] = $commentaire;
        }
        return $commentaires;
    }


    /**
     * Saves a commentaire into the database.
     *
     * @param \Manager\Commentaire $commentaire The commentaire to save
     */
    public function save(Commentaire $commentaire) {
        $commentaireData = array(
            'id_billet' => $commentaire->getBillet()->getId(),
            'user_id' => $commentaire->getAuteur()->getId(),
            'contenu' => $commentaire->getContenu(),
            );

        if ($commentaire->getId()) {
            // The commentaire has already been saved : update it
            $this->getDb()->update('commentaires', $commentaireData, array('id' => $commentaire->getId()));
        } else {
            // The commentaire has never been saved : insert it
            $this->getDb()->insert('commentaires', $commentaireData);
            // Get the id of the newly created commentaire and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $commentaire->setId($id);
        }
    }

    /**
     * Returns a Commentaire matching the supplied id.
     *
     * @param integer $id The Commentaire id
     *
     * @return \Manager\Commentaire|throws an exception if no matching Commentaire is found
     */
    public function find($id) {
        $sql = "SELECT * FROM commentaires WHERE id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No Commentaire matching id " . $id);
    }


    /**
     * Removes a Commentaire from the database.
     *
     * @param @param integer $id The Commentaire id
     */
    public function delete($id) {
        // Delete the Commentaire
        $this->getDb()->delete('commentaires', array('id' => $id));
    }

    /**
     * Removes all commentaires for a user
     *
     * @param integer $userId The id of the user
     */
    public function deleteAllByUser($userId) {
        $this->getDb()->delete('commentaires', array('user_id' => $userId));
    }


    /**
     * Removes all commentaires for an billet
     *
     * @param $billetId The id of the billet
     */
    public function deleteAllByBillet($billetId) {
        $this->getDb()->delete('commentaires', array('id_billet' => $billetId));
    }




    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \Manager\Commentaire
     */
    protected function buildDomainObject($row) {
        $commentaire = new Commentaire();
        $commentaire->setId($row['id']);
        $commentaire->setContenu($row['contenu']);
        $commentaire->setDateCreation($row['date_creation']);

        if (array_key_exists('id_billet', $row)) {
            // Find and set the associated billet
            $billetId = $row['id_billet'];
            $billet = $this->billetDAO->find($billetId);
            $commentaire->setBillet($billet);
        }
        if (array_key_exists('user_id', $row)) {
            // Find and set the associated author
            $userId = $row['user_id'];
            $user = $this->userDAO->find($userId);
            $commentaire->setAuteur($user);
        }

        return $commentaire;
    }
}
