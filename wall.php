<?php session_start(); ?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php include "header.php";?>
       
        <div id="wrapper">        

            <?php
            include "includes.php";
            
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId = intval($_GET['user_id']);
            
            ?>
            
            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                /* echo "<pre>" . print_r($user, 1) . "</pre>"; */
                ?>
                <img src="poulet_badass.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les messages de l'utilisatrice : <?php echo $user['alias'] ?>
                    </p>
                </section>
            </aside>
            
            <main>
            <article id="new-post"> 
            <?php 
            if ($userId == $connected_id) {
                $commentaire_wall = <<<END
                <form action='wall.php?user_id={$_SESSION['connected_id']}' method='post'>
                <input type='hidden' name='???' value='achanger'>
                <label for='content'>Mon message</label>
                <textarea name='content' placeholder='Ecrire un message' ></textarea>
                <input type='submit' value='Envoyer'>
                </form>
                END;
            echo $commentaire_wall;            
            }
            else {
            $subscription = <<<END
            <form action='wall.php' method='post'>
            <input type='hidden' name='user_id' value='${userId}'>
            <input type='hidden' name='connected_user' value='${connected_id}'>
            <label for='subscription'>
            <input type='submit' name='subscription' value="S'abonner">
            </form>
            END;
            echo $subscription;
            }
           
            
            $enCoursDeTraitement2 = isset($_POST['subscription']);
                    if ($enCoursDeTraitement2)
                    {
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>", "turtle";
                        $followed_id = intval($mysqli->real_escape_string($_POST['user_id']));
                        $follower_id = $mysqli->real_escape_string($connected_id);
                        $lInstructionSql2 = "INSERT INTO followers"
                                . "(id, followed_user_id, following_user_id)"
                                . "VALUES (NULL, "
                                . $followed_id . ", "
                                . $follower_id . "); "
                                ;
                        echo $lInstructionSql2;
                        $ok = $mysqli->query($lInstructionSql2);
                        if ( ! $ok)
                        {
                            echo "Impossible de vous connecter : " . $mysqli->error;
                        } else
                        {
                            echo "Vous êtes connecté.e à :" . $followed_id;
                        } 
                    }
            $enCoursDeTraitement = isset($_POST['content']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        // echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $connected_id;
                        $postContent = $_POST['content'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            //echo "Message posté en tant que :" . $listAuteurs[$authorId];
                        } 
                    } 

            ?>
            </article>
                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, posts.id as post_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                    //  echo "<pre>" . print_r($post, 1) . "</pre>";
                include "post.php";
                } ?>


            </main>
        </div>
    </body>
</html>
