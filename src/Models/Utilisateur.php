<?php
namespace Semeformation\Mvc\Cinema_crud\Models;

use Semeformation\Mvc\Cinema_crud\Includes\DAO;

use Exception;

/**
 * Description of Utilisateurs
 *
 * @author admin
 */
class Utilisateur{
    private $userId;
    private $nom;
    private $prenom;
    private $adresseCourriel;
    private $password;

    function getUserId() {
        return $this->userId;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getAdresseCourriel() {
        return $this->adresseCourriel;
    }

    function getPassword() {
        return $this->password;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setAdresseCourriel($adresseCourriel) {
        $this->adresseCourriel = $adresseCourriel;
    }

    function setPassword($password) {
        $this->password = $password;
    }
    
}
