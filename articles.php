<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
// instantiate the article class..
$articles = new Article();
$user = new User();

$uid = $_GET['user'];

// get tag from URL
$urlTag = isset($_GET['tag']) ? $_GET['tag'] : null;

// get user from URL
$urlUser = isset($_GET['user']) ? $_GET['user'] : null;

// use the template..
$template = new Template('views/articles.php');

// to send vars to template file..
//$template->test = 'This is a test'; // check indexpage for next bit..

// gets all the articles with a certain tag
// TODO add redirection if user/tags is not found..
if (isset($urlTag)) {
    $template->tags = $articles->getByTag($urlTag);
    $template->title = 'All articles with the tag: "' . $articles->getTag($urlTag)->name . '"';
    $template->link = $articles->getTagArticleLink($urlTag);
}

// gets all the articles from a certain user
if (isset($urlUser)) {
    $template->authors = $articles->getByAuthor($urlUser);
    $template->title = 'All articles from the author: "' . $articles->getAuthor($urlUser)->username . '"';
}


// send the queried results to the indexpage view
    $template->articles = $articles->getAllArticles();
    $template->articleCount = $articles->articleCount();
    $template->usersCount = $articles->usersCount();
    // gets the user profile data
    $template->userMember = $user->fetchUserData($uid);

// to display the template..
echo $template;