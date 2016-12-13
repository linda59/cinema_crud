<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\models;

use Semeformation\Mvc\Cinema_crud\includes\DBFunctions;

/**
 * Description of Film
 *
 * @author admin
 */
class Film extends DBFunctions{
    /**
     * Méthode qui renvoie la liste des films
     * @return array[][]
     */
    public function getMoviesList() {
        $requete = "SELECT * FROM film";
        // on retourne le résultat
        return $this->extraireNxN($requete, null, false);
    }

    /**
     * 
     * @param type $titre
     * @param type $titreOriginal
     */
    public function insertNewMovie($titre, $titreOriginal = null) {
        // construction
        $requete = "INSERT INTO film (titre, titreOriginal) VALUES ("
                . ":titre"
                . ", :titreOriginal)";
        // exécution
        $this->executeQuery($requete,
                ['titre' => $titre,
            'titreOriginal' => $titreOriginal]);
        // log
        if ($this->logger) {
            $this->logger->info('Movie ' . $titre . ' successfully added.');
        }
    }
    
     /**
     * 
     * @param type $filmID
     * @param type $titre
     * @param type $titreOriginal
     */
    public function updateMovie($filmID, $titre, $titreOriginal) {
        // on construit la requête d'insertion
        $requete = "UPDATE film SET "
                . "titre = "
                . "'" . $titre . "'"
                . ", titreOriginal = "
                . "'" . $titreOriginal . "'"
                . " WHERE filmID = "
                . $filmID;
        // exécution de la requête
        $this->executeQuery($requete);
    }
    
    
     /**
     * 
     * @param type $movieID
     */
    public function deleteMovie($movieID) {
         $this->executeQuery("DELETE FROM seance WHERE filmID = "
                        . $movieID);
        $this->executeQuery("DELETE FROM prefere WHERE filmID = "
                        . $movieID);
        $this->executeQuery("DELETE FROM film WHERE filmID = "
                . $movieID);
       
        
        
        if ($this->logger) {
            $this->logger->info('Movie ' . $movieID . ' successfully deleted.');
        }
    }
    
    
     /**
     * 
     * @param type $filmID
     * @return type
     */
    public function getMovieInformationsByID($filmID) {
        $requete = "SELECT * FROM film WHERE filmID = "
                . $filmID;
        $resultat = $this->extraire1xN($requete);
        // on retourne le résultat extrait
        return $resultat;
    }

    
     /**
     * 
     * @param type $filmID
     * @return type
     */
    public function getMovieCinemasByMovieID($filmID) {
        // requête qui nous permet de récupérer la liste des cinémas pour un film donné
        $requete = "SELECT DISTINCT c.* FROM cinema c"
                . " INNER JOIN seance s ON c.cinemaID = s.cinemaID"
                . " AND s.filmID = " . $filmID;
        // on extrait les résultats
        $resultat = $this->extraireNxN($requete);
        // on retourne le résultat
        return $resultat;
    }


}
