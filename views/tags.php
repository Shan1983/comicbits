<!-- <?php echo $test; ?> -->
<?php include('./partials/_header.php'); ?>
<div class="jumbotron text-center">
    <h1 >Welcome to the blog</h1>
    <p >Howdy there! You look like someone who could write a thing or two about your fav comics!</p>
        <?php if (!isset($_SESSION['is_logged_in'])) { ?>
    <a href="register.php" id="hero-btn" class="btn btn-success">Come join us!</a>
    <?php } ?>
</div>

<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <div class="article-header">
                        <h3 class="pull-left"><?php echo $title; ?></h3>
                    </div>
                    
                    <div class="clearfix"></div>
<?php if (isset($tags)) {
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
                        } ?>
                         <?php include('./partials/_footer.php'); ?>