<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');
$articles = new Article();

$users = new User();

// use the template..
$template = new Template('views/edit.php');



// ok since there is like only 3 things to validate im just gonna do them here..
if(isset($_POST['lets_edit'])) {
    $article_id = $_GET['article'];
    $data = [];
    $data['title'] = $_POST['title'];
    $data['body'] = $_POST['body'];
    $data['tag'] = $_POST['tag'];
    $data['user_id'] = $_SESSION['user_id'];

    $required_fields = ['title', 'body', 'tag'];

    if(empty($data['title'])){
        redirect('edit.php', 'You forgot to give your article a title!', 'warning');
    }else if ($data['tag'] == 'none') {
        redirect('edit.php', 'You forgot to give your article a tag!', 'warning');
    }else if(empty($data['body'])) {
        redirect('edit.php', 'You forgot to give your article.. well an article..!', 'warning');
    }else {
        if($articles->edit($data, $article_id)){
            redirect('index.php', 'Awesome! you edited your article. Soon you\'ll be even more famous!', 'success');
        }
    }
}

// to display the template..
echo $template;