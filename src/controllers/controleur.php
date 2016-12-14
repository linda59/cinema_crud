<?php

use Semeformation\Mvc\Cinema_crud\views\View;

function editFavoriteMoviesList($managers) {
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
        $utilisateur = $managers["utilisateursMgr"]->getCompleteUsernameByEmailAddress($_SESSION['user']);
    }
    $films = $managers["preferesMgr"]->getFavoriteMoviesFromUser($utilisateur['userID']);
    $nbfilms = $managers['filmsMgr']->getMoviesList();
    $vue = new View('FavoriteMoviesList');
    $vue->generer((['utilisateur' => $utilisateur, 'films' => $films, 'nbfilms' => $nbfilms]));
//    require 'views/viewFavoriteMoviesList.php';
}

function editFavoriteMovie($managers) {
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
                    // on ajoute la préférence de l'utilisateur
                    /* $fctManager->insertNewFavoriteMovie($sanitizedEntries['userID'],
                      $sanitizedEntries['filmID'],
                      $sanitizedEntries['comment']);
                     * */
                    /*
                      $fctPrefere->insertNewFavoriteMovie($sanitizedEntries['userID'],
                      $sanitizedEntries['filmID'],
                      $sanitizedEntries['comment']);
                     * 
                     */
                    $managers["preferesMgr"]->insertNewFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID'], $sanitizedEntries['comment']);
                }
                // sinon, nous sommes dans le cas d'une modification
                else {
                    // mise à jour de la préférence
                    /*
                      $fctManager->updateFavoriteMovie($sanitizedEntries['userID'],
                      $sanitizedEntries['filmID'],
                      $sanitizedEntries['comment']);
                     *  */

                    /*
                      $fctPrefere->updateFavoriteMovie($sanitizedEntries['userID'],
                      $sanitizedEntries['filmID'],
                      $sanitizedEntries['comment']);
                     */
                    $managers["preferesMgr"]->updateFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID'], $sanitizedEntries['comment']);
                }
                // on revient à la liste des préférences
                //header('Location: editFavoriteMoviesList.php');
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
            // on récupère les informations manquantes (le commentaire afférent)
            /* $preference = $fctManager->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             * */
            /*
              $preference = $fctPrefere->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             */
            $preference = $managers["preferesMgr"]->getFavoriteMovieInformations($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
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
    $films = $managers["preferesMgr"]->getMoviesNonAlreadyMarkedAsFavorite($_SESSION['userID']);
    $vue = new View('FavoriteMovie');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'films' => $films,
        'preference' => $preference,
        'aFilmIsSelected' => $aFilmIsSelected,
        'isItACreation' => $isItACreation]));
//    require 'views/viewFavoriteMovie.php';
}

function cinemaShowtimes($managers) {
    $adminConnected = false;

    //  session_start();
// si l'utilisateur admin est connexté
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $adminConnected = true;
    }

// si la méthode de formulaire est la méthode GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on assainie les entrées
        $sanitizedEntries = filter_input_array(INPUT_GET, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // si l'identifiant du cinéma a bien été passé en GET
        if ($sanitizedEntries && $sanitizedEntries['cinemaID'] !== NULL && $sanitizedEntries['cinemaID'] != '') {
            // on récupère l'identifiant du cinéma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // puis on récupère les informations du cinéma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
            // on récupère les films pas encore projetés
            //$filmsUnplanned = $fctManager->getNonPlannedMovies($cinemaID);
            //$filmsUnplanned = $fctSeance->getNonPlannedMovies($cinemaID);
            $filmsUnplanned = $managers["seancesMgr"]->getNonPlannedMovies($cinemaID);
        }
        // sinon, on retourne à l'accueil
        else {
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: index.php');
        exit();
    }
    $films = $managers["seancesMgr"]->getCinemaMoviesByCinemaID($cinemaID);
    foreach ($films as $film) {
        $seances[$film['FILMID']] = $managers["seancesMgr"]->getMovieShowtimes($cinemaID, $film['FILMID']);
    }
    $vue = new View('CinemaShowtimes');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'films' => $films,
        'seances' => $seances,
        'cinemaID' => $cinemaID,
        'cinema' => $cinema,
        'adminConnected' => $adminConnected,
        'filmsUnplanned' => $filmsUnplanned]));
//    require 'views/viewCinemaShowtimes.php';
}

function movieShowtimes($managers) {
    $adminConnected = false;

    //session_start();
// si l'utilisateur admin est connexté
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $adminConnected = true;
    }

// si la méthode de formulaire est la méthode GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        // si l'identifiant du film a bien été passé en GET'
        if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !== '') {
            // on récupère l'identifiant du cinéma
            $filmID = $sanitizedEntries['filmID'];
            // puis on récupère les informations du film en question
            // $film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($filmID);
            // on récupère les cinémas qui ne projettent pas encore le film
            //$cinemasUnplanned = $fctManager->getNonPlannedCinemas($filmID);
            //$cinemasUnplanned = $fctSeance->getNonPlannedCinemas($filmID);
            $cinemasUnplanned = $managers["seancesMgr"]->getNonPlannedCinemas($filmID);
        }
        // sinon, on retourne à l'accueil
        else {
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: index.php');
        exit();
    }
    $cinemas = $managers["filmsMgr"]->getMovieCinemasByMovieID($filmID);

    if (count($cinemas) > 0):
        foreach ($cinemas as $cinema) {
            $seances[$cinema['CINEMAID']] = $managers["seancesMgr"]->getMovieShowtimes($cinema['CINEMAID'], $filmID);
        }
    endif;

    $vue = new View('MovieShowtimes');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'cinemas' => $cinemas,
        'seances' => $seances,
        'filmID' => $filmID,
        'adminConnected' => $adminConnected,
        'film' => $film,
        'cinemasUnplanned' => $cinemasUnplanned]));
//    require 'views/viewMovieShowtimes.php';
}

function editShowtime($managers) {
    // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
// renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }

// init. des flags. Etat par défaut => je viens du cinéma et je créé
    $fromCinema = true;
    $fromFilm = false;
    $isItACreation = true;

// init. des variables du formulaire
    $seance = ['dateDebut' => '',
        'heureDebut' => '',
        'dateFin' => '',
        'heureFin' => '',
        'dateheureDebutOld' => '',
        'dateheureFinOld' => '',
        'heureFinOld' => '',
        'version' => ''];

// si l'on est en GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'GET') {
        // on assainie les variables
        $sanitizedEntries = filter_input_array(INPUT_GET, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'from' => FILTER_SANITIZE_STRING,
            'heureDebut' => FILTER_SANITIZE_STRING,
            'heureFin' => FILTER_SANITIZE_STRING,
            'version' => FILTER_SANITIZE_STRING]);
        // pour l'instant, on vérifie les données en GET
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['from'])) {
            // on récupère l'identifiant du cinéma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // l'identifiant du film
            $filmID = $sanitizedEntries['filmID'];
            // d'où vient on ?
            $from = $sanitizedEntries['from'];
            // puis on récupère les informations du cinéma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
            // puis on récupère les informations du film en question
            //$film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($filmID);

            // s'il on vient des séances du film
            if (strstr($sanitizedEntries['from'], 'movie')) {
                $fromCinema = false;
                // on vient du film
                $fromFilm = true;
            }

            // ici, on veut savoir si on modifie ou si on ajoute
            if (isset($sanitizedEntries['heureDebut'], $sanitizedEntries['heureFin'], $sanitizedEntries['version'])) {
                // nous sommes dans le cas d'une modification
                $isItACreation = false;
                // on récupère les anciennes valeurs (utile pour retrouver la séance avant de la modifier
                $seance['dateheureDebutOld'] = $sanitizedEntries['heureDebut'];
                $seance['dateheureFinOld'] = $sanitizedEntries['heureFin'];
                // dates PHP
                $dateheureDebut = new DateTime($sanitizedEntries['heureDebut']);
                $dateheureFin = new DateTime($sanitizedEntries['heureFin']);
                // découpage en heures
                $seance['heureDebut'] = $dateheureDebut->format("H:i");
                $seance['heureFin'] = $dateheureFin->format("H:i");
                // découpage en jour/mois/année
                $seance['dateDebut'] = $dateheureDebut->format("d/m/Y");
                $seance['dateFin'] = $dateheureFin->format("d/m/Y");
                // on récupère la version
                $seance['version'] = $sanitizedEntries['version'];
            }
        }
        // sinon, on retourne à l'accueil
        else {
            header('Location: index.php');
            exit();
        }
// sinon, on est en POST
    } else if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST') {
        // on assainie les variables
        $sanitizedEntries = filter_input_array(INPUT_POST, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'datedebut' => FILTER_SANITIZE_STRING,
            'heuredebut' => FILTER_SANITIZE_STRING,
            'datefin' => FILTER_SANITIZE_STRING,
            'heurefin' => FILTER_SANITIZE_STRING,
            'dateheurefinOld' => FILTER_SANITIZE_STRING,
            'dateheuredebutOld' => FILTER_SANITIZE_STRING,
            'version' => FILTER_SANITIZE_STRING,
            'from' => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);
        // si toutes les valeurs sont renseignées
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['datedebut'], $sanitizedEntries['heuredebut'], $sanitizedEntries['datefin'], $sanitizedEntries['heurefin'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $sanitizedEntries['version'], $sanitizedEntries['from'])) {
            // nous sommes en Français
            setlocale(LC_TIME, 'fra_fra');
            // date du jour de projection de la séance
            // Correction bug #3
            // AVANT
            //$datetimeDebut = new DateTime($sanitizedEntries['datedebut'] . ' ' . $sanitizedEntries['heuredebut']);
            //$datetimeFin = new DateTime($sanitizedEntries['datefin'] . ' ' . $sanitizedEntries['heurefin']);
            // APRES
            $datetimeDebut = DateTime::createFromFormat('d/m/Y H:i', $sanitizedEntries['datedebut'] . ' ' . $sanitizedEntries['heuredebut']);
            $datetimeFin = DateTime::createFromFormat('d/m/Y H:i', $sanitizedEntries['datefin'] . ' ' . $sanitizedEntries['heurefin']);
            // Fin correction bug #3
            // Est-on dans le cas d'une insertion ?
            if (!isset($sanitizedEntries['modificationInProgress'])) {
                // j'insère dans la base
                /*
                  $resultat = $fctManager->insertNewShowtime($sanitizedEntries['cinemaID'],
                  $sanitizedEntries['filmID'],
                  $datetimeDebut->format("Y-m-d H:i"),
                  $datetimeFin->format("Y-m-d H:i"),
                  $sanitizedEntries['version']);
                 * */
                //$resultat = $fctSeance->insertNewShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
                // Le try/catch permet de corriger la contrainte des clés primaires et étrangères
                // (cas où l'ajout/mise à jour correspond à une séance déjà existante)
                try {
                    $resultat = $managers["seancesMgr"]->insertNewShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
            } else {
                // c'est une mise à jour
                /*
                  $resultat = $fctManager->updateShowtime($sanitizedEntries['cinemaID'],
                  $sanitizedEntries['filmID'],
                  $sanitizedEntries['dateheuredebutOld'],
                  $sanitizedEntries['dateheurefinOld'],
                  $datetimeDebut->format("Y-m-d H:i"),
                  $datetimeFin->format("Y-m-d H:i"),
                  $sanitizedEntries['version']);
                 * */
                // $resultat = $fctSeance->updateShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
                // Le try/catch permet de corriger la contrainte des clés primaires et étrangères
                // (cas où l'ajout/mise à jour correspond à une séance déjà existante) 
                try {
                    $resultat = $managers["seancesMgr"]->updateShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
            }
            // en fonction d'où je viens, je redirige
            if (strstr($sanitizedEntries['from'], 'movie')) {
                //header('Location: movieShowtimes.php?filmID=' . $sanitizedEntries['filmID']);
                header('Location: index.php?action=movieShowtimes&filmID=' . $sanitizedEntries['filmID']);
                exit;
            } else {
                //header('Location: cinemaShowtimes.php?cinemaID=' . $sanitizedEntries['cinemaID']);
                header('Location: index.php?action=cinemaShowtimes&cinemaID=' . $sanitizedEntries['cinemaID']);
                exit;
            }
        }
    }
// sinon, on retourne à l'accueil
    else {
        header('Location: index.php');
        exit();
    }
    $vue = new View('EditShowtimes');
    $vue->generer((['seance' => $seance,
        'film' => $film,
        'cinema' => $cinema,
        'from' => $from,
        'cinemaID' => $cinemaID,
        'filmID' => $filmID,
        'isItACreation' => $isItACreation,
        'fromCinema' => $fromCinema]));
//    require 'views/viewEditShowtimes.php';
}

function deleteShowtime($managers) {
    // si l'utilisateur n'est pas connecté
    if (!array_key_exists("user", $_SESSION)) {
// renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la méthode de formulaire est la méthode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on assainie les variables
        $sanitizedEntries = filter_input_array(INPUT_POST, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'heureDebut' => FILTER_SANITIZE_STRING,
            'heureFin' => FILTER_SANITIZE_STRING,
            'version' => FILTER_SANITIZE_STRING,
            'from' => FILTER_SANITIZE_STRING
        ]);

        // suppression de la séance
        /*
          $fctManager->deleteShowtime($sanitizedEntries['cinemaID'],
          $sanitizedEntries['filmID'], $sanitizedEntries['heureDebut'],
          $sanitizedEntries['heureFin']
          );
         * */
        /*
          $fctSeance->deleteShowtime($sanitizedEntries['cinemaID'],
          $sanitizedEntries['filmID'], $sanitizedEntries['heureDebut'],
          $sanitizedEntries['heureFin']
          );
         * 
         */
        $managers["seancesMgr"]->deleteShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['heureDebut'], $sanitizedEntries['heureFin']
        );
        // en fonction d'où je viens, je redirige
        if (strstr($sanitizedEntries['from'], 'movie')) {
            //header('Location: movieShowtimes.php?filmID=' . $sanitizedEntries['filmID']);
            header('Location: index.php?action=movieShowtimes&filmID=' . $sanitizedEntries['filmID']);
            exit;
        } else {
            //header('Location: cinemaShowtimes.php?cinemaID=' . $sanitizedEntries['cinemaID']);
            header('Location: index.php?action=cinemaShowtimes&cinemaID=' . $sanitizedEntries['cinemaID']);
            exit;
        }
    } else {
        // renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }
}

function deleteFavoriteMovie($managers) {
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

        // suppression de la préférence de film
        // $fctManager->deleteFavoriteMovie($sanitizedEntries['userID'],
        //         $sanitizedEntries['filmID']);
        /*
          $fctPrefere->deleteFavoriteMovie($sanitizedEntries['userID'],
          $sanitizedEntries['filmID']);
         */
        $managers["preferesMgr"]->deleteFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
    }
// redirection vers la liste des préférences de films
//header("Location: editFavoriteMoviesList.php");
    header("Location: index.php?action=editFavoriteMoviesList");
    exit;
}
