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
                        <h3>Welcome to <?php echo $userMember->username; ?>'s profile!</h3>
                    </div>
                    <h1 id="create" class="pull-left">Upload your fav comic images below:</h1>
                    <?php if(userType($userMember->id) == 'Admin') { ?>
                    <form method="post" action="profile.php?user=<?php echo $userMember->id ?>">
                       <button name="do_make_admin" type="submit"  class="btn btn-success pull-right" value="<?php echo $userMember->id;?>">Make user an Administrator!</button>
                     </form>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <hr>
                    <h1>Ajax Image uploader coming soon..</h1>
                    <hr>
                    <h1 class="pull-left"><?php echo $title; ?></h1>
                    <div class="clearfix"></div>
                    
                    <ul id="articles">
                        <?php
                        // check if they're any articles..
                         if (isset($authors)) {
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
