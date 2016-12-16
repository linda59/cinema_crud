<?php $this->titre = "Film - Editer un film"; ?>

<h1>Ajouter/Modifier un film</h1>
<!--
<form method="POST" name="editCinema" action="editMovie.php">
-->
<form method="POST" name="editCinema" action="index.php?action=editMovie">
    <label>Titre :</label>
    <input name="titre" type="text" value="<?= $film['TITRE'] ?>" required/>
    <label>Titre original :</label>
    <input name="titreOriginal" type="text" value="<?= $film['TITREORIGINAL'] ?>" /> <br/>
    <label>Date sortie :</label><br/>
    <input name="dateSortie" type="text" value="<?= $film['DATESORTIE'] ?>"  placeholder="01/01/2010"/><br/>
    <br/>
    <input type="hidden" value="<?= $film['FILMID'] ?>" name="filmID"/>

    <!--ajout de la liste deroulante pour choisir la categorie du film
        ajout de FILMTYPE-->

    <br/>
    <br/>
    <?php
    // si c'est une modification, c'est une information dont nous avons besoin
    if (!$isItACreation) {
        ?>
        <input type="hidden" name="modificationInProgress" value="true"/>
        <?php
    }
    ?>
    <input type="submit" name="saveEntry" value="Sauvegarder"/>
</form>
<form action="index.php">
    <input type="hidden" name="action" value="moviesList"/>
    <input type="submit" name="backToList" value="Retour à la liste"/>
</form>