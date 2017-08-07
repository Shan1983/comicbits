<?php
// these a little bits of code that are just quick and nasty ways of accessing 
// stuff from our database without having to instantiate a complete class..

// get total comments for articles
function commentCount($article_id) {
    $db = new Database();
    $db->query("SELECT * FROM comment WHERE article_id = :article_id");
    $db->bind(':article_id', $article_id);
    $res = $db->resultSet();
    return count($res);
}

// gets all those pesky tags
function getTags() {
    $db = new Database();
    $db->query('SELECT * FROM tags');
    $results = $db->resultSet();
    return $results;
}

// get the user type
function userType($str) {
    $db = new Database();

    // get the users membership level
    $db->query("SELECT *
                FROM users
                WHERE id = :user_id ");

    $db->bind("user_id", $str);

    $results = $db->single();

    // return in english the users membership level
    if ($results->membership == "1") {
        return "Admin";
    } else if ($results->membership == "2") {
        return "Moderator";
    } else if ($results->membership == "3") {
        return "Member";
    } else if ($results->membership == "4") {
        return "Banned";
    }

}

function getUserById($id) {
    $db = new Database();
 
    $db->query("SELECT username FROM users WHERE id = :id");
    $db->bind(':id', $id);
    
    $res = $db->single();
    
    
    return $res->username;
}

// used for the search function to find all
// article titles like % the search query.
// its in here cause I got lazy..
function search($query) {
    $db = new Database();

    $db->query("SELECT id, title
                FROM articles
                WHERE title LIKE :query");

    $db->bind(':query', '%' . $query . '%');

    $results = $db->resultSet();
    
    if($results[0]->title) {
    return $results;
    }else {
    return false;
    }

    
}

// removes a comment.. kinda..
function removeComment($id) {
    $db = new Database();

    $db->query("UPDATE comments
                SET body = :closed
                WHERE article_id = :id");

    $db->bind(':closed', strtoupper("removed for inappropriate content"));
    $db->bind(':id', $id);


    if ($db->execute()) {
        return true;
    } else {
        return false;
    }
}

function removeArticle($id) {
    $db = new Database();
    $db->query("UPDATE articles
                SET status = :closed
                WHERE id = :id");

    $db->bind(':closed', 1);
    $db->bind(':id', $id);


    if ($db->execute()) {
        return true;
    } else {
        return false;
    }
}

// function GUID()
// 		{
// 			if (function_exists('com_create_guid') === true)
// 			{
// 				return trim(com_create_guid(), '{}');
// 			}

// 			return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
// 		}