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
            $this->getDb()->update('commentaires', $commentaireData, array('com_id' => $commentaire->getId()));
        } else {
            // The commentaire has never been saved : insert it
            $this->getDb()->insert('commentaires', $commentaireData);
            // Get the id of the newly created commentaire and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $commentaire->setId($id);
        }
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
            $commentaire->setArticle($billet);
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
