<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;

use Semeformation\Mvc\Cinema_crud\Views\View;
use Semeformation\Mvc\Cinema_crud\Models\Cinema;
use Semeformation\Mvc\Cinema_crud\DAO\SeanceDAO;
use \Psr\Log\LoggerInterface;
/**
 * Description of CinemaController
 *
 * @author admin
 */
class CinemaController {
    private $cinemasDAO;
    private $seances;

    public function __construct(LoggerInterface $logger=null) {
        $this->cinemas = new Cinema($logger);
        $this->seances = new SeanceDAO();
    }

    function cinemasList() {

    $isUserAdmin = false;

    if (array_key_exists("user", $_SESSION) and $_SESSION['user'] == 'admin@adm.adm') {
        $isUserAdmin = true;
    }
    $cinemas = $this->cinemasDAO->getCinemasList();
    $vue = new View('CinemasList');
    $vue->generer((['isUserAdmin' => $isUserAdmin, 'cinemas' => $cinemas]));
//    require 'views/viewCinemasList.php';
}

    function editCinema() {
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
                $this->cinemasDAO->insertNewCinema($sanEntries['denomination'],
                        $sanEntries['adresse']);
            }
            // sinon, nous sommes dans le cas d'une modification
            else {
                // mise à jour du cinéma
                //$fctManager->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                //$fctCinema->updateCinema($sanEntries['cinemaID'], $sanEntries['denomination'], $sanEntries['adresse']);
                $this->cinemasDAO->updateCinema($sanEntries['cinemaID'],
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
            $cinema = $this->cinemasDAO->getCinemaInformationsByID($sanEntries['cinemaID']);
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
    $vue->generer((['sanEntries'    => $sanEntries,
        'cinema'        => $cinema,
        'isItACreation' => $isItACreation]));
//    require 'views/viewEditCinema.php';
}

    function deleteCinema() {
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

        $this->seances->deleteShowtimeByIdCinema($sanitizedEntries['cinemaID']);
        $this->cinemasDAO->deleteCinema($sanitizedEntries['cinemaID']);
    }
// redirection vers la liste des cinémas
//header("Location: cinemasList.php");
    header("Location: index.php?action=cinemasList");
    exit;
}
}
