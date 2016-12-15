<?php

namespace Semeformation\Mvc\Cinema_crud\Controllers;
use Semeformation\Mvc\Cinema_crud\Views\View;
use Semeformation\Mvc\Cinema_crud\Models\Utilisateur;

/**
 * Description of HomeController
 *
 * @author admin
 */
class HomeController {

    /**
     * L'utilisateur de l'application
     */
    private $utilisateur;

    /**
     * Constructeur de la classe
     */
    public function __construct(\Psr\Log\LoggerInterface $logger=null) {
        $this->utilisateur = new Utilisateur($logger);
    }

    public function home() {
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
                $sanitizedEntries = filter_input_array(INPUT_POST, ['email' => FILTER_SANITIZE_EMAIL,
                    'password' => FILTER_DEFAULT]);
                try {


                    //$managers["utilisateursMgr"]->verifyUserCredentials($sanitizedEntries['email'], $sanitizedEntries['password']);
                    $this->utilisateur->verifyUserCredentials($sanitizedEntries['email'], $sanitizedEntries['password']);
                    // on enregistre l'utilisateur
                    $_SESSION['user'] = $sanitizedEntries['email'];
                    //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);          
                    //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
                    //$_SESSION['userID'] = $managers["utilisateursMgr"]->getUserIDByEmailAddress($_SESSION['user']);
                    $_SESSION['userID'] = $this->utilisateur->getUserIDByEmailAddress($_SESSION['user']);
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

    public function createNewUser() {
        // variables de contrôles du formulaire de création
        $isFirstNameEmpty = false;
        $isLastNameEmpty = false;
        $isEmailAddressEmpty = false;
        $isUserUnique = true;
        $isPasswordEmpty = false;
        $isPasswordConfirmationEmpty = false;
        $isPasswordValid = true;

// si la méthode POST est utilisée, cela signifie que le formulaire a été envoyé
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === "POST") {
            // on "sainifie" les entrées
            $sanitizedEntries = filter_input_array(INPUT_POST, ['firstName' => FILTER_SANITIZE_STRING,
                'lastName' => FILTER_SANITIZE_STRING,
                'email' => FILTER_SANITIZE_EMAIL,
                'password' => FILTER_DEFAULT,
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
                //$userID = $managers["utilisateursMgr"]->getUserIDByEmailAddress($sanitizedEntries['email']);
                $userID = $this->utilisateur->getUserIDByEmailAddress($sanitizedEntries['email']);
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
                $password = password_hash($sanitizedEntries['password'], PASSWORD_DEFAULT);
                // créer l'utilisateur
                /* $fctManager->createUser($sanitizedEntries['firstName'],
                  $sanitizedEntries['lastName'],
                  $sanitizedEntries['email'],
                  $password);
                 * */
                //$utilisateursMgr->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);
                $this->utilisateur->createUser($sanitizedEntries['firstName'], $sanitizedEntries['lastName'], $sanitizedEntries['email'], $password);
                //session_start();
                // authentifier l'utilisateur
                $_SESSION['user'] = $sanitizedEntries['email'];
                //$_SESSION['userID'] = $fctManager->getUserIDByEmailAddress($_SESSION['user']);
                //$_SESSION['userID'] = $utilisateursMgr->getUserIDByEmailAddress($_SESSION['user']);
                $_SESSION['userID'] = $this->utilisateur->getUserIDByEmailAddress($_SESSION['user']);
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
            $sanitizedEntries['lastName'] = '';
            $sanitizedEntries['email'] = '';
        }
        $vue = new View('CreateUser');
        $vue->generer((['sanitizedEntries' => $sanitizedEntries,
            'isFirstNameEmpty' => $isFirstNameEmpty,
            'isLastNameEmpty' => $isLastNameEmpty,
            'isEmailAddressEmpty' => $isEmailAddressEmpty,
            'isUserUnique' => $isUserUnique,
            'isPasswordEmpty' => $isPasswordEmpty,
            'isPasswordConfirmationEmpty' => $isPasswordConfirmationEmpty,
            'isPasswordValid' => $isPasswordValid]));
//    require 'views/viewCreateUser.php';
    }
    
    public function logout(){        
        session_start();
        session_destroy();
        header('Location: index.php');
    }

}
