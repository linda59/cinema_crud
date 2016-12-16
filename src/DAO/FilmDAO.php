<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\DAO;

use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Film;

/**
 * Description of FilmDAO
 *
 * @author admin
 */
class FilmDAO extends DAO {

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
    public function verifierFilm($titre, $titreOriginal = null,$dateSortie) {


         $requete = "SELECT * FROM film WHERE titre = '". $titre."' and titreOriginal ='". $titreOriginal."'  and dateSortie ='". $dateSortie."'";
        $resultat = $this->extraire1xN($requete);
        // on retourne le résultat extrait
        return $resultat;

    }


    /**
     *
     * @param type $titre
     * @param type $titreOriginal
     */
    public function insertNewMovie($titre, $titreOriginal = null,$dateSortie) {
        // construction
        $requete = "INSERT INTO film (titre, titreOriginal, dateSortie) VALUES (" . ":titre". ", :titreOriginal". ",:dateSortie)";
        // exécution
        $this->executeQuery($requete,
                ['titre' => $titre,
            'titreOriginal' => $titreOriginal,'dateSortie' => $dateSortie]);
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
    public function updateMovie($filmID, $titre, $titreOriginal,$dateSortie) {
        // on construit la requête d'insertion
        $requete = "UPDATE film SET "
                . "titre = "
                . "'" . $titre . "'"
                . ", titreOriginal = "
                . "'" . $titreOriginal . "'"
                 . ", dateSortie = "
                 . "'" . $dateSortie . "'"
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

        // On construit l'objet Film :
        $film = $this->buildFilm($resultat);

        // on retourne le film
        return $film;
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

     /**
     * Méthode qui instancie un objet Film et qui le retourne.
     * @param array $row  un tableau résultat d’une requête SELECT
     */
    public function buildFilm($row){
        $film = new Film();
        $film->setFilmId($row['FILMID']);
        $film->setTitre($row['TITRE']);
        $film->setDateSortie($row['DATESORTIE']);
        $film->setTitreOriginal($row['TITREORIGINAL']);
        // $film->setClassification($row['CLASSIFICATION']);
        return $film;
    }
}
