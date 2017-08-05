<!-- <?php echo $test; ?> -->
<?php include('./partials/_header.php'); ?>
<?php  displayMessage(); // display flash message?>
<div class="jumbotron text-center">
    <h1 >Welcome to the blog</h1>
    <p >Howdy there! You look like someone who could write a thing or two about your fav comics!</p>
    <a href="register.php" class="btn btn-success">Come join us!</a>
</div>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <h1 class="pull-left">All Articles</h1>
                    <div class="clearfix"></div>
                    <hr>
                    <ul id="articles">
                        <?php
                        // check if they're any articles..
                        if ($articles) {
                            foreach($articles as $article) :
                        if($article->status != 1) { ?>
                            <li class="article">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if($article->isFeatured == 1) { // FEATURED ARTCLE CSS NEEDS TO BE CHANGED HERE ?>
                                        <div class="article-info-featured">
                                        <h3>
                                            <a href="article.php?article=<?php echo $article->id; ?>"><?php echo $article->title ?> (FEATURED remove this once css is added)</a>
                                        </h3>
                                        <div class="article-info">
                                            <a href="articles.php?tag=<?php echo urlFormat($article->tag_id); ?>"
                                               class="label label-primary"><?php echo $article->name ?></a>
                                            | Published: <?php echo formatDate($article->created_at); ?>
                                            | By: <a
                                                    href="articles.php?user=<?php echo urlFormat($article->user_id); ?>"><?php echo $article->username ?></a>
                                            | <?php echo calcReadTime($article->body); ?>
                                            <hr>
                                            <p class="article-info-body"><?php echo articleSample($article->body) ?></p>
                                            <br>
                                            <span class="pull-left label label-info"><?php if (commentCount($article->id)) {
                                                    echo commentCount($article->id) . ' Comments'; 
                                                   
                                                } else {
                                                    echo '';
                                                } ?></span>
                                            <a href="article.php?article=<?php echo urlFormat($article->id); ?>"
                                               class="pull-right">Contiue reading..</a>
                                            <div class="clearfix"></div>
                                            <hr>
                                        </div>
                                        </div>
                                        <?php }else {  // NOT FEATURED NOTHING NEEDS TO BE CHANGED BELOW ?>
                                        <h3>
                                            <a href="article.php?article=<?php echo $article->id; ?>"><?php echo $article->title ?></a>
                                        </h3>
                                        <div class="article-info">
                                            <a href="articles.php?tag=<?php echo urlFormat($article->tag_id); ?>"
                                               class="label label-primary"><?php echo $article->name ?></a>
                                            | Published: <?php echo formatDate($article->created_at); ?>
                                            | By: <a
                                                    href="articles.php?user=<?php echo urlFormat($article->user_id); ?>"><?php echo $article->username ?></a>
                                            | <?php echo calcReadTime($article->body); ?>
                                            <hr>
                                            <p class="article-info-body"><?php echo articleSample($article->body) ?></p>
                                            <br>
                                            <span class="pull-left label label-info"><?php if (commentCount($article->id)) {
                                                    echo commentCount($article->id) . ' Comments';
                                                } else {
                                                    echo '';
                                                } ?></span>
                                            <a href="article.php?article=<?php echo urlFormat($article->id); ?>"
                                               class="pull-right">Contiue reading..</a>
                                            <div class="clearfix"></div>
                                            <hr>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                            endforeach;
                        }else{
                            // no articles
                            echo "<h3 class='well text-center'>No Articles Yet!!!";
                        }
                        ?>
                    </ul>

<?php include('./partials/_footer.php'); ?>
