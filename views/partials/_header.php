<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="icon" href="../../favicon.ico">

    <title><?php echo SITE_TITLE; ?> </title>

    <!-- Bootstrap core CSS -->
    <link href="views/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="views/css/new.css" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	
<link href="https://fonts.googleapis.com/css?family=Asul|Contrail+One|Kavoon|Lobster|Rubik:900" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    
    <!--this is for the comments only-->
    <?php if (basename($_SERVER['PHP_SELF']) == "article.php") { ?>
	<script type="text/javascript" src="views/js/comments.js"></script>
	<?php } ?>
	
	   <?php if (basename($_SERVER['PHP_SELF']) == "index.php") { ?>
	<script type="text/javascript" src="views/js/featuredArticle.js"></script>
	<?php } ?>

</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand logo" href="index.php"><img src="images/logo.png" alt="logo" width="50" height="50"> ComicBit</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if(!isset($_SESSION['is_logged_in'])) {
                ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <?php } ?>
            </ul>
            <div class="col-sm-3 col-md-6">
                <form  class="navbar-form" role="search" action="search.php?" method="get">
                    <div class="input-group">
                        <input id="search" name="q" type="text" class="form-control" placeholder="Find something amazing.." name="q">
                        <div class="input-group-btn">
                            <button  class="btn btn-default" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!--/.nav-collapse -->

    </div>
</nav>
