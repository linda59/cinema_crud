<?php
 
/* 
 * To change this license header, choose License Headers in Project Properties. 
 * To change this template file, choose Tools | Templates 
 * and open the template in the editor. 
 */ 
 
namespace Semeformation\Mvc\Cinema_crud\dao; 
use \Semeformation\Mvc\Cinema_crud\Includes\DAO; 
use Semeformation\Mvc\Cinema_crud\Models\Cinema; 
 
/** 
 * Description of CinemaDAO 
 * 
 * @author admin 
 */ 
class CinemaDAO extends DAO{ 
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
        $this->executeQuery($requete, 
                ['denomination' => $denomination, 
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
        return $this->extraireNxN($requete); 
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
        //return $resultat; 
         
        // on construit l'objet Cinema 
        $cinema = $this->buildBusinessObject($resultat); 
// on retourne le cinema 
        return $cinema; 
    } 
     
    /** 
     * Méthode qui instancie un objet Cinema et qui le retourne. 
     * @param array $row  un tableau résultat d’une requête SELECT      
     */ 
    public function buildBusinessObject($row){ 
        $cinema = new Cinema(); 
        $cinema->setCinemaId($row['CINEMAID']); 
        $cinema->setDenomination($row['DENOMINATION']); 
        $cinema->setAdresse($row['ADRESSE']);       
        return $cinema;                                      
    }

   

} 