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
                    <h1 class="pull-left">Your search results are served..</h1>
                    </div>
                    <div class="clearfix"></div>
                    
                    <ul id="create">
                        <?php if($searchResults) { ?>
                        <?php foreach($searchResults as $result) {?>
    
                            <a href="article.php?article=<?php echo $result->id; ?>" class="pull-left" >
                                <?php echo $result->title; ?></a><br/>
                                
                        <?php } ?>
                        <?php  } else { ?>
                        <h1>Oh No! We didnt find anything! :(</h1>
                        <?php  } ?>
                    </ul>

                    <?php include('./partials/_footer.php'); ?>


