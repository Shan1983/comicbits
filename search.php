<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
$articles = new Article();


// use the template..
$template = new Template('views/search.php');


if (isset($_GET['q'])) {
    //redirect('search.php', $_GET[q], 'error');
    $template->searchResults = search(urlFormat($_GET['q']));
}

if ($_GET['q'] == null) {
    redirect('index.php', 'Your search was empty!  -_-', 'error');
}


// to display the template..
echo $template;
