<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');

// instantiate the article class..
$articles = new Article();

// use the template..
$template = new Template('views/article.php');

// get the article id from the url
$articleId = isset($_GET['article']) ? $_GET['article'] : null;

// get the articles data
$template->article = $articles->getArticle($articleId);
$template->comments = $articles->getComments($articleId);
$template->title = $articles->getArticle($articleId)->title;
$template->id = isset($_GET['article']) ? $_GET['article'] : null;


// send the queried results to the indexpage view
$template->articles = $articles->getAllArticles();
$template->articleCount = $articles->articleCount();
$template->usersCount = $articles->usersCount();

if(isset($_POST['lets_comment'])){
    $article_id = $_GET['article'];

    $data = [];
    $data['comment'] = $_POST['comment'];
    $data['user_id'] = $_SESSION['user_id'];

    $required_fields = ['comment'];

    if(empty($data['comment'])){
        redirect("article.php?article=$article_id", 'You forgot to leave your comment!', 'error');
    }else {
        if($articles->leaveComment($data, $article_id)){
            redirect("article.php?article=$article_id", 'Awesome! Your comment was posted!', 'success');
        }else {
            redirect("article.php?article=$article_id", 'OH NO! Your comment got lost!', 'error');
        }
    }

}

if ($_POST['do_make_featured']) {
    $article_id = $_GET['article'];
    $articles->makeArticleFeatured($article_id);
    redirect('index.php', 'Awesome! The article is now featured!', 'success');
}

if ($_POST['do_remove_featured']) {
    $article_id = $_GET['article'];
    $articles->removeArticleFeatured($article_id);
    redirect('index.php', 'Awesome! The article is no longer featured!', 'success');
}

// if (isset($_POST['do_remove'])) {
//     if(removeComment($_POST['do_remove'])) {
//         redirect("article.php?article=".$_POST['do_remove'], "The comment has been removed", 'alert');

//     }else{
//         redirect("article.php?article=".$_POST['do_remove'], "Something bad happened.. remove it again!", 'error');

//     }

// }

// if (isset($_POST['do_remove_article'])) {
//     if(removeArticle($_POST['do_remove_article'])) {
//         redirect('index.php', "The article has been removed", 'alert');

//     }else{
//         redirect("article.php?article=".$_POST['do_remove_article'], "Something bad happened.. remove it again!", 'error');

//     }

// }



// to display the template..
echo $template;