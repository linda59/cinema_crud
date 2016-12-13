<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Film - Editer un film</title>
        <link rel="stylesheet" type="text/css" href="css/cinema.css"/>
    </head>
    <body>
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
            <label>Catégorie du film :</label>
            <select type="hidden" name="modificationInProgress" value=<?= $film['CLASSIFICATION'] ?>>
                <option value="18">-18</option>
                <option value="16">-16</option>
                <option value="12">-12</option>
                <option value="10">-10</option>
                <option value="3">-3</option>
            </select>
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
            <input type="submit" name="backToList" value="Retour à la liste"/>
        </form>
    </body>
</html>