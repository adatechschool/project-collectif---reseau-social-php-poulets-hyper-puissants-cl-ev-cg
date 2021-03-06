<?php session_start(); ?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include "header.php"; ?>

        <div id="wrapper">
            <aside>
                <img src="poulet_badass.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes dont
                        l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?>
                        suit les messages
                    </p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                include "includes.php";
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                while ($post = $lesInformations->fetch_assoc())
                {
                 //echo "<pre>" . print_r($post, 1) . "</pre>"; 
                ?>
                <article>
                    <img src="poulet_badass.jpg" alt="blason"/>
                    <h3><?php echo $post['alias'] ?></h3>
                    <p>id :<?php echo $post['id'] ?></p>                    
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
