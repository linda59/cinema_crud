<?php $this->titre = "Espace personnel - Films préférés"; ?>

<header><h1><?= $utilisateur->getPrenom() ?> <?= $utilisateur->getNom() ?>, ci-dessous vos films préférés</h1></header>
<table class="std">
    <tr>
        <th>Titre</th>
        <th>Commentaire</th>
        <th colspan="2">Action</th>
    </tr>
    <?php
    // on récupère la liste des films préférés grâce à l'utilisateur identifié
    //$films = $fctManager->getFavoriteMoviesFromUser($utilisateur['userID']);
    //$films = $fctPrefere->getFavoriteMoviesFromUser($utilisateur['userID']);
    //$films = $managers["preferesMgr"]->getFavoriteMoviesFromUser($utilisateur['userID']);
    // si des films ont été trouvés
    if ($films) {
        // boucle de création du tableau
        foreach ($films as $film) {
            ?>
            <tr>
                <td><?= $film['titre'] ?></td>
                <td><?= $film['commentaire'] ?></td>
                <td>
                    <!--
                    <form name="modifyFavoriteMovie" action="editFavoriteMovie.php" method="GET">
                    -->
                    <form name="modifyFavoriteMovie" action="index.php" >
                        <input name="action" type="hidden" value="editFavoriteMovie"/>
                        <input type="hidden" name="userID" value="<?= $utilisateur->getUserId() ?>"/>
                        <input type="hidden" name="filmID" value="<?= $film['filmID'] ?>"/>
                        <input type="image" src="images/modifyIcon.png" alt="Modify"/>
                    </form>
                </td>
                <td>
                    <!--
                    <form name="deleteFavoriteMovie" action="deleteFavoriteMovie.php" method="POST">
                    -->
                    <form name="deleteFavoriteMovie" action="index.php?action=deleteFavoriteMovie" method="POST">
                        <input type="hidden" name="userID" value="<?= $utilisateur->getUserId() ?>"/>
                        <input type="hidden" name="filmID" value="<?= $film['filmID'] ?>"/>
                        <input type="image" src="images/deleteIcon.png" alt="Delete"/>
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    <?php
    if (count($nbfilms) > count($films)) {
//if (count($managers['filmsMgr']->getMoviesList()) > count($films)){
        ?>
        <tr class="new">
            <td colspan="4">
                <!--
                <form name="addFavoriteMovie" action="editFavoriteMovie.php">
                -->
                <form name="addFavoriteMovie" action="index.php">
                    <input name="action" type="hidden" value="editFavoriteMovie"/>
                    <button class="add" type="submit">Cliquer pour ajouter un film préféré...</button>
                </form>

            </td>
        </tr>
        <?php
    }
    ?>
</table>

<form name="backToMainPage" action="index.php">
    <input type="submit" value="Retour à l'accueil"/>
</form>
