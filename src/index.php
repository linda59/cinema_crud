<?php

 require_once __DIR__ . '/vendor/autoload.php'; 
 
// init. des managers 
require_once __DIR__ . '/includes/Manager.php';

// initialisation de l'application 
require_once __DIR__ . '/init.php';

// appel au contrôleur serviteur
require __DIR__ . '/controllers/controleur.php';

// on "sainifie" les entrées
session_start();
$sanitizedEntries = filter_input_array(INPUT_GET, ['action' => FILTER_SANITIZE_STRING]);
if ($sanitizedEntries && $sanitizedEntries['action'] !== '') {
// si l'action demandée est la liste des cinémas 
    if ($sanitizedEntries['action'] == "cinemasList") {
// Activation de la route cinemasList
        cinemasList($managers);
    } else if ($sanitizedEntries['action'] == "createUser") {
        createNewUser($managers);
    } else if ($sanitizedEntries['action'] == "cinemaShowtimes") {
        cinemaShowtimes($managers);
    }else if ($sanitizedEntries['action'] == "moviesList") {
         moviesList($managers);
    }else if ($sanitizedEntries['action'] == "movieShowtimes") {
        movieShowtimes($managers);
    }else if ($sanitizedEntries['action'] == "editShowtime") {
        editShowtime($managers);
    }else if ($sanitizedEntries['action'] == "deleteMovie") {
         deleteMovie($managers);
    }else if ($sanitizedEntries['action'] == "deleteShowtime"){
           deleteShowtime($managers);
    }else if ($sanitizedEntries['action'] == "deleteCinema"){
           deleteCinema($managers);
    }else if ($sanitizedEntries['action'] == "deleteFavoriteMovie"){       
           deleteFavoriteMovie($managers);
    }else if ($sanitizedEntries['action'] == "editCinema") {
        editCinema($managers); 
    }else if ($sanitizedEntries['action'] == "editMovie") {
        editMovie($managers);
    }else if ($sanitizedEntries['action'] == "editFavoriteMoviesList") {
         editFavoriteMoviesList($managers);    
    }  else if ($sanitizedEntries['action'] == "editFavoriteMovie") {
         editFavoriteMovie($managers);
    } else {
        // Activation de la route par défaut (page d'accueil) 
        home($managers);
    }
} else {
    // Activation de la route par défaut (page d'accueil) 
    home($managers);
}
 


/*
 require_once __DIR__ . '/vendor/autoload.php';
 

require_once __DIR__ . '/includes/Manager.php';

// initialisation de l'application
require_once __DIR__ . '/init.php';



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

 
