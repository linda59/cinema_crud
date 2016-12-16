<?php
namespace Semeformation\Mvc\Cinema_crud\DAO;
use \Exception;
/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
Use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;
/**
* Description of UtilisateurDAO
*
* @author admin
*/
class Prefere extends DAO{

    private $filmDAO;
    private $utilisateurDAO;

    function __construct(){
        # code...
    }



    /**
     * Get the value of Description of UtilisateurDAO
     *
     * @return mixed
     */
    public function getFilmDAO()
    {
        return $this->filmDAO;
    }

    /**
     * Set the value of Description of UtilisateurDAO
     *
     * @param mixed filmDAO
     *
     * @return self
     */
    public function setFilmDAO($filmDAO)
    {
        $this->filmDAO = $filmDAO;

        return $this;
    }

    /**
     * Get the value of Utilisateur
     *
     * @return mixed
     */
    public function getUtilisateurDAO()
    {
        return $this->utilisateurDAO;
    }

    /**
     * Set the value of Utilisateur
     *
     * @param mixed utilisateurDAO
     *
     * @return self
     */
    public function setUtilisateurDAO($utilisateurDAO)
    {
        $this->utilisateurDAO = $utilisateurDAO;

        return $this;
    }

    public function buildBusinessObject($row) {
        
    }

}
