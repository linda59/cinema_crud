<?php $this->titre = "Cinema CRUD"; ?>

<div>
    <header>
        <h1>Espace personnel</h1>
    </header>
    <?php
    // si pas encore authentifié
    if (!$loginSuccess):
        ?>

        <form method="POST" name="editFavoriteMoviesList" action="index.php">               
            <input name="action" type="hidden" value="editFavoriteMoviesList"/> 
            <label>Adresse email : </label>
            <input type="email" name="email" required/>
            <label>Mot de passe  : </label>
            <input type="password" name="password" required/>
            <div class="error">
                <?php
                if (!$areCredentialsOK):
                    echo "Les informations de connexions ne sont pas correctes.";
                endif;
                ?>
            </div>
            <input type="submit" value="Editer ma liste de films préférés"/>
        </form>                

        <p>Pas encore d'espace personnel ? 
            <a href="index.php?action=createUser">Créer sa liste de films préférés.</a>
        </p>   




        <?php
    // sinon (utilisateur authentifié)
    else:
        ?>
        <!--
        <form action="editFavoriteMoviesList.php">
            <input type="submit" value="Editer ma liste de films préférés"/>                
        </form>
        -->
        <form name="editFavoriteMoviesList" method="GET" action="index.php?action=editFavoriteMoviesList.php"> 
            <input name="action" type="hidden" value="editFavoriteMoviesList"/> 
            <input type="submit" value="Editer ma liste de films préférés"/>
        </form>
        <a href="logout.php">Se déconnecter</a>
    <?php endif; ?>
</div>
<!-- Gestion des cinémas -->
<div>
    <header>
        <h1>Gestion des cinémas</h1>
        <!-- 
        <form name="cinemasList" action="cinemasList.php">
            <input type="submit" value="Consulter la liste des cinémas"/>
        </form>
        -->
        <form name="cinemasList" method="GET" action="index.php"> 
            <input name="action" type="hidden" value="cinemasList"/> 
            <input type="submit" value="Consulter la liste des cinémas"/>
        </form>
        <!--
        <form name="moviesList" action="moviesList.php">
        -->
        <form name="moviesList" method="GET" action="index.php"> 
            <input name="action" type="hidden" value="moviesList"/> 
            <input type="submit" value="Consulter la liste des films"/>
        </form>
    </header>
</div>