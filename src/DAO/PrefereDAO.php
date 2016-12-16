<?php
namespace Semeformation\Mvc\Cinema_crud\DAO;
use \Exception;
/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
Use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\DAO\UtilisateurDAO;
use Semeformation\Mvc\Cinema_crud\DAO\FilmDAO;
use Semeformation\Mvc\Cinema_crud\Models\Prefere;

/**
* Description of UtilisateurDAO
*
* @author admin
*/
class PrefereDAO extends DAO{

    private $filmDAO;
    private $utilisateurDAO;

    function __construct(){
        $this->filmDAO = new FilmDAO();
        $this->utilisateurDAO = new UtilisateurDAO();

    }

    /**
    * Crée une préférence à partir d'une ligne de la BDD.
    *
    * @param array $row La ligne de résultat de la BDD.
    * @return Prefere
    */
    protected function buildBusinessObject($row) {
        $prefere = new Prefere();
        var_dump($prefere);
        $prefere->setCommentaire($row['commentaire']);
        // trouver l'utilisateur concerné grâce à son identifiant
        if (array_key_exists('userID', $row)) {
            $userId = $row['userID'];
            $utilisateur = $this->utilisateurDAO->getUserByID($userId);
            $prefere->setUtilisateur($utilisateur);
        }
        // trouver le film concerné grâce à son identifiant
        if (array_key_exists('filmID', $row)) {
            $filmId = $row['filmID'];
            $film = $this->filmDAO->getMovieInformationsByID($filmId);
            $prefere->setFilm($film);
        }
        return $prefere;
    }


    protected function buildBusinessObjects($row) {
        $preferes = array();
        foreach ($row as $value) {
            $preferes[]= $this->buildBusinessObject($value);
        }
        return $preferes;
    }


    /**
    * Méthode qui retourne les films préférés d'un utilisateur donné
    * @param string $utilisateur Adresse email de l'utilisateur
    * @return array[][] Les films préférés (sous forme de tableau associatif) de l'utilisateur
    */
    public function getFavoriteMoviesFromUser($id) {
        // on construit la requête qui va récupérer les films de l'utilisateur
        $requete = "SELECT f.filmID, f.titre, p.commentaire, p.userID from film f" .
        " INNER JOIN prefere p ON f.filmID = p.filmID" .
        " AND p.userID = " . $id;

        // on extrait le résultat de la BDD sous forme de tableau associatif
        $resultat = $this->extraireNxN($requete, null, false);

        $prefere = $this->buildBusinessObjects($resultat);
        // on retourne le résultat
        return $prefere;
    }

    /**
    * Méthode qui renvoie les informations sur un film favori donné pour un utilisateur donné
    * @param int $userID Identifiant de l'utilisateur
    * @param int $filmID Identifiant du film
    * @return array[]
    */
    public function getFavoriteMovieInformations($userID, $filmID) {
        // requête qui récupère les informations d'une préférence de film pour un utilisateur donné
        $requete = "SELECT f.titre, p.userID, p.filmID, p.commentaire"
        . " FROM prefere p INNER JOIN film f ON p.filmID = f.filmID"
        . " WHERE p.userID = "
        . $userID
        . " AND p.filmID = "
        . $filmID;

        // on extrait les résultats de la BDD
        $resultat = $this->extraire1xN($requete, null, false);
        // on retourne le résultat
        return $resultat;
    }

    /**
    * Méthode qui met à jour une préférence de film pour un utilisateur
    * @param int userID Identifiant de l'utilisateur
    * @param int filmID Identifiant du film
    * @param string comment Commentaire de l'utilisateur à propos de ce film
    */
    public function updateFavoriteMovie($userID, $filmID, $comment) {
        // on construit la requête d'insertion
        $requete = "UPDATE prefere SET commentaire = "
        . "'" . $comment . "'"
        . " WHERE filmID = "
        . $filmID
        . " AND userID = "
        . $userID;
        // exécution de la requête
        $this->executeQuery($requete);
    }



    /**
    * Méthode qui ne renvoie que les titres et ID de films non encore marqués
    * comme favoris par l'utilisateur passé en paramètre
    * @param int $userID Identifiant de l'utilisateur
    * @return array[][] Titres et ID des films présents dans la base
    */
    public function getMoviesNonAlreadyMarkedAsFavorite($userID) {
        // requête de récupération des titres et des identifiants des films
        // qui n'ont pas encore été marqués comme favoris par l'utilisateur
        $requete = "SELECT f.filmID, f.titre "
        . "FROM film f"
        . " WHERE f.filmID NOT IN ("
        . "SELECT filmID"
        . " FROM prefere"
        . " WHERE userID = :id"
        . ")";
        // extraction de résultat
        $resultat = $this->extraireNxN($requete, ['id' => $userID], false);
        // retour du résultat
        return $resultat;
    }



    /**
    * Méthode qui ajoute une préférence de film à un utilisateur
    * @param int userID Identifiant de l'utilisateur
    * @param int filmID Identifiant du film
    * @param string comment Commentaire de l'utilisateur à propos de ce film
    */
    public function insertNewFavoriteMovie($userID, $filmID, $comment = "") {
        // on construit la requête d'insertion
        $requete = "INSERT INTO prefere (filmID, userID, commentaire) VALUES ("
        . ":filmID"
        . ", :userID"
        . ", :comment)";

        // exécution de la requête
        $this->executeQuery($requete,
        ['filmID' => $filmID,
        'userID' => $userID,
        'comment' => $comment]);

        if ($this->logger) {
            $this->logger->info('Movie ' . $filmID . ' successfully added to ' . $userID . '\'s preferences.');
        }
    }


    /**
    *
    * @param type $userID
    * @param type $filmID
    */
    public function deleteFavoriteMovie($userID, $filmID) {
        $this->executeQuery("DELETE FROM prefere WHERE userID = "
        . $userID
        . " AND filmID = "
        . $filmID);

        if ($this->logger) {
            $this->logger->info('Movie ' . $filmID . ' successfully deleted from ' . $userID . '\'s preferences.');
        }
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


}
