<?php
    require_once "../blocks/databaseConnect.php";
?>

<div class="container">
    <div class="popular">
        <div class="popular_header">Популярное в подписке</div>
        <div class="popular_films">
            <?php 
                $query_text = 'SELECT * FROM "filmsRus" ';
                $query = pg_query($db_connection, $query_text);
                $coun = 12;
                while ($res = pg_fetch_object($query)):
            ?>
                <div class="film">
                    <a class="film_link" href="blocks/watch.php?watch_id=<?php echo $res -> id ?>"></a>
                    <div class="film_poster">
                        <div class="poster_detail">
                        <a class="film_link" href="blocks/watch.php?watch_id=<?php echo $res -> id ?>"></a>
                            <div class="detail_rating"><?php echo $res -> rating ?></div>
                            <div class="detail_genre"><?php echo $res -> year ?>, <?php echo $res -> genre ?> </div>
                            <div class="detail_duration"><?php echo $res -> duration ?> минут</div>
                        </div>
                        <img src="<?php echo $res -> poster ?>" alt="">
                    </div>
                    <div class="film_name"><?php echo $res -> title ?></div>
                    <div class="film_sub">Подписка</div>
                </div>
            <?php 
                $coun -= 1;
                if ($coun == 0)
                    exit;
                endwhile; 
                pg_free_result($query); 
            ?>
        </div>
    </div>
</div>