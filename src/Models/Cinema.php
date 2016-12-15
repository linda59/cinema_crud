<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;



/**
 * Description of Cinema
 *
 * @author admin
 */
class Cinema{
    
    
private $cinemaId;
private $denomination;
private $adresse;
function getCinemaId() {
    return $this->cinemaId;
}

function getDenomination() {
    return $this->denomination;
}

function getAdresse() {
    return $this->adresse;
}

function setCinemaId($cinemaId) {
    $this->cinemaId = $cinemaId;
}

function setDenomination($denomination) {
    $this->denomination = $denomination;
}

function setAdresse($adresse) {
    $this->adresse = $adresse;
}


    
}
