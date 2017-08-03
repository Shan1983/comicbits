<?php include('./partials/_header.php'); ?>
<?php  displayMessage(); // display flash message?>
<div class="jumbotron text-center">
    <h1 >Your face looks familar..</h1>
    <p >Use the form below to login</p>
</div>
<div class="container">
    <div class="row">
        <!-- MAIN ARTICLE DISPLAY -->
        <div class="col-md-8 col-md-offset-2">
            <div class="main-col">
                <!-- TODO setup block for main curved background -->
                <div class="block">
                    <form role="form" method="post" action="login.php">
                        <div class="form-group">
                            <label>Email:</label>
                            <input name="email" type="email" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" placeholder="Enter Password">
                        </div>
                        <button name="lets_login" type="submit" class="btn btn-primary">Login</button>
                    </form>
                    <hr>
                    <p>If your like us sometimes you forgot stuff. If you have forgotten your Login details..</p>
                    <a href="iforgot.php">Click here!</a>
                </div>
            </div>
