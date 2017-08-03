<?php
// theres are just little funstions that help format presentation data like dates..

// help with those pesky URI's
function urlFormat($str) {
    // strip all the evil white spaces
    $str = preg_replace('/\s*/', '', $str);
    // lowercase the string
    $str = strtolower($str);
    // encode the url to help fight the bad guys.. and maybe you..
    $str = urlencode($str);
    return $str;
}

// helps with those pesky dates
function formatDate($date) {
    $date = date('j F, Y, g:i a', strtotime($date));
    return $date;
}

// calculates that pesky read time
function calcReadTime($str) {
    $words = str_word_count(strip_tags($str));
    // the blogging site medium uses 275 words per min to calculate,
    // minus 12 seconds for images.. this doesn't have images mid article soo..
    // i removed the 75 and made it 200 words per min..
    $minutes = floor($words / 200);
    $est = $minutes . ' min read time.';
    if ($est <= 0){
        return 'Less than a minute to read.';
    }
    return $est;
}

// checks if tag is active or selected..
function is_active($tag) {
    if(isset($_GET['tag'])){
        if($_GET['tag'] === $tag) {
            return 'active';
        }else {
            return '';
        }
    }else {
        if($tag === null) {
            return 'active';
        }
    }
}

// grabs the first 25 words - to be used on the index page
function articleSample($str) {
    $words = explode(' ', $str);
    $first_25_words = array_slice($words, 0, 25);
    return implode(' ', $first_25_words) . '...';
}

