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
            <h1 class="icon"><i class="fa fa-lock " aria-hidden="true"></i><br><small>Account Required</small></h1>
            <a href="register.php" id="loginBtn" class="btn-block btn btn-primary">Register</a>
            <a href="login.php" id="RegisterBtn" class="btn-block btn btn-success">Login</a>
            <?php }else { ?>
                <img class="pull-right" src="images/avatars/<?php echo $_SESSION['avatar']; ?>" alt="users avatar" height="50" width="50">
                <h4>Howdy, <strong><a href="articles.php?user=<?php echo $_SESSION['user_id'];?>"><?php echo $_SESSION['username']; ?></a></strong><hr> </h4>
                <div class="clearfix"></div>
                <small>Member since: <?php echo formatDate($_SESSION['created_at']); ?></small><br><br>
                <form action="logout.php" method="post">
                    <a href="create.php" id="mainBtn" class="btn btn-primary">Write a new article!</a>
                    <input name='logout'  type='submit' class="btn btn-default form-control" value="Logout">
                </form>
                <div class="clearfix"></div>
            <?php } ?>
        </div>
        <div class="tag-block">
            <!--<h3 class="text-center">Tags</h3>-->
            <div class="tag-header">
                    <h2 class="pull-left text-center">Tags</h2>
            </div>
            <div class="list-group">
                <a href="index.php" class="list-group-item <?php echo is_active(null); ?>">All Articles</a>
                <?php
                foreach(getTags() as $tag) : ?>
                <a href="tags.php?tag=<?php echo $tag->id; ?>" class="list-group-item <?php echo is_active($tag->id); ?>"><?php echo $tag->name; ?></a>
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