<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
// instantiate the article class..
$articles = new Article();

// use the template..
$template = new Template('views/indexpage.php');

// to send vars to template file..
//$template->test = 'This is a test'; // check indexpage for next bit..

// send the queried results to the indexpage view
$template->articles = $articles->getAllArticles();
$template->articleCount = $articles->articleCount();
$template->usersCount = $articles->usersCount();



// to display the template..
echo $template;