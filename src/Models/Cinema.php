<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Semeformation\Mvc\Cinema_crud\Models;

use Semeformation\Mvc\Cinema_crud\Includes\DAO;

use Exception;

/**
 * Description of Cinema
 *
 * @author admin
 */
class Cinema {
    private $cinemaid;
    private $denomination;
    private $adresse;
    
    function getCinemaid() {
        return $this->cinemaid;
    }

    function getDenomination() {
        return $this->denomination;
    }

    function getAdresse() {
        return $this->adresse;
    }

    function setCinemaid($cinemaid) {
        $this->cinemaid = $cinemaid;
    }

    function setDenomination($denomination) {
        $this->denomination = $denomination;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }


    
}
