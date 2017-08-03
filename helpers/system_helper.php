<?php
// this are system wide functions that help with displaying information, such as flash messaging..

// this sets up flash messages, and redirect a user to which ever page you want to go to
function redirect($page = false, $message = null, $message_type = null)
{

    //get the page location address
    if (is_string($page)) {
        $location = $page;
    } else {
        $location = $_SERVER['SCRIPT_NAME'];
    }

    // check if the redirect has a message
    if ($message != null) {
        // put the message into a session
        $_SESSION['message'] = $message;
    }

    // check for the type of message from the redirect
    if ($message_type != null) {
        // do the same as the message and  put the type into a session
        $_SESSION['message_type'] = $message_type;
    }

    // and then.. use the real http redirect
    header('location: ' . $location);
    // and stop from going any further cause that will be buggy
    exit();

}

// display those generated messages, with flare and panache ;) i.e. flash message..
function displayMessage()
{
    if (!empty($_SESSION['message'])) {
        // get the message from the session
        $message = $_SESSION['message'];

        // check its type
        if (!empty($_SESSION['message_type'])) {
            $message_type = $_SESSION['message_type'];

            //check the redirect type
            if ($message_type == 'error') {
                echo '<div class="flash alert alert-danger text-center">' . $message . '</div>';
            } else if ($message_type == 'success') {
                echo '<div class="flash alert alert-success text-center">' . $message . '</div>';
            } else if ($message_type == 'warning') {
                echo '<div class="flash alert alert-warning text-center">' . $message . '</div>';
            } else if ($message_type == 'alert') {
                echo '<div class="flash alert alert-info text-center">' . $message . '</div>';
            }
        }

        // empty out the messages
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    } else {
        echo '';
    }

}

