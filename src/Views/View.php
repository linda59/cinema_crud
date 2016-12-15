<?php

namespace Semeformation\Mvc\Cinema_crud\Views;

use \Exception;

/**
 * Description of View
 *
 * @author admin
 */
class View {

// Nom du fichier associé à la vue
    private $fichier;
    // titre onglet
    private $titre;
    //  template principal
    const TEMPLATE = "views/viewTemplate.php";

    public function __construct($action) {
// La vue à générer dépend de l'action demandée
        $this->fichier = "Views/view" . $action . ".php";
    }

    /*
     * Génère et affiche la vue
     */

    public function generer($donnees = null) {
// Génération de la partie spécifique de la vue
        $content = $this->genererFichier($this->fichier, $donnees);
        // utilisation du template avec chargement des données spécifiques
        $vue = $this->genererFichier(View::TEMPLATE, ['title' => $this->titre,
            'content' => $content]);
// Renvoi de la vue au navigateur
        echo $vue;
    }

    /*
     * Génère et retourne la vue générée
     */

    private function genererFichier($fichier, $donnees) {
        if (file_exists($fichier)) {
// déclare autant de variables qu'il y en a dans le tableau
            if ($donnees !== null) {
                extract($donnees);
            }
// Toutes les données ne vont pas au navigateur mais dans un tampon
            ob_start();
// La vue est envoyée dans la tampon de sortie
            include $fichier;
// Renvoi du contenu du tampon et nettoyage
            return ob_get_clean();
        } else {
            throw new Exception('Impossible de trouver une vue nommée ' . $fichier);
        }
    }

}
