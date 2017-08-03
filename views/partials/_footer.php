</div>
</div>
</div>
<!-- SIDEBAR -->
<div class="col-md-4">
    <div id="sidebar">
        <div class="block">
            <?php
            if(isset($_SESSION['is_logged_in']) == false) {
            ?>
            <h4>You need to have an account to see this!</h4>
            <a href="register.php" class="btn-block btn btn-primary">Register</a>
            <a href="login.php" class="btn-block btn btn-default">Login</a>
            <?php }else { ?>
                <img class="pull-right" src="images/avatars/<?php echo $_SESSION['avatar']; ?>" alt="users avatar" height="50" width="50">
                <h4>Howdy, <strong><?php echo $_SESSION['username']; ?></strong><hr> </h4>
                <div class="clearfix"></div>
                <small>Member since: <?php echo formatDate($_SESSION['created_at']); ?></small><br><br>
                <form action="logout.php" method="post">
                    <a href="create.php" class="btn btn-primary">Write a new article!</a>
                    <input name='logout' type='submit' class="btn btn-default pull-right" value="Logout">
                </form>
                <div class="clearfix"></div>
            <?php } ?>
        </div>
        <div class="block">
            <h3 class="text-center">Tags</h3>
            <div class="list-group">
                <a href="index.php" class="list-group-item <?php echo is_active(null); ?>">All Articles</a>
                <?php
                foreach(getTags() as $tag) : ?>
                <a href="articles.php?tag=<?php echo $tag->id; ?>" class="list-group-item <?php echo is_active($tag->id); ?>"><?php echo $tag->name; ?></a>
               <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
</div>
</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>