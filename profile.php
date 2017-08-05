<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
$articles = new Article();


// use the template..
$template = new Template('views/userprofile.php');

if (!empty($_GET['user'])) {
    // set up for ajax or anything else that goes on the users profile..
}else{
    redirect('index.php', 'Something went wrong :(', 'error');
}




// to display the template..
echo $template;
