<!-- <?php echo $test; ?> -->
<?php include('./partials/_header.php'); ?>
<div class="jumbotron text-center">
    <h1 >Welcome to the blog</h1>
    <p >Howdy there! You look like someone who could write a thing or two about your fav comics!</p>
    <a href="register.html" class="btn btn-success">Come join us!</a>
</div>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <h1 class="pull-left"><?php echo $title; ?></h1>
                    <div class="clearfix"></div>
                    <hr>
                    <ul id="articles">
                        <?php
                        // check if they're any articles..
                        if (isset($tags)) {
                            foreach($tags as $key => $tag) :
                                
                                ?>
                                <li class="article">
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <h3><a href="article.php?article=<?php echo $link[$key]->id; ?>"><?php echo $tag->title ?></a></h3>
                                            
                                            <div class="article-info">
                                                <a href="articles.php?tag=<?php echo urlFormat($tag->tag_id); ?>"
                                                   class="label label-primary"><?php echo $tag->name ?></a>
                                                | Published: <?php echo formatDate($tag->created_at); ?>
                                                | By: <a href="articles.php?user=<?php echo urlFormat($tag->user_id); ?>"><?php echo $tag->username ?></a>
                                                | <?php echo calcReadTime($tag->body); ?>
                                                <hr>
                                                <div class="clearfix"></div>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            endforeach;
                        }else if (isset($authors)) {
                            if(empty($authors)) {echo "<h3 class='well text-center'>No Articles Yet!!!";}
                            foreach($authors as $author) :
                                ?>
                                <li class="article">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3><a href="article.php?article=<?php echo $author->aid; ?>"><?php echo $author->title ?></a></h3>

                                                <div class="article-info">
                                                <a href="articles.php?tag=<?php echo urlFormat($author->tag_id); ?>"
                                                   class="label label-primary"><?php echo $author->name ?></a>
                                                | Published: <?php echo formatDate($author->created_at); ?>
                                                | By: <a href="articles.php?user=<?php echo urlFormat($author->user_id); ?>"><?php echo $author->username ?></a>
                                                | <?php echo calcReadTime($author->body); ?>
                                                <hr>
                                                <div class="clearfix"></div>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            endforeach;
                        }else {
                            // no articles
                            echo "<h3 class='well text-center'>No Articles Yet!!!";
                        }
                        ?>
                    </ul>

                    <?php include('./partials/_footer.php'); ?>
