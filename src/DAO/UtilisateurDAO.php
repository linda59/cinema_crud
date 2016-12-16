<?php
namespace Semeformation\Mvc\Cinema_crud\DAO;
use \Exception;
/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
Use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;
/**
* Description of UtilisateurDAO
*
* @author admin
*/
class UtilisateurDAO extends DAO{
    /*
    * Méthode qui teste si l'utilisateur est bien présent dans la BDD
    * @param string $email Email de l'utilisateur
    * @param string $password Mot de passe de l'utilisateur
    * @throw Exception si on ne trouve pas l'utilisateur en BDD
    */

    public function verifyUserCredentials($email, $passwordSaisi) {
        // extraction du mdp de l'utilisateur
        $requete = "SELECT password FROM utilisateur WHERE adresseCourriel = :email";
        // on prépare la requête
        $statement = $this->executeQuery($requete, ['email' => $email]);

        // on teste le nombre de lignes renvoyées
        if ($statement->rowCount() > 0) {
            // on récupère le mot de passe
            $passwordBDD = $statement->fetch()[0];
            $this->testPasswords($passwordSaisi, $passwordBDD, $email);
        } else {
            throw new Exception('The user ' . $email . ' doesn\'t exist.');
        }
    }

    /**
    *
    * @param type $passwordSaisi
    * @param type $passwordBDD
    * @param type $email
    * @throws Exception
    */
    protected function testPasswords($passwordSaisi, $passwordBDD, $email) {
        // on teste si les mots de passe correspondent
        if (password_verify($passwordSaisi, $passwordBDD)) {
            if ($this->logger) {
                $this->logger->info('User ' . $email . ' now connected.');
            }
        } else {
            throw new Exception('Bad password for the user ' . $email);
        }
    }

    /**
    * Méthode qui retourne l'id d'un utilisateur passé en paramètre
    * @param string $utilisateur Adresse email de l'utilisateur
    * @return string $id Identifiant de l'utilisateur
    */
    public function getUserIDByEmailAddress($utilisateur) {
        // requête qui récupère l'ID grâce à l'adresse email
        $requete = "SELECT userID FROM utilisateur WHERE adresseCourriel = :email";

        // on récupère le résultat de la requête
        $resultat = $this->executeQuery($requete, ['email' => $utilisateur]);

        // on teste le nombre de lignes renvoyées
        if ($resultat->rowCount() > 0) {
            // on récupère la première (et seule) ligne retournée
            $row = $resultat->fetch();
            // l'id est le premier élément du tableau de résultats
            return $row[0];
        } else {
            return null;
        }
    }

    /**
    * Méthode qui retourne le nom et le prénom d'un utilisateur donné
    * @param string $utilisateur Adresse email de l'utilisateur
    * @return array[] Le nom et prénom de l'utilisateur
    */
    public function getUserByEmailAddress($email) {
        // on construit la requête qui va récupérer le nom et le prénom de l'utilisateur
        $requete = "SELECT * FROM utilisateur "
        . "WHERE adresseCourriel = :email";

        // on extrait le résultat de la BDD sous forme de tableau associatif
        $resultat = $this->extraire1xN($requete, ['email' => $email], false);

        // on construit l'objet Utilisateur
        $utilisateur = $this->buildUtilisateur($resultat);
        // on retourne l'utilisateur
        return $utilisateur;
    }

    /**
    * Méthode qui ajoute un utilisateur dans la BDD
    * @param string $firstName Prénom de l'utilisateur
    * @param string $lastName Nom de l'utilisateur
    * @param string $email Adresse email de l'utilisateur
    * @param string $password Mot de passe de l'utilisateur
    */
    public function createUser($firstName, $lastName, $email, $password) {
        // construction de la requête
        $requete = "INSERT INTO utilisateur (prenom, nom, adresseCourriel, password) "
        . "VALUES (:firstName, :lastName, :email, :password)";

        // exécution de la requête
        $this->executeQuery($requete,
        [':firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password' => $password]);

        if ($this->logger) {
            $this->logger->info('User ' . $email . ' successfully created.');
        }
    }



    /**
    * Méthode qui instancie un objet Utilisateur et qui le retourne.
    * @param array $row  un tableau résultat d’une requête SELECT
    */
    public function buildUtilisateur($row){
        $utilsateur = new Utilisateur();
        $utilsateur->setUserId($row['USERID']);
        $utilsateur->setNom($row['NOM']);
        $utilsateur->setPrenom($row['PRENOM']);
        $utilsateur->setAdresseCourriel($row['ADRESSECOURRIEL']);
        $utilsateur->setPassword($row['PASSWORD']);
        return $utilsateur;
    }

    public function getUserByID($id){
        // on construit la requête qui va récupérer le nom et le prénom de l'utilisateur
        $requete = "SELECT * FROM utilisateur "
        . "WHERE userid = :id";

        // on extrait le résultat de la BDD sous forme de tableau associatif
        $resultat = $this->extraire1xN($requete, ['id' => $id], false);

        // on construit l'objet Utilisateur
        $utilisateur = $this->buildUtilisateur($resultat);
        // on retourne l'utilisateur
        return $utilisateur;
    }

}
