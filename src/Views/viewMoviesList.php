<?php $this->titre = "Liste des films"; ?>

<header><h1>Liste des films</h1></header>
<table class="std">
    <tr>
        <th>Titre</th>
        <th>Titre original</th>
        <th>Date sortie</th>
        <th colspan="3">Action</th>
    </tr>
    <?php
    // on récupère la liste des films ainsi que leurs informations
    //$films = $fctManager->getMoviesList();
    //$films =  $fctFilm->getMoviesList();
    //$films = $managers["filmsMgr"]->getMoviesList();
    // boucle de construction de la liste des cinémas
    foreach ($films as $film) {
        ?>
        <tr>
            <td><?= $film->getTitre() ?></td>
            <td><?= $film->getTitreOriginal() ?></td>
            <td><?= $film->getDateSortie() ?></td>
            <td>
                <!--
                <form name="movieShowtimes" action="movieShowtimes.php" method="GET">
                -->
                <form name="movieShowtimes" action="index.php" method="GET">              
                    <input name="action" type="hidden" value="movieShowtimes"/> 
                    <input name="filmID" type="hidden" value="<?= $film->getFilmID() ?>"/>
                    <input type="submit" value="Consulter les séances"/>
                </form>
            </td>
            <?php if ($isUserAdmin): ?>
                <td>
                    <!--<form name="modifyMovie" action="editMovie.php" method="GET">
                    -->
                    <form name="modifyMovie" action="index.php" method="GET">              
                        <input name="action" type="hidden" value="editMovie"/> 
                        <input type="hidden" name="filmID" value="<?= $film->getFilmID() ?>"/>
                        <input type="image" src="images/modifyIcon.png" alt="Modify"/>
                    </form>
                </td>
                <td>
                    <!--
                    <form name="deleteMovie" action="deleteMovie.php" method="POST">
                    -->                            
                    <form  name="deleteMovie" action="index.php?action=deleteMovie" method="POST">  
                        <input type="hidden" name="filmID" value="<?= $film->getFilmID() ?>"/>
                        <input type="image" src="images/deleteIcon.png" alt="Delete"/>
                    </form>
                </td>
            <?php endif; ?>
        </tr>
        <?php
    }
    ?>
    <?php if ($isUserAdmin): ?>
        <tr class="new">
            <td colspan="5">
                <!--
                <form name="addMovie" action="editMovie.php">
                -->
                <form name="addMovie" action="index.php" method="GET">              
                    <input name="action" type="hidden" value="editMovie"/> 
                    <button class="add" type="submit">Cliquer ici pour ajouter un film...</button>
                </form>
            </td>
        </tr>
    <?php endif; ?>
</table>
<form name="backToMainPage" action="index.php">
    <input type="submit" value="Retour à l'accueil"/>
</form>