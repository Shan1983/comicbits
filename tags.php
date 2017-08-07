<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
// instantiate the article class..
$articles = new Article();


// get tag from URL
$urlTag = isset($_GET['tag']) ? $_GET['tag'] : null;


// use the template..
$template = new Template('views/tags.php');

// to send vars to template file..
//$template->test = 'This is a test'; // check indexpage for next bit..

// gets all the articles with a certain tag
// TODO add redirection if user/tags is not found..
if (isset($urlTag)) {
    $template->tags = $articles->getByTag($urlTag);
    $template->title = 'All articles with the tag: "' . $articles->getTag($urlTag)->name . '"';
    $template->link = $articles->getTagArticleLink($urlTag);
}



// send the queried results to the indexpage view
    $template->articles = $articles->getAllArticles();
    $template->articleCount = $articles->articleCount();
    $template->usersCount = $articles->usersCount();

// to display the template..
echo $template;