<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');


// use the template..
$template = new Template('views/login.php');


if(isset($_POST['lets_login'])){
    // get the users data from the submitted form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // instantiate a new user
    $user = new User();

    // check if the user exists, then log em in
    if($user->login($email, $password)) {
        redirect('index.php', 'Welcome Back, ' . $_SESSION['username'] . '!', 'success');
    }else {
        redirect('login.php', 'Login failed! Are you sure you entered your details correctly?', 'error');
    }
}


// to display the template..
echo $template;
