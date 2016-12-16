<?php

namespace Semeformation\Mvc\Cinema_crud\DAO;

use \Exception;
Use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Cinema;

class CinemaDAO extends DAO {

    /**
     * 
     * @param type $denomination
     * @param type $adresse
     */
    public function insertNewCinema($denomination, $adresse) {
        // construction
        $requete = "INSERT INTO cinema (denomination, adresse) VALUES ("
                . ":denomination"
                . ", :adresse)";
        // exécution
        $this->executeQuery($requete, ['denomination' => $denomination,
            'adresse' => $adresse]);
        // log
        if ($this->logger) {
            $this->logger->info('Cinema ' . $denomination . ' successfully added.');
        }
    }

    /**
     * 
     * @param type $cinemaID
     * @param type $denomination
     * @param type $adresse
     */
    public function updateCinema($cinemaID, $denomination, $adresse) {
        // on construit la requête d'insertion
        $requete = "UPDATE cinema SET "
                . "denomination = "
                . "'" . $denomination . "'"
                . ", adresse = "
                . "'" . $adresse . "'"
                . " WHERE cinemaID = "
                . $cinemaID;
        // exécution de la requête
        $this->executeQuery($requete);
    }

    /**
     * 
     * @param type $cinemaID
     */
    public function deleteCinema($cinemaID) {
        $this->executeQuery("DELETE FROM cinema WHERE cinemaID = "
                . $cinemaID);

        if ($this->logger) {
            $this->logger->info('Cinema ' . $cinemaID . ' successfully deleted.');
        }
    }

    /**
     * 
     * @return type
     */
    public function getCinemasList() {
        $requete = "SELECT * FROM cinema";
        // on retourne le résultat
        //return $this->extraireNxN($requete);
        $resultats = $this->extraireNxN($requete);
        $tabCinemaObj = array();
        foreach ($resultats as $resultat) {
            $tabCinemaObj[] = $this->buildBusinessObject($resultat);
        }
        return $tabCinemaObj;
    }

    /**
     * 
     * @param type $cinemaID
     * @return type
     */
    public function getCinemaInformationsByID($cinemaID) {
        $requete = "SELECT * FROM cinema WHERE cinemaID = "
                . $cinemaID;
        $resultat = $this->extraire1xN($requete);
        // on retourne le résultat extrait
        // on construit l'objet Utilisateur
        $cinema = $this->buildBusinessObject($resultat);
        // on retourne l'utilisateur
        return $cinema;
    }

    /**
     * Méthode qui instancie un objet Utilisateur et qui le retourne.
     * @param array $row  un tableau résultat d’une requête SELECT
     */
    public function buildBusinessObject($row) {
        $cinema = new Cinema();
        $cinema->setCinemaid($row['CINEMAID']);
        $cinema->setDenomination($row['DENOMINATION']);
        $cinema->setAdresse($row['ADRESSE']);
        return $cinema;
    }

}
