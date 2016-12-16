<?php $this->titre = 'Cinéma - Editer un cinéma'; ?>

<h1>Ajouter/Modifier un cinéma</h1>
<!--
<form method="POST" name="editCinema" action="editCinema.php">
-->
<form method="POST" name="editCinema" action="index.php?action=editCinema">
    <label>Dénomination :</label>

    <input name="denomination" type="text" value="<?php  if(!is_array($cinema))  echo $cinema->getDenomination(); else echo $cinema['DENOMINATION']?>" required/>
    <label>Adresse :</label>
    <textarea name="adresse" required><?php  if(!is_array($cinema)) echo $cinema->getAdresse(); else echo $cinema['ADRESSE']?></textarea>
    <br/>
    <input type="hidden" value="<?php  if(!is_array($cinema)) echo $cinema->getCinemaId(); else echo $cinema['CINEMAID']?>" name="cinemaID"/>
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
