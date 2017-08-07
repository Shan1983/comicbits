<?php include('./partials/_header.php'); ?>
<?php  displayMessage(); // display flash message?>
<div class="jumbotron text-center">
    <h1 >Don't be a stranger, join us!</h1>
    <p >Start writing about your fav comic hero's today!</p>
</div>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8 col-md-offset-2">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <div class="article-header">
                        <h3>Register</h3>
                    </div>
                    <h4 id="create" class="pull-left">Registering let's you do all kinds of awesome things, like authoring your own articles, liking and sharing, and leaving comments.</h4>
                    <div class="clearfix"></div>
                    <hr>
                    <form role="form" enctype="multipart/form-data" method="post" action="register.php">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter your Name">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Your Email Address">
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter a Username">
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" class="form-control" name="password2" placeholder="Enter Password Again">
                        </div>
                        <div class="form-group">
                            <label>Upload a Avatar <small>(Optional)</small></label>
                            <input type="file" name="avatar">
                            <p class="help-block"></p>
                        </div>

                        <button name="register" id="mainBtn" type="submit" class="btn btn-success pull-right" value="Register" >Register</button>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                    </form>
                  
                </div>
            </div>