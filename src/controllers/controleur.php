<?php

use Semeformation\Mvc\Cinema_crud\views\View;

function home($managers) {
// personne d'authentifié à ce niveau
    $loginSuccess = false;

// variables de contrôle du formulaire
    $areCredentialsOK = true;

// si l'utilisateur est déjà authentifié
    if (array_key_exists("user", $_SESSION)) {
        $loginSuccess = true;
// Sinon (pas d'utilisateur authentifié pour l'instant)
    } else {
        // si la méthode POST a été employée
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_POST,
                    ['email'    => FILTER_SANITIZE_EMAIL,
                'password' => FILTER_DEFAULT]);
            try {


                $managers["utilisateursMgr"]->verifyUserCredentials($sanitizedEntries['email'],
                        $sanitizedEntries['password']);

                // on enregistre l'utilisateur
                $_SESSION['user']   = $sanitizedEntries['email'];
                //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);          
                //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
                $_SESSION['userID'] = $managers["utilisateursMgr"]->getUserIDByEmailAddress($_SESSION['user']);
                // on redirige vers la page d'édition des films préférés
                //header("Location: editFavoriteMoviesList.php");
                //header("Location: index.php?action=editFavoriteMoviesList.php");
                header("Location: index.php?action=editFavoriteMoviesList");
                exit;
            } catch (Exception $ex) {
                $areCredentialsOK = false;
                $logger->error($ex->getMessage());
            }
        }
    }
    $vue = new View('Home');
    $vue->generer((['areCredentialsOK' => $areCredentialsOK, 'loginSuccess' => $loginSuccess]));
//    require 'views/viewHome.php';
}

function cinemasList($managers) {

    $isUserAdmin = false;

    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $isUserAdmin = true;
    }
    $vue = new View('CinemasList');
    $vue->generer((['isUserAdmin' => $isUserAdmin, 'managers' => $managers]));
//    require 'views/viewCinemasList.php';
}

function createNewUser($managers) {
    // variables de contrôles du formulaire de création
    $isFirstNameEmpty            = false;
    $isLastNameEmpty             = false;
    $isEmailAddressEmpty         = false;
    $isUserUnique                = true;
    $isPasswordEmpty             = false;
    $isPasswordConfirmationEmpty = false;
    $isPasswordValid             = true;

// si la méthode POST est utilisée, cela signifie que le formulaire a été envoyé
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['firstName'            => FILTER_SANITIZE_STRING,
            'lastName'             => FILTER_SANITIZE_STRING,
            'email'                => FILTER_SANITIZE_EMAIL,
            'password'             => FILTER_DEFAULT,
            'passwordConfirmation' => FILTER_DEFAULT]);

        // si le prénom n'a pas été renseigné
        if ($sanitizedEntries['firstName'] === "") {
            $isFirstNameEmpty = true;
        }

        // si le nom n'a pas été renseigné
        if ($sanitizedEntries['lastName'] === "") {
            $isLastNameEmpty = true;
        }

        // si l'adresse email n'a pas été renseignée
        if ($sanitizedEntries['email'] === "") {
            $isEmailAddressEmpty = true;
        } else {
            // On vérifie l'existence de l'utilisateur
            //$userID = $fctManager->getUserIDByEmailAddress($sanitizedEntries['email']);
            //$userID = $utilisateursMgr->getUserIDByEmailAddress($sanitizedEntries['email']);
            $userID = $managers["utilisateursMgr"]->getUserIDByEmailAddress($sanitizedEntries['email']);
            // si on a un résultat, cela signifie que cette adresse email existe déjà
            if ($userID) {
                $isUserUnique = false;
            }
        }
        // si le password n'a pas été renseigné
        if ($sanitizedEntries['password'] === "") {
            $isPasswordEmpty = true;
        }
        // si la confirmation du password n'a pas été renseigné
        if ($sanitizedEntries['passwordConfirmation'] === "") {
            $isPasswordConfirmationEmpty = true;
        }

        // si le mot de passe et sa confirmation sont différents
        if ($sanitizedEntries['password'] !== $sanitizedEntries['passwordConfirmation']) {
            $isPasswordValid = false;
        }

        // si les champs nécessaires ne sont pas vides, que l'utilisateur est unique et que le mot de passe est valide
        if (!$isFirstNameEmpty && !$isLastNameEmpty && !$isEmailAddressEmpty && $isUserUnique &&
                !$isPasswordEmpty && $isPasswordValid) {
            // hash du mot de passe
            $password           = password_hash($sanitizedEntries['password'],
                    PASSWORD_DEFAULT);
            // créer l'utilisateur
            /* $fctManager->createUser($sanitizedEntries['firstName'],
              $sanitizedEntries['lastName'],
              $sanitizedEntries['email'],
              $password);
             * */
            //$utilisateursMgr->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);
            $managers["utilisateursMgr"]->createUser($sanitizedEntries['firstName'],
                    $sanitizedEntries['lastName'], $sanitizedEntries['email'],
                    $password);
            //session_start();
            // authentifier l'utilisateur
            $_SESSION['user']   = $sanitizedEntries['email'];
            //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);
            //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
            $_SESSION['userID'] = $managers["utilisateursMgr"]->getUserIDByEmailAddress($_SESSION['user']);
            // on redirige vers la page d'édition des films préférés
            //header("Location: editFavoriteMoviesList.php");
            header("Location: index.php?action=editFavoriteMoviesList");
            exit;
        }
    }
// sinon (le formulaire n'a pas été envoyé)
    else {
        // initialisation des variables du formulaire
        $sanitizedEntries['firstName'] = '';
        $sanitizedEntries['lastName']  = '';
        $sanitizedEntries['email']     = '';
    }
    $vue = new View('CreateUser');
    $vue->generer((['sanitizedEntries'            => $sanitizedEntries, 'managers'                    => $managers,
        'isFirstNameEmpty'            => $isFirstNameEmpty,
        'isLastNameEmpty'             => $isLastNameEmpty,
        'isEmailAddressEmpty'         => $isEmailAddressEmpty,
        'isUserUnique'                => $isUserUnique,
        'isPasswordEmpty'             => $isPasswordEmpty,
        'isPasswordConfirmationEmpty' => $isPasswordConfirmationEmpty,
        'isPasswordValid'             => $isPasswordValid]));
//    require 'views/viewCreateUser.php';
}

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
    $vue = new View('FavoriteMoviesList');
    $vue->generer((['utilisateur' => $utilisateur, 'managers' => $managers]));
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
    $isItACreation   = false;

// si la méthode de formulaire est la méthode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['backToList'             => FILTER_DEFAULT,
            'filmID'                 => FILTER_SANITIZE_NUMBER_INT,
            'userID'                 => FILTER_SANITIZE_NUMBER_INT,
            'comment'                => FILTER_SANITIZE_STRING,
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
                    $managers["preferesMgr"]->insertNewFavoriteMovie($sanitizedEntries['userID'],
                            $sanitizedEntries['filmID'],
                            $sanitizedEntries['comment']);
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
                    $managers["preferesMgr"]->updateFavoriteMovie($sanitizedEntries['userID'],
                            $sanitizedEntries['filmID'],
                            $sanitizedEntries['comment']);
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
                $isItACreation   = true;
                // initialisation des champs du formulaire
                $preference      = [
                    "userID"      => $sanitizedEntries["userID"],
                    "filmID"      => "",
                    "titre"       => "",
                    "commentaire" => $sanitizedEntries["comment"]];
                $userID          = $sanitizedEntries['userID'];
            }
        }
// sinon (nous sommes en GET) et que l'id du film et l'id du user sont bien renseignés
    } elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_GET,
                ['filmID' => FILTER_SANITIZE_NUMBER_INT,
            'userID' => FILTER_SANITIZE_NUMBER_INT]);

        if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !==
                '' && $sanitizedEntries['userID'] !== NULL && $sanitizedEntries['userID'] !==
                '') {
            // on récupère les informations manquantes (le commentaire afférent)
            /* $preference = $fctManager->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             * */
            /*
              $preference = $fctPrefere->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             */
            $preference = $managers["preferesMgr"]->getFavoriteMovieInformations($sanitizedEntries['userID'],
                    $sanitizedEntries['filmID']);
            // sinon, c'est une création
        } else {
            // C'est une création
            $isItACreation = true;
            // on initialise les autres variables de formulaire à vide
            $preference    = [
                "userID"      => $_SESSION['userID'],
                "filmID"      => "",
                "titre"       => "",
                "commentaire" => ""];
        }
    }
    $vue = new View('FavoriteMoviesList');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'managers'         => $managers,
        'preference'       => $preference,
        'aFilmIsSelected'  => $aFilmIsSelected,
        'isItACreation'    => $isItACreation]));
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
        $sanitizedEntries = filter_input_array(INPUT_GET,
                ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // si l'identifiant du cinéma a bien été passé en GET
        if ($sanitizedEntries && $sanitizedEntries['cinemaID'] !== NULL && $sanitizedEntries['cinemaID'] !=
                '') {
            // on récupère l'identifiant du cinéma
            $cinemaID       = $sanitizedEntries['cinemaID'];
            // puis on récupère les informations du cinéma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema         = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
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
    $vue = new View('CinemaShowtimes');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'managers'         => $managers,
        'cinemaID'         => $cinemaID,
        'cinema'           => $cinema,
        'adminConnected'   => $adminConnected,
        'filmsUnplanned'   => $filmsUnplanned]));
//    require 'views/viewCinemaShowtimes.php';
}

function moviesList($managers) {
    $isUserAdmin = false;

//session_start();
// si l'utilisateur est pas connecté et qu'il est amdinistrateur
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $isUserAdmin = true;
    }
    $vue = new View('MoviesList');
    $vue->generer((['isUserAdmin' => $isUserAdmin, 'managers' => $managers]));
//    require 'views/viewMoviesList.php';
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
        $sanitizedEntries = filter_input_array(INPUT_GET,
                ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        // si l'identifiant du film a bien été passé en GET'
        if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !==
                '') {
            // on récupère l'identifiant du cinéma
            $filmID           = $sanitizedEntries['filmID'];
            // puis on récupère les informations du film en question
            // $film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film             = $managers["filmsMgr"]->getMovieInformationsByID($filmID);
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
    $vue = new View('MovieShowtimes');
    $vue->generer((['sanitizedEntries' => $sanitizedEntries, 'managers'         => $managers,
        'filmID'           => $filmID,
        'adminConnected'   => $adminConnected,
        'film'             => $film,
        'cinemasUnplanned' => $cinemasUnplanned]));
//    require 'views/viewMovieShowtimes.php';
}

function editCinema($managers) {
    //   session_start();
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
        $sanEntries = filter_input_array(INPUT_POST,
                ['backToList'             => FILTER_DEFAULT,
            'cinemaID'               => FILTER_SANITIZE_NUMBER_INT,
            'adresse'                => FILTER_SANITIZE_STRING,
            'denomination'           => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);

        // si l'action demandée est retour en arrière
        if ($sanEntries['backToList'] !== NULL) {
            // on redirige vers la page des cinémas
            // header('Location: cinemasList.php');
            header('Location: index.php?action=cinemasList');
            exit;
        }
        // sinon (l'action demandée est la sauvegarde d'un cinéma)
        else {

            // et que nous ne sommes pas en train de modifier un cinéma
            if ($sanEntries['modificationInProgress'] == NULL) {
                // on ajoute le cinéma
                //$fctManager->insertNewCinema($sanEntries['denomination'], $sanEntries['adresse']);
                //$fctCinema->insertNewCinema($sanEntries['denomination'], $sanEntries['adresse']);
                $managers["cinemasMgr"]->insertNewCinema($sanEntries['denomination'],
                        $sanEntries['adresse']);
            }
            // sinon, nous sommes dans le cas d'une modification
            else {
                // mise à jour du cinéma
                //$fctManager->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                //$fctCinema->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                $managers["cinemasMgr"]->updateCinema($sanEntries['cinemaID'],
                        $sanEntries['denomination'], $sanEntries['adresse']);
            }
            // on revient à la liste des cinémas
            //header('Location: cinemasList.php');.
            header('Location: index.php?action=cinemasList');
            exit;
        }
    }// si la page est chargée avec $_GET
    elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {
        // on "sainifie" les entrées
        $sanEntries = filter_input_array(INPUT_GET,
                ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);
        if ($sanEntries && $sanEntries['cinemaID'] !== NULL && $sanEntries['cinemaID'] !==
                '') {
            // on récupère les informations manquantes 
            //$cinema = $fctManager->getCinemaInformationsByID($sanEntries['cinemaID']);
            //$cinema = $fctCinema->getCinemaInformationsByID($sanEntries['cinemaID']);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($sanEntries['cinemaID']);
        }
        // sinon, c'est une création
        else {
            $isItACreation = true;
            $cinema        = [
                'CINEMAID'     => '',
                'DENOMINATION' => '',
                'ADRESSE'      => ''
            ];
        }
    }
    $vue = new View('EditCinema');
    $vue->generer((['sanEntries'    => $sanEntries, 'managers'      => $managers,
        'cinema'        => $cinema,
        'isItACreation' => $isItACreation]));
//    require 'views/viewEditCinema.php';
}

function editShowtime($managers) {
    // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
// renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }

// init. des flags. Etat par défaut => je viens du cinéma et je créé
    $fromCinema    = true;
    $fromFilm      = false;
    $isItACreation = true;

// init. des variables du formulaire
    $seance = ['dateDebut'         => '',
        'heureDebut'        => '',
        'dateFin'           => '',
        'heureFin'          => '',
        'dateheureDebutOld' => '',
        'dateheureFinOld'   => '',
        'heureFinOld'       => '',
        'version'           => ''];

// si l'on est en GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'GET') {
        // on assainie les variables
        $sanitizedEntries = filter_input_array(INPUT_GET,
                ['cinemaID'   => FILTER_SANITIZE_NUMBER_INT,
            'filmID'     => FILTER_SANITIZE_NUMBER_INT,
            'from'       => FILTER_SANITIZE_STRING,
            'heureDebut' => FILTER_SANITIZE_STRING,
            'heureFin'   => FILTER_SANITIZE_STRING,
            'version'    => FILTER_SANITIZE_STRING]);
        // pour l'instant, on vérifie les données en GET
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'],
                        $sanitizedEntries['filmID'], $sanitizedEntries['from'])) {
            // on récupère l'identifiant du cinéma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // l'identifiant du film
            $filmID   = $sanitizedEntries['filmID'];
            // d'où vient on ?
            $from     = $sanitizedEntries['from'];
            // puis on récupère les informations du cinéma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema   = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
            // puis on récupère les informations du film en question
            //$film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film     = $managers["filmsMgr"]->getMovieInformationsByID($filmID);

            // s'il on vient des séances du film
            if (strstr($sanitizedEntries['from'], 'movie')) {
                $fromCinema = false;
                // on vient du film
                $fromFilm   = true;
            }

            // ici, on veut savoir si on modifie ou si on ajoute
            if (isset($sanitizedEntries['heureDebut'],
                            $sanitizedEntries['heureFin'],
                            $sanitizedEntries['version'])) {
                // nous sommes dans le cas d'une modification
                $isItACreation               = false;
                // on récupère les anciennes valeurs (utile pour retrouver la séance avant de la modifier
                $seance['dateheureDebutOld'] = $sanitizedEntries['heureDebut'];
                $seance['dateheureFinOld']   = $sanitizedEntries['heureFin'];
                // dates PHP
                $dateheureDebut              = new DateTime($sanitizedEntries['heureDebut']);
                $dateheureFin                = new DateTime($sanitizedEntries['heureFin']);
                // découpage en heures
                $seance['heureDebut']        = $dateheureDebut->format("H:i");
                $seance['heureFin']          = $dateheureFin->format("H:i");
                // découpage en jour/mois/année
                $seance['dateDebut']         = $dateheureDebut->format("d/m/Y");
                $seance['dateFin']           = $dateheureFin->format("d/m/Y");
                // on récupère la version
                $seance['version']           = $sanitizedEntries['version'];
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
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['cinemaID'               => FILTER_SANITIZE_NUMBER_INT,
            'filmID'                 => FILTER_SANITIZE_NUMBER_INT,
            'datedebut'              => FILTER_SANITIZE_STRING,
            'heuredebut'             => FILTER_SANITIZE_STRING,
            'datefin'                => FILTER_SANITIZE_STRING,
            'heurefin'               => FILTER_SANITIZE_STRING,
            'dateheurefinOld'        => FILTER_SANITIZE_STRING,
            'dateheuredebutOld'      => FILTER_SANITIZE_STRING,
            'version'                => FILTER_SANITIZE_STRING,
            'from'                   => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);
        // si toutes les valeurs sont renseignées
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'],
                        $sanitizedEntries['filmID'],
                        $sanitizedEntries['datedebut'],
                        $sanitizedEntries['heuredebut'],
                        $sanitizedEntries['datefin'],
                        $sanitizedEntries['heurefin'],
                        $sanitizedEntries['dateheuredebutOld'],
                        $sanitizedEntries['dateheurefinOld'],
                        $sanitizedEntries['version'], $sanitizedEntries['from'])) {
            // nous sommes en Français
            setlocale(LC_TIME, 'fra_fra');
            // date du jour de projection de la séance
            // Correction bug #3
            // AVANT
            //$datetimeDebut = new DateTime($sanitizedEntries['datedebut'] . ' ' . $sanitizedEntries['heuredebut']);
            //$datetimeFin = new DateTime($sanitizedEntries['datefin'] . ' ' . $sanitizedEntries['heurefin']);
            // APRES
            $datetimeDebut = DateTime::createFromFormat('d/m/Y H:i',
                            $sanitizedEntries['datedebut'] . ' ' . $sanitizedEntries['heuredebut']);
            $datetimeFin   = DateTime::createFromFormat('d/m/Y H:i',
                            $sanitizedEntries['datefin'] . ' ' . $sanitizedEntries['heurefin']);
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
                    $resultat = $managers["seancesMgr"]->insertNewShowtime($sanitizedEntries['cinemaID'],
                            $sanitizedEntries['filmID'],
                            $datetimeDebut->format("Y-m-d H:i"),
                            $datetimeFin->format("Y-m-d H:i"),
                            $sanitizedEntries['version']);
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
                    $resultat = $managers["seancesMgr"]->updateShowtime($sanitizedEntries['cinemaID'],
                            $sanitizedEntries['filmID'],
                            $sanitizedEntries['dateheuredebutOld'],
                            $sanitizedEntries['dateheurefinOld'],
                            $datetimeDebut->format("Y-m-d H:i"),
                            $datetimeFin->format("Y-m-d H:i"),
                            $sanitizedEntries['version']);
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
    $vue = new Semeformation\Mvc\Cinema_crud\Views\View('EditShowtimes');
    $vue->generer((['seance'        => $seance,
        'film'          => $film,
        'cinema'        => $cinema,
        'from'          => $from,
        'cinemaID'      => $cinemaID,
        'filmID'        => $filmID,
        'isItACreation' => $isItACreation,
        'fromCinema'    => $fromCinema]));
//    require 'views/viewEditShowtimes.php';
}

function editMovie($managers) {
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
        $sanEntries = filter_input_array(INPUT_POST,
                ['backToList'             => FILTER_DEFAULT,
            'filmID'                 => FILTER_SANITIZE_NUMBER_INT,
            'titre'                  => FILTER_SANITIZE_STRING,
            'titreOriginal'          => FILTER_SANITIZE_STRING,
            'dateSortie'             => FILTER_SANITIZE_STRING,
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
                $verifieFilm = $managers["filmsMgr"]->verifierFilm($sanEntries['titre'],
                        $sanEntries['titreOriginal'], $sanEntries['dateSortie']);
                if (empty($verifieFilm))
                    $managers["filmsMgr"]->insertNewMovie($sanEntries['titre'],
                            $sanEntries['titreOriginal'],
                            $sanEntries['dateSortie']);
            }
            // sinon, nous sommes dans le cas d'une modification
            else {
                // mise à jour du film
                //$fctManager->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                //$fctFilm->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                $managers["filmsMgr"]->updateMovie($sanEntries['filmID'],
                        $sanEntries['titre'], $sanEntries['titreOriginal'],
                        $sanEntries['dateSortie']);
            }
            // on revient à la liste des films
            //header('Location: moviesList.php');
            header('Location: index.php?action=moviesList');
            exit;
        }
    }// si la page est chargée avec $_GET
    elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {
        // on "sainifie" les entrées
        $sanEntries = filter_input_array(INPUT_GET,
                ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        if ($sanEntries && $sanEntries['filmID'] !== NULL && $sanEntries['filmID'] !==
                '') {
            // on récupère les informations manquantes 
            //$film = $fctManager->getMovieInformationsByID($sanEntries['filmID']);
            //$film = $fctFilm->getMovieInformationsByID($sanEntries['filmID']);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($sanEntries['filmID']);
        }
        // sinon, c'est une création
        else {
            $isItACreation = true;
            $film          = [
                'FILMID'        => '',
                'TITRE'         => '',
                'TITREORIGINAL' => '',
                'DATESORTIE'    => ''
            ];
        }
    }
    require 'views/viewEditMovie.php';
}

function deleteMovie($managers) {
    // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
        // renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la méthode de formulaire est la méthode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['filmID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la préférence de film
        //$fctManager->deleteMovie($sanitizedEntries['filmID']);
        //$fctFilm->deleteMovie($sanitizedEntries['filmID']);
        $managers["filmsMgr"]->deleteMovie($sanitizedEntries['filmID']);
    }
// redirection vers la liste des films
//header("Location: moviesList.php");
    header("Location: index.php?action=moviesList");
    exit;
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
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['cinemaID'   => FILTER_SANITIZE_NUMBER_INT,
            'filmID'     => FILTER_SANITIZE_NUMBER_INT,
            'heureDebut' => FILTER_SANITIZE_STRING,
            'heureFin'   => FILTER_SANITIZE_STRING,
            'version'    => FILTER_SANITIZE_STRING,
            'from'       => FILTER_SANITIZE_STRING
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
        $managers["seancesMgr"]->deleteShowtime($sanitizedEntries['cinemaID'],
                $sanitizedEntries['filmID'], $sanitizedEntries['heureDebut'],
                $sanitizedEntries['heureFin']
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

function deleteCinema($managers) {
    // si l'utilisateur n'est pas connecté ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
        // renvoi à la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la méthode de formulaire est la méthode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entrées
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la préférence de film
        //$fctManager->deleteCinema($sanitizedEntries['cinemaID']);
        //$fctCinema->deleteCinema($sanitizedEntries['cinemaID']);
        $managers["seancesMgr"]->deleteShowtimeByIdCinema($sanitizedEntries['cinemaID']);
        $managers["cinemasMgr"]->deleteCinema($sanitizedEntries['cinemaID']);
    }
// redirection vers la liste des cinémas
//header("Location: cinemasList.php");
    header("Location: index.php?action=cinemasList");
    exit;
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
        $sanitizedEntries = filter_input_array(INPUT_POST,
                ['userID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la préférence de film
        // $fctManager->deleteFavoriteMovie($sanitizedEntries['userID'],
        //         $sanitizedEntries['filmID']);
        /*
          $fctPrefere->deleteFavoriteMovie($sanitizedEntries['userID'],
          $sanitizedEntries['filmID']);
         */
        $managers["preferesMgr"]->deleteFavoriteMovie($sanitizedEntries['userID'],
                $sanitizedEntries['filmID']);
    }
// redirection vers la liste des préférences de films
//header("Location: editFavoriteMoviesList.php");
    header("Location: index.php?action=editFavoriteMoviesList");
    exit;
}
