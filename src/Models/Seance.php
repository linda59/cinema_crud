<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;
use \Semeformation\Mvc\Cinema_crud\Includes\DAO;
/**
 * Description of Seance
 *
 * @author admin
 */
class Seance {
    
    private $cinemaID;
    private $filmID;
    private $heureDebut;
    private $heureFin;
    private $version;
    
    public function getCinemaID() {
        return $this->cinemaID;
    }
    
    public function getFilmID() {
        return $this->filmID;
    }
    
    public function getHeureDebut() {
        return $this->heureDebut;
    }
    
    public function getHeureFin() {
        return $this->heureFin;
    }
    
    public function getVersion() {
        return $this->version;
    }
    
    public function setCinemaID($cinemaID) {
        $this->cinemaID = $cinemaID;
    }
    
    public function setFilmID($filmID) {
        $this->filmID = $filmID;
    }
    
    public function setHeureDebut($heureDebut) {
        $this->heureDebut = $heureDebut;
    }
    
    public function setHeureFin($heureFin) {
        $this->heureFin = $heureFin;
    }
    
    public function setVersion($version) {
        $this->version = $version;
    }
}
