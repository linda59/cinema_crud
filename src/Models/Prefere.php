<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;

/**
 * Description of Prefere
 *
 * @author admin
 */
class Prefere {

    private $commentaire;
    private $film;
    private $utilisateur;


    /**
     * Get the value of Description of Prefere
     *
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set the value of Description of Prefere
     *
     * @param mixed commentaire
     *
     * @return self
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }


    /**
     * Get the value of Film
     *
     * @return mixed
     */
    public function getFilm()
    {
        return $this->film;
    }

    /**
     * Set the value of Film
     *
     * @param mixed film
     *
     * @return self
     */
    public function setFilm($film)
    {
        $this->film = $film;

        return $this;
    }

    /**
     * Get the value of Utilisateur
     *
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set the value of Utilisateur
     *
     * @param mixed utilisateur
     *
     * @return self
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

}
