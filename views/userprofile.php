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
                    <h1 class="pull-left">G'day <?php echo $userMember->username; ?></h1>
                    <?php if(userType($userMember->id) != 'Admin') { ?>
                    <form method="post" action="profile.php?user=<?php echo $userMember->id ?>">
                       <button name="do_make_admin" type="submit"  class="btn btn-success pull-right" value="<?php echo $userMember->id;?>">Make user an Administrator!</button>
                     </form>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <hr>
                    <h1>Ajax Image uploader coming soon..</h1>

                    <?php include('./partials/_footer.php'); ?>


