<?php

namespace Manager;

class Billet
{
    /**
     * Billet id.
     *
     * @var integer
     */
    private $id;

    /**
     * Billet titre.
     *
     * @var string
     */
    private $titre;

    /**
     * Billet contenu.
     *
     * @var string
     */
    private $contenu;

    /**
     * Billet date de création.
     *
     * @var DateTime
     */
    private $datecreation;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
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
}
