<?php

namespace Manager;

class Commentaire
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment auteur.
     *
     * @var Manager\User
     */
    private $auteur;

    /**
     * Comment contenu.
     *
     * @var integer
     */
    private $contenu;

    /**
     * Comment date creation.
     *
     * @var integer
     */
    private $datecreation;


    /**
     * Associated billet.
     *
     * @var Manager\Billet
     */
    private $billet;


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAuteur() {
        return $this->auteur;
    }

    public function setAuteur(User $auteur) {
        $this->auteur = $auteur;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function getDateCreation() {
        return $this->datecreation;
    }

    public function setDateCreation($datecreation) {
        $this->datecreation = $datecreation;
    }

    public function getBillet() {
        return $this->billet;
    }

    public function setBillet(Billet $billet) {
        $this->billet = $billet;
    }
}
