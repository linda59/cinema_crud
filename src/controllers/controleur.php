<?php

function home($managers) {
// personne d'authentifi� � ce niveau
    $loginSuccess = false;

// variables de contr�le du formulaire
    $areCredentialsOK = true;

// si l'utilisateur est d�j� authentifi�
    if (array_key_exists("user", $_SESSION)) {
        $loginSuccess = true;
// Sinon (pas d'utilisateur authentifi� pour l'instant)
    } else {
        // si la m�thode POST a �t� employ�e
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
            // on "sainifie" les entr�es
            $sanitizedEntries = filter_input_array(INPUT_POST, ['email' => FILTER_SANITIZE_EMAIL,
                'password' => FILTER_DEFAULT]);
            try {


                $managers["utilisateursMgr"]->verifyUserCredentials($sanitizedEntries['email'], $sanitizedEntries['password']);

                // on enregistre l'utilisateur
                $_SESSION['user'] = $sanitizedEntries['email'];
                //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);          
                //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
                $_SESSION['userID'] = $managers["utilisateursMgr"]->getUserIDByEmailAddress($_SESSION['user']);
                // on redirige vers la page d'�dition des films pr�f�r�s
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
    require 'views/viewHome.php';
}

function cinemasList($managers) {

    $isUserAdmin = false;

    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $isUserAdmin = true;
    }
    require 'views/viewCinemasList.php';
}

function createNewUser($managers) {
    // variables de contr�les du formulaire de cr�ation
    $isFirstNameEmpty = false;
    $isLastNameEmpty = false;
    $isEmailAddressEmpty = false;
    $isUserUnique = true;
    $isPasswordEmpty = false;
    $isPasswordConfirmationEmpty = false;
    $isPasswordValid = true;

// si la m�thode POST est utilis�e, cela signifie que le formulaire a �t� envoy�
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_POST, ['firstName' => FILTER_SANITIZE_STRING,
            'lastName' => FILTER_SANITIZE_STRING,
            'email' => FILTER_SANITIZE_EMAIL,
            'password' => FILTER_DEFAULT,
            'passwordConfirmation' => FILTER_DEFAULT]);

        // si le pr�nom n'a pas �t� renseign�
        if ($sanitizedEntries['firstName'] === "") {
            $isFirstNameEmpty = true;
        }

        // si le nom n'a pas �t� renseign�
        if ($sanitizedEntries['lastName'] === "") {
            $isLastNameEmpty = true;
        }

        // si l'adresse email n'a pas �t� renseign�e
        if ($sanitizedEntries['email'] === "") {
            $isEmailAddressEmpty = true;
        } else {
            // On v�rifie l'existence de l'utilisateur
            //$userID = $fctManager->getUserIDByEmailAddress($sanitizedEntries['email']);
            //$userID = $utilisateursMgr->getUserIDByEmailAddress($sanitizedEntries['email']);
            $userID = $managers["utilisateursMgr"]->getUserIDByEmailAddress($sanitizedEntries['email']);
            // si on a un r�sultat, cela signifie que cette adresse email existe d�j�
            if ($userID) {
                $isUserUnique = false;
            }
        }
        // si le password n'a pas �t� renseign�
        if ($sanitizedEntries['password'] === "") {
            $isPasswordEmpty = true;
        }
        // si la confirmation du password n'a pas �t� renseign�
        if ($sanitizedEntries['passwordConfirmation'] === "") {
            $isPasswordConfirmationEmpty = true;
        }

        // si le mot de passe et sa confirmation sont diff�rents
        if ($sanitizedEntries['password'] !== $sanitizedEntries['passwordConfirmation']) {
            $isPasswordValid = false;
        }

        // si les champs n�cessaires ne sont pas vides, que l'utilisateur est unique et que le mot de passe est valide
        if (!$isFirstNameEmpty && !$isLastNameEmpty && !$isEmailAddressEmpty && $isUserUnique && !$isPasswordEmpty && $isPasswordValid) {
            // hash du mot de passe
            $password = password_hash($sanitizedEntries['password'], PASSWORD_DEFAULT);
            // cr�er l'utilisateur
            /* $fctManager->createUser($sanitizedEntries['firstName'],
              $sanitizedEntries['lastName'],
              $sanitizedEntries['email'],
              $password);
             * */
            //$utilisateursMgr->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);
            $managers["utilisateursMgr"]->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);
            //session_start();
            // authentifier l'utilisateur
            $_SESSION['user'] = $sanitizedEntries['email'];
            //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);
            //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
            $_SESSION['userID'] = $managers["utilisateursMgr"]->getUserIDByEmailAddress($_SESSION['user']);
            // on redirige vers la page d'�dition des films pr�f�r�s
            //header("Location: editFavoriteMoviesList.php");
            header("Location: index.php?action=editFavoriteMoviesList");
            exit;
        }
    }
// sinon (le formulaire n'a pas �t� envoy�)
    else {
        // initialisation des variables du formulaire
        $sanitizedEntries['firstName'] = '';
        $sanitizedEntries['lastName'] = '';
        $sanitizedEntries['email'] = '';
    }
    require 'views/viewCreateUser.php';
}

function editFavoriteMoviesList($managers) {
// session_start();
// si l'utilisateur n'est pas connect�
    if (!array_key_exists("user", $_SESSION)) {
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }
// l'utilisateur est loggu�
    else {
        //$utilisateur = $fctManager->getCompleteUsernameByEmailAddress($_SESSION['user']);
        //$utilisateur = $utilisateursMgr->getCompleteUsernameByEmailAddress($_SESSION['user']);
        $utilisateur = $managers["utilisateursMgr"]->getCompleteUsernameByEmailAddress($_SESSION['user']);
    }

    require 'views/viewFavoriteMoviesList.php';
}

function editFavoriteMovie($managers) {
// si l'utilisateur n'est pas connect�
    if (!array_key_exists("user", $_SESSION)) {
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// variable de contr�le de formulaire
    $aFilmIsSelected = true;
// variable qui sert � conditionner l'affichage du formulaire
    $isItACreation = false;

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_POST, ['backToList' => FILTER_DEFAULT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'userID' => FILTER_SANITIZE_NUMBER_INT,
            'comment' => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);

        // si l'action demand�e est retour en arri�re
        if ($sanitizedEntries['backToList'] !== NULL) {
            // on redirige vers la page d'�dition des films favoris
            //header('Location: editFavoriteMoviesList.php');
            header("Location: index.php?action=editFavoriteMoviesList");
            exit;
        }
        // sinon (l'action demand�e est la sauvegarde d'un favori)
        else {
            // si un film a �t� selectionn� 
            if ($sanitizedEntries['filmID'] !== NULL) {

                // et que nous ne sommes pas en train de modifier une pr�f�rence
                if ($sanitizedEntries['modificationInProgress'] == NULL) {
                    // on ajoute la pr�f�rence de l'utilisateur
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
                    // mise � jour de la pr�f�rence
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
                // on revient � la liste des pr�f�rences
                //header('Location: editFavoriteMoviesList.php');
                header('Location: index.php?action=editFavoriteMoviesList');
                exit;
            }
            // sinon (un film n'a pas �t� s�lectionn�)
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
// sinon (nous sommes en GET) et que l'id du film et l'id du user sont bien renseign�s
    } elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT,
            'userID' => FILTER_SANITIZE_NUMBER_INT]);

        if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !== '' && $sanitizedEntries['userID'] !== NULL && $sanitizedEntries['userID'] !== '') {
            // on r�cup�re les informations manquantes (le commentaire aff�rent)
            /* $preference = $fctManager->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             * */
            /*
              $preference = $fctPrefere->getFavoriteMovieInformations($sanitizedEntries['userID'],
              $sanitizedEntries['filmID']);
             */
            $preference = $managers["preferesMgr"]->getFavoriteMovieInformations($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
            // sinon, c'est une cr�ation
        } else {
            // C'est une cr�ation
            $isItACreation = true;
            // on initialise les autres variables de formulaire � vide
            $preference = [
                "userID" => $_SESSION['userID'],
                "filmID" => "",
                "titre" => "",
                "commentaire" => ""];
        }
    }
    require 'views/viewFavoriteMovie.php';
}

function cinemaShowtimes($managers) {
    $adminConnected = false;

    //  session_start();
// si l'utilisateur admin est connext�
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $adminConnected = true;
    }

// si la m�thode de formulaire est la m�thode GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on assainie les entr�es
        $sanitizedEntries = filter_input_array(INPUT_GET, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // si l'identifiant du cin�ma a bien �t� pass� en GET
        if ($sanitizedEntries && $sanitizedEntries['cinemaID'] !== NULL && $sanitizedEntries['cinemaID'] != '') {
            // on r�cup�re l'identifiant du cin�ma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // puis on r�cup�re les informations du cin�ma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
            // on r�cup�re les films pas encore projet�s
            //$filmsUnplanned = $fctManager->getNonPlannedMovies($cinemaID);
            //$filmsUnplanned = $fctSeance->getNonPlannedMovies($cinemaID);
            $filmsUnplanned = $managers["seancesMgr"]->getNonPlannedMovies($cinemaID);
        }
        // sinon, on retourne � l'accueil
        else {
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: index.php');
        exit();
    }

    require 'views/viewCinemaShowtimes.php';
}

function moviesList($managers) {
    $isUserAdmin = false;

//session_start();
// si l'utilisateur est pas connect� et qu'il est amdinistrateur
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $isUserAdmin = true;
    }
    require 'views/viewMoviesList.php';
}

function movieShowtimes($managers) {
    $adminConnected = false;

    //session_start();
// si l'utilisateur admin est connext�
    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $adminConnected = true;
    }

// si la m�thode de formulaire est la m�thode GET
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        // si l'identifiant du film a bien �t� pass� en GET'
        if ($sanitizedEntries && $sanitizedEntries['filmID'] !== NULL && $sanitizedEntries['filmID'] !== '') {
            // on r�cup�re l'identifiant du cin�ma
            $filmID = $sanitizedEntries['filmID'];
            // puis on r�cup�re les informations du film en question
            // $film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($filmID);
            // on r�cup�re les cin�mas qui ne projettent pas encore le film
            //$cinemasUnplanned = $fctManager->getNonPlannedCinemas($filmID);
            //$cinemasUnplanned = $fctSeance->getNonPlannedCinemas($filmID);
            $cinemasUnplanned = $managers["seancesMgr"]->getNonPlannedCinemas($filmID);
        }
        // sinon, on retourne � l'accueil
        else {
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: index.php');
        exit();
    }
    require 'views/viewMovieShowtimes.php';
}

function editCinema($managers) {
    //   session_start();
// si l'utilisateur n'est pas connect� ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// variable qui sert � conditionner l'affichage du formulaire
    $isItACreation = false;

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanEntries = filter_input_array(INPUT_POST, ['backToList' => FILTER_DEFAULT,
            'cinemaID' => FILTER_SANITIZE_NUMBER_INT,
            'adresse' => FILTER_SANITIZE_STRING,
            'denomination' => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);

        // si l'action demand�e est retour en arri�re
        if ($sanEntries['backToList'] !== NULL) {
            // on redirige vers la page des cin�mas
            // header('Location: cinemasList.php');
            header('Location: index.php?action=cinemasList');
            exit;
        }
        // sinon (l'action demand�e est la sauvegarde d'un cin�ma)
        else {

            // et que nous ne sommes pas en train de modifier un cin�ma
            if ($sanEntries['modificationInProgress'] == NULL) {
                // on ajoute le cin�ma
                //$fctManager->insertNewCinema($sanEntries['denomination'], $sanEntries['adresse']);
                //$fctCinema->insertNewCinema($sanEntries['denomination'], $sanEntries['adresse']);
                $managers["cinemasMgr"]->insertNewCinema($sanEntries['denomination'], $sanEntries['adresse']);
            }
            // sinon, nous sommes dans le cas d'une modification
            else {
                // mise � jour du cin�ma
                //$fctManager->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                //$fctCinema->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                $managers["cinemasMgr"]->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
            }
            // on revient � la liste des cin�mas
            //header('Location: cinemasList.php');
            header('Location: index.php?action=cinemasList');
            exit;
        }
    }// si la page est charg�e avec $_GET
    elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {
        // on "sainifie" les entr�es
        $sanEntries = filter_input_array(INPUT_GET, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);
        if ($sanEntries && $sanEntries['cinemaID'] !== NULL && $sanEntries['cinemaID'] !== '') {
            // on r�cup�re les informations manquantes 
            //$cinema = $fctManager->getCinemaInformationsByID($sanEntries['cinemaID']);
            //$cinema = $fctCinema->getCinemaInformationsByID($sanEntries['cinemaID']);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($sanEntries['cinemaID']);
        }
        // sinon, c'est une cr�ation
        else {
            $isItACreation = true;
            $cinema = [
                'CINEMAID' => '',
                'DENOMINATION' => '',
                'ADRESSE' => ''
            ];
        }
    }
    require 'views/viewEditCinema.php';
}

function editShowtime($managers) {
    // si l'utilisateur n'est pas connect� ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
// renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// init. des flags. Etat par d�faut => je viens du cin�ma et je cr��
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
        // pour l'instant, on v�rifie les donn�es en GET
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['from'])) {
            // on r�cup�re l'identifiant du cin�ma
            $cinemaID = $sanitizedEntries['cinemaID'];
            // l'identifiant du film
            $filmID = $sanitizedEntries['filmID'];
            // d'o� vient on ?
            $from = $sanitizedEntries['from'];
            // puis on r�cup�re les informations du cin�ma en question
            //$cinema = $fctManager->getCinemaInformationsByID($cinemaID);
            //$cinema = $fctCinema->getCinemaInformationsByID($cinemaID);
            $cinema = $managers["cinemasMgr"]->getCinemaInformationsByID($cinemaID);
            // puis on r�cup�re les informations du film en question
            //$film = $fctManager->getMovieInformationsByID($filmID);
            //$film = $fctFilm->getMovieInformationsByID($filmID);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($filmID);

            // s'il on vient des s�ances du film
            if (strstr($sanitizedEntries['from'], 'movie')) {
                $fromCinema = false;
                // on vient du film
                $fromFilm = true;
            }

            // ici, on veut savoir si on modifie ou si on ajoute
            if (isset($sanitizedEntries['heureDebut'], $sanitizedEntries['heureFin'], $sanitizedEntries['version'])) {
                // nous sommes dans le cas d'une modification
                $isItACreation = false;
                // on r�cup�re les anciennes valeurs (utile pour retrouver la s�ance avant de la modifier
                $seance['dateheureDebutOld'] = $sanitizedEntries['heureDebut'];
                $seance['dateheureFinOld'] = $sanitizedEntries['heureFin'];
                // dates PHP
                $dateheureDebut = new DateTime($sanitizedEntries['heureDebut']);
                $dateheureFin = new DateTime($sanitizedEntries['heureFin']);
                // d�coupage en heures
                $seance['heureDebut'] = $dateheureDebut->format("H:i");
                $seance['heureFin'] = $dateheureFin->format("H:i");
                // d�coupage en jour/mois/ann�e
                $seance['dateDebut'] = $dateheureDebut->format("d/m/Y");
                $seance['dateFin'] = $dateheureFin->format("d/m/Y");
                // on r�cup�re la version
                $seance['version'] = $sanitizedEntries['version'];
            }
        }
        // sinon, on retourne � l'accueil
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
        // si toutes les valeurs sont renseign�es
        if ($sanitizedEntries && isset($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['datedebut'], $sanitizedEntries['heuredebut'], $sanitizedEntries['datefin'], $sanitizedEntries['heurefin'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $sanitizedEntries['version'], $sanitizedEntries['from'])) {
            // nous sommes en Fran�ais
            setlocale(LC_TIME, 'fra_fra');
            // date du jour de projection de la s�ance
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
                // j'ins�re dans la base
                /*
                  $resultat = $fctManager->insertNewShowtime($sanitizedEntries['cinemaID'],
                  $sanitizedEntries['filmID'],
                  $datetimeDebut->format("Y-m-d H:i"),
                  $datetimeFin->format("Y-m-d H:i"),
                  $sanitizedEntries['version']);
                 * */
                //$resultat = $fctSeance->insertNewShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
                $resultat = $managers["seancesMgr"]->insertNewShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
            } else {
                // c'est une mise � jour
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
                $resultat = $managers["seancesMgr"]->updateShowtime($sanitizedEntries['cinemaID'], $sanitizedEntries['filmID'], $sanitizedEntries['dateheuredebutOld'], $sanitizedEntries['dateheurefinOld'], $datetimeDebut->format("Y-m-d H:i"), $datetimeFin->format("Y-m-d H:i"), $sanitizedEntries['version']);
            }
            // en fonction d'o� je viens, je redirige
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
// sinon, on retourne � l'accueil
    else {
        header('Location: index.php');
        exit();
    }
    require 'views/viewEditShowtimes.php';
}

function editMovie($managers) {
    // si l'utilisateur n'est pas connect� ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
// renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// variable qui sert � conditionner l'affichage du formulaire
    $isItACreation = false;

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanEntries = filter_input_array(INPUT_POST, ['backToList' => FILTER_DEFAULT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'titre' => FILTER_SANITIZE_STRING,
            'titreOriginal' => FILTER_SANITIZE_STRING,
            'modificationInProgress' => FILTER_SANITIZE_STRING]);

        // si l'action demand�e est retour en arri�re
        if ($sanEntries['backToList'] !== NULL) {
            // on redirige vers la page des films
            //header('Location: moviesList.php');
            header('Location: index.php?action=moviesList');
            exit;
        }
        // sinon (l'action demand�e est la sauvegarde d'un film)
        else {

            // et que nous ne sommes pas en train de modifier un film
            if ($sanEntries['modificationInProgress'] == NULL) {
                // on ajoute le film
                //$fctManager->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal']);
                //$fctFilm->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal']);
                $managers["filmsMgr"]->insertNewMovie($sanEntries['titre'], $sanEntries['titreOriginal']);
            }
            // sinon, nous sommes dans le cas d'une modification
            else {
                // mise � jour du film
                //$fctManager->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                //$fctFilm->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
                $managers["filmsMgr"]->updateMovie($sanEntries['filmID'], $sanEntries['titre'], $sanEntries['titreOriginal']);
            }
            // on revient � la liste des films
            //header('Location: moviesList.php');
            header('Location: index.php?action=moviesList');
            exit;
        }
    }// si la page est charg�e avec $_GET
    elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "GET") {
        // on "sainifie" les entr�es
        $sanEntries = filter_input_array(INPUT_GET, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);
        if ($sanEntries && $sanEntries['filmID'] !== NULL && $sanEntries['filmID'] !== '') {
            // on r�cup�re les informations manquantes 
            //$film = $fctManager->getMovieInformationsByID($sanEntries['filmID']);
            //$film = $fctFilm->getMovieInformationsByID($sanEntries['filmID']);
            $film = $managers["filmsMgr"]->getMovieInformationsByID($sanEntries['filmID']);
        }
        // sinon, c'est une cr�ation
        else {
            $isItACreation = true;
            $film = [
                'FILMID' => '',
                'TITRE' => '',
                'TITREORIGINAL' => ''
            ];
        }
    }
    require 'views/viewEditMovie.php';
}

function deleteMovie($managers) {
    // si l'utilisateur n'est pas connect� ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_POST, ['filmID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la pr�f�rence de film
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
    // si l'utilisateur n'est pas connect�
    if (!array_key_exists("user", $_SESSION)) {
// renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on assainie les variables
        $sanitizedEntries = filter_input_array(INPUT_POST, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT,
            'heureDebut' => FILTER_SANITIZE_STRING,
            'heureFin' => FILTER_SANITIZE_STRING,
            'version' => FILTER_SANITIZE_STRING,
            'from' => FILTER_SANITIZE_STRING
        ]);

        // suppression de la s�ance
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
        // en fonction d'o� je viens, je redirige
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
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }
}

function deleteCinema($managers) {
    // si l'utilisateur n'est pas connect� ou sinon s'il n'est pas amdinistrateur
    if (!array_key_exists("user", $_SESSION) or $_SESSION['user'] !== 'admin@adm.adm') {
        // renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_POST, ['cinemaID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la pr�f�rence de film
        //$fctManager->deleteCinema($sanitizedEntries['cinemaID']);
        //$fctCinema->deleteCinema($sanitizedEntries['cinemaID']);
        $managers["seancesMgr"]->deleteShowtimeByIdCinema($sanitizedEntries['cinemaID']);
        $managers["cinemasMgr"]->deleteCinema($sanitizedEntries['cinemaID']);
    }
// redirection vers la liste des cin�mas
//header("Location: cinemasList.php");
    header("Location: index.php?action=cinemasList");
    exit;
}

function deleteFavoriteMovie($managers) {
    // si l'utilisateur n'est pas connect�
    if (!array_key_exists("user", $_SESSION)) {
// renvoi � la page d'accueil
        header('Location: index.php');
        exit;
    }

// si la m�thode de formulaire est la m�thode POST
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {

        // on "sainifie" les entr�es
        $sanitizedEntries = filter_input_array(INPUT_POST, ['userID' => FILTER_SANITIZE_NUMBER_INT,
            'filmID' => FILTER_SANITIZE_NUMBER_INT]);

        // suppression de la pr�f�rence de film
        // $fctManager->deleteFavoriteMovie($sanitizedEntries['userID'],
        //         $sanitizedEntries['filmID']);
        /*
          $fctPrefere->deleteFavoriteMovie($sanitizedEntries['userID'],
          $sanitizedEntries['filmID']);
         */
        $managers["preferesMgr"]->deleteFavoriteMovie($sanitizedEntries['userID'], $sanitizedEntries['filmID']);
    }
// redirection vers la liste des pr�f�rences de films
//header("Location: editFavoriteMoviesList.php");
    header("Location: index.php?action=editFavoriteMoviesList");
    exit;
}
