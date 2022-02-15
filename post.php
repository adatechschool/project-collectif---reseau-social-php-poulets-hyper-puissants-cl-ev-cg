<?php 

if (isset($_POST['liked'])) {
    $postid = $_POST['post_id'];
    $result = mysqli_query($mysqli, "SELECT * FROM posts WHERE id=$postid");
    $row = mysqli_fetch_array($result);
    $n = $row['likes'];

    mysqli_query($mysqli, "INSERT INTO likes (user_id, post_id) VALUES (1, $postid)");
    mysqli_query($mysqli, "UPDATE posts SET likes=$n+1 WHERE id=$postid");

    echo $n+1;
    exit();
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
                    </article>
                    END;
                    echo $post_article;
                    ?>