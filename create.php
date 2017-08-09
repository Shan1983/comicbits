<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');

$articles = new Article();

$users = new User();
if(isset($_POST['article-image'])) {
if ($articles->uploadArticleImage()){
        $data['image'] = $_FILES['article-image']['name']; 
    }else {
        $data['image'] = 'default.png';
    }
}

// use the template..
$template = new Template('views/create.php');

// ok since there is like only 3 things to validate im just gonna do them here..
if(isset($_POST['lets_create'])) {
    $data = [];
    $data['title'] = $_POST['title'];
    $data['body'] = $_POST['body'];
    $data['tag'] = $_POST['tag'];
    
    $data['user_id'] = $_SESSION['user_id'];

    $required_fields = ['title', 'body', 'tag'];

    if(empty($data['title'])){
        redirect('create.php', 'You forgot to give your article a title!', 'warning');
    }else if ($data['tag'] == 'none') {
        redirect('create.php', 'You forgot to give your article a tag!', 'warning');
    }else if(empty($data['body'])) {
        redirect('create.php', 'You forgot to give your article.. well an article..!', 'warning');
    }else {
        if($articles->create($data)){
            redirect('index.php', 'Awesome! you published your article. Soon you\'ll be famous!', 'success');
        }else {
            redirect('create.php', 'OH NO! Something went really wrong! Super sorry, but you\'ll need to start the article again from scratch!', 'error');
        }
    }
}


// to display the template..
echo $template;