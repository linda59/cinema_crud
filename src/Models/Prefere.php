<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;
use Semeformation\Mvc\Cinema_crud\Includes\DAO;
/**
 * Description of Prefere
 *
 * @author admin
 */
class Prefere  {
      private $filmId;
     private $userId;
      private $commmentaire;



    /**
     * Get the value of Description of UtilisateurDAO
     *
     * @return mixed
     */
    public function getFilmId()
    {
        return $this->filmId;
    }

    /**
     * Set the value of Description of UtilisateurDAO
     *
     * @param mixed filmDAO
     *
     * @return self
     */
    public function setFilmId($filmId)
    {
        $this->filmId = $filmId;

        
    }

    /**
     * Get the value of Utilisateur
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->$userId;
    }

    /**
     * Set the value of Utilisateur
     *
     * @param mixed utilisateurDAO
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

      
    }
    
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set the value of Utilisateur
     *
     * @param mixed utilisateurDAO
     *
     * @return self
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

      
    }

   

}
