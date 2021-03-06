<?php
namespace Semeformation\Mvc\Cinema_crud\DAO;

Use Semeformation\Mvc\Cinema_crud\Includes\DAO;
use Semeformation\Mvc\Cinema_crud\Models\Seance;

/**
 * Description of SeanceDAO
 *
 * @author admin
 */
class SeanceDAO extends DAO {
    
    /**
     * Renvoie une liste de films pas encore programmés pour un cinema donné
     * @param integer $cinemaID
     * @return array
     */
    public function getNonPlannedMovies($cinemaID) {
        // requête de récupération des titres et des identifiants des films
        // qui n'ont pas encore été programmés dans ce cinéma
        $requete = "SELECT f.filmID, f.titre "
                . "FROM film f"
                . " WHERE f.filmID NOT IN ("
                . "SELECT filmID"
                . " FROM seance"
                . " WHERE cinemaID = :id"
                . ")";
        // extraction de résultat
        $resultat = $this->extraireNxN($requete, ['id' => $cinemaID], false);
        // retour du résultat
        return $resultat;
    }
    
    /**
     * Renvoie une liste de cinémas qui ne projettent pas le film donné
     * @param integer $filmID
     * @return array
     */
    public function getNonPlannedCinemas($filmID) {
        // requête de récupération des titres et des identifiants des films
        // qui n'ont pas encore été programmés dans ce cinéma
        $requete = "SELECT c.cinemaID, c.denomination "
                . "FROM cinema c"
                . " WHERE c.cinemaID NOT IN ("
                . "SELECT cinemaID"
                . " FROM seance"
                . " WHERE filmID = :id"
                . ")";
        // extraction de résultat
        $resultat = $this->extraireNxN($requete, ['id' => $filmID], false);
        // retour du résultat
        return $resultat;
    }
    
    /**
     * 
     * @param type $cinemaID
     * @return type
     */
    public function getCinemaMoviesByCinemaID($cinemaID) {
        // requête qui nous permet de récupérer la liste des films pour un cinéma donné
        $requete = "SELECT DISTINCT f.* FROM film f"
                . " INNER JOIN seance s ON f.filmID = s.filmID"
                . " AND s.cinemaID = " . $cinemaID;
        // on extrait les résultats
        $resultat = $this->extraireNxN($requete);
        // on retourne le résultat
        return $resultat;
    }
    
    /**
     *
     * @param type $cinemaID
     * @param type $filmID
     * @return type
     */
    public function verifierFilm($cinemaID, $filmID, $dateheuredebut,$dateheurefin) {
        // requête qui permet de récupérer la liste des séances d'un film donné dans un cinéma donné
        $requete = "SELECT s.* FROM seance s"
                . " WHERE s.filmID = " . $filmID
                . " AND s.cinemaID = " . $cinemaID
                  . " AND s.HEUREDEBUT = '" . $dateheuredebut
                  . "' AND s.HEUREFIN = '" . $dateheurefin."'";
        // on extrait les résultats
        $resultat = $this->extraire1xN($requete);
        // on retourne la requête
        return $resultat;
    }
    
    /**
     * 
     * @param type $cinemaID
     * @param type $filmID
     * @return type
     */
    public function getMovieShowtimes($cinemaID, $filmID) {
        // requête qui permet de récupérer la liste des séances d'un film donné dans un cinéma donné
        $requete = "SELECT s.* FROM seance s"
                . " WHERE s.filmID = " . $filmID
                . " AND s.cinemaID = " . $cinemaID;
        // on extrait les résultats
        $resultat = $this->extraireNxN($requete);
        // on retourne la requête
        return $resultat;
    }
    
    /**
     * Supprime une séance pour un film donné et un cinéma donné
     * @param type $cinemaID
     * @param type $filmID
     * @param type $heureDebut
     * @param type $heureFin
     */
    public function deleteShowtime($cinemaID, $filmID, $heureDebut, $heureFin) {
        $this->executeQuery("DELETE FROM seance "
                . "WHERE cinemaID = :cinemaID "
                . "AND filmID = :filmID "
                . "AND heureDebut = :heureDebut"
                . " AND heureFin = :heureFin",
                [':cinemaID' => $cinemaID,
            ':filmID' => $filmID,
            ':heureDebut' => $heureDebut,
            ':heureFin' => $heureFin]);

        if ($this->logger) {
            $this->logger->info('Showtime for the movie ' . $filmID . ' and the cinema ' . $cinemaID . ' successfully deleted.');
        }
    }
    
    /**
     * Supprime une séance pour un film donné et un cinéma donné
     * @param type $cinemaID
    
     */
    public function deleteShowtimeByIdCinema($cinemaID) {
        $this->executeQuery("DELETE FROM seance "
                . "WHERE cinemaID = :cinemaID ",
                [':cinemaID' => $cinemaID]);

        if ($this->logger) {
            $this->logger->info('Showtime for the movie  ... and the cinema ' . $cinemaID . ' successfully deleted.');
        }
    }
    
    /**
     * Insère une nouvelle séance pour un film donné dans un cinéma donné
     * @param integer $cinemaID
     * @param integer $filmID
     * @param datetime $dateheuredebut
     * @param datetime $dateheurefin
     * @param string $version
     */
    public function insertNewShowtime($cinemaID, $filmID, $dateheuredebut, $dateheurefin, $version) {
        // construction
        $requete = "INSERT INTO seance (cinemaID, filmID, heureDebut, heureFin, version) VALUES ("
                . ":cinemaID"
                . ", :filmID"
                . ", :heureDebut"
                . ", :heureFin"
                . ", :version)";
        // exécution
        $resultat = $this->executeQuery($requete,
                [':cinemaID' => $cinemaID,
            ':filmID' => $filmID,
            ':heureDebut' => $dateheuredebut,
            ':heureFin' => $dateheurefin,
            ':version' => $version]);

        // log
        if ($this->logger) {
            $this->logger->info('Showtime for the movie ' . $filmID . ' at the ' . $cinemaID . ' successfully added.');
        }

        return $resultat;
    }
    
    /**
     * Insère une nouvelle séance pour un film donné dans un cinéma donné
     * @param integer $cinemaID
     * @param integer $filmID
     * @param datetime $dateheuredebutOld
     * @param datetime $dateheurefinOld
     * @param datetime $dateheuredebut
     * @param datetime $dateheurefin
     * @param string $version
     */
    public function updateShowtime($cinemaID, $filmID, $dateheuredebutOld, $dateheurefinOld, $dateheuredebut, $dateheurefin, $version): \PDOStatement {
        // construction
        $requete = "UPDATE seance SET heureDebut = :heureDebut,"
                . " heureFin = :heureFin,"
                . " version = :version"
                . " WHERE cinemaID = :cinemaID"
                . " AND filmID = :filmID"
                . " AND heureDebut = :heureDebutOld"
                . " AND heureFin = :heureFinOld";
        // exécution
        $resultat = $this->executeQuery($requete,
                [':cinemaID' => $cinemaID,
            ':filmID' => $filmID,
            ':heureDebutOld' => $dateheuredebutOld,
            ':heureFinOld' => $dateheurefinOld,
            ':heureDebut' => $dateheuredebut,
            ':heureFin' => $dateheurefin,
            ':version' => $version]);

        // log
        if ($this->logger) {
            $this->logger->info('Showtime for the movie ' . $filmID . ' at the ' . $cinemaID . ' successfully updated.');
        }

        return $resultat;
    }
    
    /**
     * Méthode qui instancie un objet Utilisateur et qui le retourne.
     * @param array $row  un tableau résultat d’une requête SELECT
     */
    public function buildBusinessObject($row){
        $seance = new Seance();
        if(array_key_exists('CINEMAID', $row)) {
            $cinemaID = $row['CINEMAID'];
            $cinema = $this->cinemaDAO->getCinemaByID($cinemaID);
            $seance->setCinemaID($cinema);
        }
        
        if(array_key_exists('FILMID', $row)) {
            $filmID = $row['FILMID'];
            $film = $this->filmDAO->getMovieByID($filmID);
            $seance->setFilmID($film);
        }

        $seance->setHeureDebut($row['HEUREDEBUT']);
        $seance->setHeureFin($row['HEUREFIN']);
        $seance->setVersion($row['VERSION']);
        return $seance;
    }
    
}


//$seance = new SeanceDAO()->buildBusinessObject($row);