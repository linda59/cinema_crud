<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Semeformation\Mvc\Cinema_crud\Views\View;
use Semeformation\Mvc\Cinema_crud\DAO\FilmDAO;
use \Psr\Log\LoggerInterface;

/**
 * Description of MovieController
 *
 * @author admin
 */
class MovieController {
    private $movieDAO;

    /**
     * Constructeur de la classe
     */
    public function __construct(LoggerInterface $logger=null) {
        $this->movieDAO = new FilmDAO($logger);
    }


    function deleteMovie() {
        // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
        if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
            // renvoi à la page d'accueil
            header('Location: index.php');
            exit;
        }

// si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_POST, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);

            // suppression de la préférence de film
            //$fctManager->deleteMovie($sanitizedEntries['filmID']);
            //$fctFilm->deleteMovie($sanitizedEntries['filmID']);
            $this->movieDAO->deleteMovie($sanitizedEntries['filmID']);
        }
// redirection vers la liste des films
//header("Location: moviesList.php");
        header("Location: index.php?action=moviesList");
        exit;
    }

    function editMovie() {
        // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
        if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
// renvoi à la page d'accueil
            header('Location: index.php');
            exit;
        }

// variable qui sert à conditionner l'affichage du formulaire
        $isItACreation = false;

// si la méthode de formulaire est la méthode POST
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

            // on "sainifie" les entrées
            $sanEntries = filter_input_array(INPUT_POST, ['backToList' => FILTER_DEFAULT,
                'filmID' => FILTER_SANITIZE_NUMBER_INT,
                'titre' => FILTER_SANITIZE_STRING,
                'titreOriginal' => FILTER_SANITIZE_STRING,
                'dateSortie' => FILTER_SANITIZE_STRING,
                'modificationInProgress' => FILTER_SANITIZE_STRING]);

            // si l'action demandée est retour en arrière
            if ($sanEntries['backToList'] !== NULL) {
                // on redirige vers la page des films
                //header('Location: moviesList.php');
                header('Location: index.php?action=moviesList');
                exit;
            }
            // sinon (l'action demandée est la sauvegarde d'un film)
            else {

                // et que nous ne sommes pas en train de modifier un film
                if ($sanEntries['modificationInProgress'] == NULL) {
                    // on ajoute le film
                    //$fctManager->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal']);
                    //$fctFilm->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal']);
                    $verifieFilm = $this->movieDAO->verifierFilm($sanEntries['titre'], $sanEntries['titreOriginal'], $sanEntries['dateSortie']);
                    if (empty($verifieFilm))
                        $this->movieDAO->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal'], $sanEntries['dateSortie']);
                }
                // sinon, nous sommes dans le cas d'une modification
                else {
                    // mise à jour du film
                    //$fctManager->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                    //$fctFilm->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                    $this->movieDAO->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal'], $sanEntries['dateSortie']);
                }
                // on revient à la liste des films
                //header('Location: moviesList.php');
                header('Location: index.php?action=moviesList');
                exit;
            }
        }// si la page est chargée avec $_GET
        elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {
            // on "sainifie" les entrées
            $sanEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
            if ($sanEntries && $sanEntries['filmID'] !== NULL && $sanEntries['filmID'] !== '') {
                // on récupère les informations manquantes
                //$film = $fctManager->getMovieInformationsByID($sanEntries['filmID']);
                //$film = $fctFilm->getMovieInformationsByID($sanEntries['filmID']);
                $film = $this->movieDAO->getMovieInformationsByID($sanEntries['filmID']);
            }
            // sinon, c'est une création
            else {
                $isItACreation = true;
                $film = [
                    'FILMID' => '',
                    'TITRE' => '',
                    'TITREORIGINAL' => '',
                    'DATESORTIE' => ''
                ];
            }
        }
        //require 'views/viewEditMovie.php';
        $vue = new View('EditMovie');
        $vue->generer((['film' => $film,
            'isItACreation' => $isItACreation]));
    }

    function moviesList() {
        $isUserAdmin = false;

//session_start();
// si l'utilisateur est pas connecté et qu'il est amdinistrateur
        if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
            $isUserAdmin = true;
        }
        $films = $this->movieDAO->getMoviesList();
        $vue = new View('MoviesList');
        $vue->generer((['isUserAdmin' => $isUserAdmin, 'films' => $films]));
//    require 'views/viewMoviesList.php';
    }

}
