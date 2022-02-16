<?php 
$enCoursDeLike = isset($_POST['liked']);
if ($enCoursDeLike){
    $seConnecter = isset($_SESSION['connected_id']);
    if ($seConnecter){
        $likedPost = intval($mysqli->real_escape_string($_POST['post_id']));
        $dejaLike = "SELECT * FROM likes WHERE user_id = ". $_SESSION["connected_id"] ." AND post_id = ".$_POST['post_id'] .";";
        $dejaLikeResult = $mysqli->query($dejaLike);
        $alreadyLiked = $dejaLikeResult->fetch_assoc();
        if(!$alreadyLiked){
            $postLiked = "INSERT INTO likes"
            . "(id, user_id, post_id)"
            . "VALUES (NULL,"
            . $_SESSION["connected_id"] . ","
            . $likedPost . ");";
            $ok = $mysqli->query($postLiked);
            if (! $ok){
             echo "Votre muscle n'a pas été pris en compte" . $mysqli->error;
         } else {
             echo "Vous êtes maintenant plus musclé";
         }
        } else {
            $unlikePost = "DELETE FROM likes WHERE post_id = ". $liked_post . " AND user_id = " . $_SESSION["connected_id"] . ";";
            $ok = $mysqli->query($unlikePost);
        }
    } else {
        echo "Connectez-vous";
        }
        
    }
                    $post_article = <<<END
                    <article>
                        <h3>
                            <!-- TODO : mettre en forme la date-->
                            <time>{$post['created']}</time>
                        </h3>
                        <address><a href='wall.php?user_id={$post['user_id']}'>{$post['author_name']}</a></address>
                        <div>
                            <p>{$post['content']}</p>
                        </div>                                            
                        <footer>
                        <small> 💪 {$post['like_number']}</small>
                            <a href="">#{$post['taglist']}</a>
                        </footer>
                        <form action="wall.php?user_id={$_SESSION['connected_id']}" method="post">
                            <input type='hidden' name='liked' value='true'>
                            <input type='hidden' name='post_id' value="{$post['post_id']}">
                            <input type='submit' value='more muscle'>
                        </form>
                    END;
                    echo $post_article;
                    ?>



