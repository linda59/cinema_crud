<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Semeformation\Mvc\Cinema_crud\Views\View;
use Semeformation\Mvc\Cinema_crud\Models\Prefere;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;
use Semeformation\Mvc\Cinema_crud\Models\Film;


class FavoriteController {

    /**
     * L'utilisateur de l'application
     */
    private $prefere;
    private $utilisateur;
    //private $film;

    /**
     * Constructeur de la classe
     */
    public function __construct(\Psr\Log\LoggerInterface $logger = null) {
        $this->prefere = new Prefere($logger);
        $this->utilisateur = new Utilisateur($logger);
        $this->film = new Film($logger);
    }

    public function editFavoriteMovie() {
// si l'utilisateur n'est pas connecté
        if (!array_key_exists("user", $_SESSION)) {
            // renvoi à la page d'accueil
            header('Location: index.php');
            exit;
        }

// variable de contrôle de formulaire
        $aFilmIsSelected = true;
// variable qui sert à conditionner l'affichage du formulaire
        $isItACreation = false;

// si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_POST, ['backToList' => FILTER_DEFAULT,
                'filmID' => FILTER_SANITIZE_NUMBER_INT,
                'userID' => FILTER_SANITIZE_NUMBER_INT,
                'comment' => FILTER_SANITIZE_STRING,
                'modificationInProgress' => FILTER_SANITIZE_STRING]);

            // si l'action demandée est retour en arrière
            if ($sanitizedEntries['backToList'] !== NULL) {
                // on redirige vers la page d'édition des films favoris
                //header('Location: editFavoriteMoviesList.php');
                header("Location: index.php?action=editFavoriteMoviesList");
                exit;
            }
            // sinon (l'action demandée est la sauvegarde d'un favori)
            else {
                // si un film a été selectionné 
                if ($sanitizedEntries['filmID'] !== NULL) {

                    // et que nous ne sommes pas en train de modifier une préférence
                    if ($sanitizedEntries['modificationInProgress'] == NULL) {
                       
                        $this->prefere->insertNewFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID'], $sanitizedEntries['comment']);
                    }
                    // sinon, nous sommes dans le cas d'une modification
                    else {
                       
                        $this->prefere->updateFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID'], $sanitizedEntries['comment']);
                    }
                    // on revient à la liste des préférences
                    
                    header('Location: index.php?action=editFavoriteMoviesList');
                    exit;
                }
                // sinon (un film n'a pas été sélectionné)
                else {
                    // 
                    $aFilmIsSelected = false;
                    $isItACreation = true;
                    // initialisation des champs du formulaire
                    $preference = [
                        "userID" => $sanitizedEntries["userID"],
                        "filmID" => "",
                        "titre" => "",
                        "commentaire" => $sanitizedEntries["comment"]];
                    $userID = $sanitizedEntries['userID'];
                }
            }
// sinon (nous sommes en GET) et que l'id du film et l'id du user sont bien renseignés
        } elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT,
                'userID' => FILTER_SANITIZE_NUMBER_INT]);

            if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !== '' && $sanitizedEntries['userID'] !== NULL && $sanitizedEntries['userID'] !== '') {
                
                $preference = $this->prefere->getFavoriteMovieInformations($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
                // sinon, c'est une création
            } else {
                // C'est une création
                $isItACreation = true;
                // on initialise les autres variables de formulaire à vide
                $preference = [
                    "userID" => $_SESSION['userID'],
                    "filmID" => "",
                    "titre" => "",
                    "commentaire" => ""];
            }
        }
        $films = $this->prefere->getMoviesNonAlreadyMarkedAsFavorite($_SESSION['userID']);
        $vue = new View('FavoriteMovie');
        $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'films' => $films,
            'preference' => $preference,
            'aFilmIsSelected' => $aFilmIsSelected,
            'isItACreation' => $isItACreation]));
//    require 'views/viewFavoriteMovie.php';
    }
    
    public function editFavoriteMoviesList() {
// session_start();
// si l'utilisateur n'est pas connecté
    if (!array_key_exists("user", $_SESSION)) {
        // renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }
// l'utilisateur est loggué
    else {
        //$utilisateur = $fctManager->getCompleteUsernameByEmailAddress($_SESSION['user']);
        //$utilisateur = $utilisateursMgr->getCompleteUsernameByEmailAddress($_SESSION['user']);
        $utilisateur = $this->utilisateur->getCompleteUsernameByEmailAddress($_SESSION['user']);
    }
    $films = $this->prefere->getFavoriteMoviesFromUser($utilisateur['userID']);
    $nbfilms= $this->film->getMoviesList();
    $vue = new View('FavoriteMoviesList');
    $vue->generer((['utilisateur' => $utilisateur, 'films' => $films,'nbfilms'=>$nbfilms]));
//    require 'views/viewFavoriteMoviesList.php';
}

    public function deleteFavoriteMovie() {
        // si l'utilisateur n'est pas connecté
        if (!array_key_exists("user", $_SESSION)) {
// renvoi à la page d'accueil
            header('Location: index.php');
            exit;
        }

// si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_POST, ['userID' => FILTER_SANITIZE_NUMBER_INT,
                'filmID' => FILTER_SANITIZE_NUMBER_INT]);

           
            $this->prefere->deleteFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
        }
// redirection vers la liste des préférences de films

        header("Location: index.php?action=editFavoriteMoviesList");
        exit;
    }

}
