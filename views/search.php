<!-- <?php echo $test; ?> -->
<?php include('./partials/_header.php'); ?>
<div class="jumbotron text-center">
    <h1 >Welcome to the blog</h1>
    <p >Howdy there! You look like someone who could write a thing or two about your fav comics!</p>
    <a href="register.html" id="hero-btn" class="btn btn-success">Come join us!</a>
</div>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <h1 class="pull-left">Your search results are served..</h1>
                    <div class="clearfix"></div>
                    <hr>
                    <ul id="articles">
                        <?php foreach($searchResults as $result) {?>
                            <a href="article.php?article=<?php echo $result->id; ?>" class="pull-left" >
                                <?php echo $result->title; ?></a><br/>
                        <?php } ?>
                    </ul>

                    <?php include('./partials/_footer.php'); ?>


