<?php $this->titre = 'Gestion des cinémas - Cinémas'; ?>

<header><h1>Liste des cinémas</h1></header>
<table class="std">
    <tr>
        <th>Nom</th>
        <th>Adresse</th>
        <th colspan="3">Action</th>
    </tr>
    <?php
    // on récupère la liste des cinémas ainsi que leurs informations
    //$cinemas = $fctManager->getCinemasList();          
    //$cinemas = $fctCinema->getCinemasList();
    //$cinemas = $managers["cinemasMgr"]->getCinemasList();
    // boucle de construction de la liste des cinémas
    foreach ($cinemas as $cinema) {
        var_dump($cinema);
        ?>
        <tr>
            <td><?= $cinema->getDenomination() ?></td>
            <td><?= $cinema->getAdresse() ?></td>
            <td>
                <!--
                <form name="cinemaShowtimes" action="cinemaShowtimes.php" method="GET">
                -->
                <form name="cinemaShowtimes" action="index.php">
                    <input name="action" type="hidden" value="cinemaShowtimes"/> 
                    <input name="cinemaID" type="hidden" value="<?= $cinema->getCinemaID() ?>"/>
                    <input type="submit" value="Consulter les séances"/>
                </form>
            </td>
            <?php
            if ($isUserAdmin):
                ?>
                <td>
                    <!--
                    <form name="modifyCinema" action="editCinema.php" method="GET">
                    -->
                    <form name="modifyCinema" action="index.php">
                        <input name="action" type="hidden" value="editCinema"/> 
                        <input type="hidden" name="cinemaID" value="<?= $cinema->getCinemaID() ?>"/>
                        <input type="image" src="images/modifyIcon.png" alt="Modify"/>
                    </form>
                </td>
                <td>
                    <!--
                    <form name="deleteCinema" action="deleteCinema.php" method="POST">
                    -->
                    <form name="deleteCinema" action="index.php?action=deleteCinema" method="POST">
                        <input type="hidden" name="cinemaID" value="<?= $cinema->getCinemaID() ?>"/>
                        <input type="image" src="images/deleteIcon.png" alt="Delete"/>
                    </form>
                </td>
            <?php endif; ?>
        </tr>
        <?php
    }
    if ($isUserAdmin):
        ?>
        <tr class="new">
            <td colspan="5">
                <!--
                <form name="addCinema" action="editCinema.php">
                -->
                <form name="addCinema" action="index.php">
                    <input name="action" type="hidden" value="editCinema"/> 
                    <button class="add" type="submit">Cliquer ici pour ajouter un cinéma</button>
                </form>
            </td>
        </tr>

    <?php endif; ?>
</table>

<form name="backToMainPage" action="index.php">
    <input type="submit" value="Retour à l'accueil"/>
</form>
