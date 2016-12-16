<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;

use Semeformation\Mvc\Cinema_crud\Includes\DAO;

/**
 * Description of Film
 *
 * @author admin
 */
class Film {
    
    private $filmId ;
    private $titre ;
    private $dateSortie;
    private $titreOriginal;
    private $classification;
    
    /*
     * Getteurs et setteurs des attributs pour le passage en DAO (POPO)
     */
    function getFilmId() {
        return $this->filmId;
    }

    function getTitre() {
        return $this->titre;
    }

    function getDateSortie() {
        return $this->dateSortie;
    }
     
    function getTitreOriginal() {
        return $this->titreOriginal;
    }

    function getClassification() {
        return $this->classification;
    }

    function setFilmId($filmId) {
        $this->filmId = $filmId;
    }
    
    function setTitre($titre) {
        $this->titre = $titre;
    }

    function setDateSortie($dateSortie) {
        $this->dateSortie = $dateSortie;
    }
    
    function setTitreOriginal($titreOriginal) {
        $this->titreOriginal = $titreOriginal;
    }

    function setClassification($classification) {
        $this->classification = $classification;
    }

}
