<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
$articles = new Article();
$user = new User();

$uid = $_GET['user'];

// use the template..
$template = new Template('views/userprofile.php');
$template->userMember = $user->fetchUserData($uid);

if (isset($_POST['do_make_admin'])) {

    $user->makeAdmin($uid);
    redirect('index.php', 'Sweet! New admin has been crowned!', 'success');
}




// to display the template..
echo $template;
