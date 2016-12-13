<?php

 require_once __DIR__ . '/vendor/autoload.php'; 
 
// init. des managers 
require_once __DIR__ . './includes/Manager.php';

// initialisation de l'application 
require_once __DIR__ . './init.php';

// appel au contrôleur serviteur
require __DIR__ . './controllers/controleur.php';

// on "sainifie" les entrées
session_start();
$sanitizedEntries = filter_input_array(INPUT_GET, ['action' => FILTER_SANITIZE_STRING]);

if ($sanitizedEntries && $sanitizedEntries['action'] !== '') {
// si l'action demandée est la liste des cinémas 
    switch ($sanitizedEntries['action']) {
        case "cinemasList":
// Activation de la route cinemasList
            cinemasList($managers);
            break;
        case "createUser":
            createNewUser($managers);
            break;
        case "cinemaShowtimes":
            cinemaShowtimes($managers);
            break;
        case "moviesList":
            moviesList($managers);
            break;
        case "movieShowtimes":
            movieShowtimes($managers);
            break;
        case "editShowtime":
            editShowtime($managers);
            break;
        case "deleteMovie":
            deleteMovie($managers);
            break;
        case "deleteShowtime":
            deleteShowtime($managers);
            break;
        case "deleteCinema":
            deleteCinema($managers);
            break;
        case "deleteFavoriteMovie":
            deleteFavoriteMovie($managers);
            break;
        case "editCinema":
            editCinema($managers);
            break;
        case "editMovie":
            editMovie($managers);
            break;
        case "editFavoriteMoviesList":
            editFavoriteMoviesList($managers);
            break;
        case "editFavoriteMovie":
            editFavoriteMovie($managers);
            break;
        default:
            // Activation de la route par défaut (page d'accueil) 
            home($managers);
    }
} else {
    // Activation de la route par défaut (page d'accueil) 
    home($managers);
}
 


/*
 require_once __DIR__ . '/vendor/autoload.php';
 

require_once __DIR__ . './includes/Manager.php';

// initialisation de l'application
require_once __DIR__ . './init.php';



session_start();
// personne d'authentifié à ce niveau
$loginSuccess = false;

// variables de contrôle du formulaire
$areCredentialsOK = true;

// si l'utilisateur est déjà authentifié
if (array_key_exists("user",
                $_SESSION)) {
    $loginSuccess = true;
// Sinon (pas d'utilisateur authentifié pour l'instant)
} else {
    // si la méthode POST a été employée
    if (filter_input(INPUT_SERVER,
                    'REQUEST_METHOD') === "POST") {
        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['email' => FILTER_SANITIZE_EMAIL,
            'password' => FILTER_DEFAULT]);
        try {
            // On vérifie l'existence de l'utilisateur
            //$fctManager->verifyUserCredentials($sanitizedEntries['email'],
            //        $sanitizedEntries['password']);
            
            $utilisateursMgr->verifyUserCredentials($sanitizedEntries['email'],
                   $sanitizedEntries['password']);

            // on enregistre l'utilisateur
            $_SESSION['user'] = $sanitizedEntries['email'];
            //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);
            //
            $_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
            // on redirige vers la page d'édition des films préférés
            header("Location: editFavoriteMoviesList.php");
            exit;
        } catch (Exception $ex) {
            $areCredentialsOK = false;
            $logger->error($ex->getMessage());
        }
    }
}
require 'views/viewHome.php';

*/

 
