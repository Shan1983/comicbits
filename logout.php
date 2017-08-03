<?php

// well you guessed it this logs the user out..

include ("core/init.php");

if(isset($_POST['logout'])) {
    $user = new User();

    if($user->logout()) {
        redirect('index.php', 'You are now logged out, come back soon!', 'alert');
    }else {
        redirect('index.php');
    }
}