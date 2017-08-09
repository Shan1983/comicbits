<?php

class Article {
    // init the database
    private $db;


    public function __construct()
    {
        $this->db = new Database();
    }

    // grab all the articles
    public function getAllArticles() {
        // get all articles with this query
        $this->db->query(
            'SELECT articles.*, users.username, tags.name
                   FROM articles
                   INNER JOIN users
                   ON articles.user_id = users.id
                   INNER JOIN tags
                   ON articles.tag_id = tags.id
                   ORDER BY created_at DESC
                   ');
        // get the result from the query
        $results = $this->db->resultSet();

        // finally return the set
        return $results;

    }
    
     // grab all the articles
    public function getAllArticlesAPI() {
        // get all articles with this query
        $this->db->query(
            'SELECT articles.*, users.username, tags.name
                   FROM articles
                   INNER JOIN users
                   ON articles.user_id = users.id
                   INNER JOIN tags
                   ON articles.tag_id = tags.id
                   ORDER BY created_at DESC
                   ');
        // get the result from the query
        $results = $this->db->resultSet();
        // finally return the set

        return $results;
        
    }

    // gets total articles for stats
    public function articleCount() {
        $this->db->query('SELECT articles.id FROM articles');
        $count = $this->db->resultSet();
        return count($count);
    }

    // gets total users for stats
    public function usersCount() {
        $this->db->query('SELECT SUM(id) FROM users');
        $count = $this->db->execute();
        return $count;
    }

    // gets all the articles that fall under a tag
    public function getTag($urlTag){
       $this->db->query("SELECT * FROM tags WHERE id = :urlTag");
       $this->db->bind(':urlTag', $urlTag);

       $result = $this->db->single();

       return $result;
    }

    // gets all the articles that fall under a author
    public function getAuthor($urlUser){
        $this->db->query("SELECT * FROM users WHERE id = :urlUser");
        $this->db->bind(':urlUser', $urlUser);

        $result = $this->db->single();

        return $result;
    }

    public function getByTag($urlTag) {
        $this->db->query('SELECT articles.*, tags.*, users.username
                                FROM articles
                                INNER JOIN tags
                                ON articles.tag_id = tags.id
                                INNER JOIN users
                                ON articles.user_id = users.id
                                WHERE articles.tag_id = :urlTag');
        $this->db->bind('urlTag', $urlTag);
        $results = $this->db->resultSet();

        return $results;
    }

    // gets all the articles that fall under a author
    public function getByAuthor($urlUser){
        $this->db->query('SELECT articles.*, articles.id AS aid, tags.*, users.username
                                FROM articles
                                INNER JOIN tags
                                ON articles.tag_id = tags.id
                                INNER JOIN users
                                ON articles.user_id = users.id
                                WHERE articles.user_id = :urlUser');
        $this->db->bind('urlUser', $urlUser);
        $results = $this->db->resultSet();

        return $results;
    }

    public function getTagArticleLink($id) {
        $this->db->query('SELECT articles.* FROM articles WHERE tag_id = :id');
        $this->db->bind('id', $id);
        $results = $this->db->resultSet();

        return $results;
    }


    // gets the article for the show article page
    public function getArticle($articleId) {
        $this->db->query('SELECT articles.*, tags.*
                                FROM articles
                                INNER JOIN tags
                                ON articles.tag_id = tags.id
                                WHERE articles.id = :id');
        $this->db->bind(':id', $articleId);

        $result = $this->db->single();

        return $result;
    }

    // get the comments for the selected article
    public function getComments($articleId) {
        $this->db->query('SELECT comments.*, users.*
                                FROM comments
                                INNER JOIN users
                                ON comments.user_id = users.id
                                WHERE comments.article_id = :articleId
                                ORDER BY comments.created_at ASC');
        $this->db->bind('articleId', $articleId);
        $results = $this->db->resultSet();
        return $results;
    }

    public function create($data) {
        $this->db->query('INSERT INTO articles (tag_id, user_id, article_image, title, body, status)
                                VALUES (:tag_id, :user_id, :article_image, :title, :body, :status)');
        $this->db->bind(':tag_id', $data['tag']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':article_image', $data['image']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':status', 0);
        

        if($this->db->execute()) {
            return true;
        }else {
            return false;
        }
    }

    public function leaveComment($data, $articleId) {
        $this->db->query('INSERT INTO comments (article_id, user_id, body, status)
                                VALUES (:article_id, :user_id, :body, :status)');
        $this->db->bind(':article_id', $articleId);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':body', $data['comment']);
        $this->db->bind(':status', 0);

        if($this->db->execute()) {
            return true;
        }else {
            return false;
        }
    }

    // edit an article
    public function edit($data, $id) {
        $this->db->query('UPDATE articles 
                                SET tag_id = :tag_id, user_id = :user_id, title = :title, body = :body
                                WHERE id = :id');
        $this->db->bind(':tag_id', $data['tag']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':id', $id);

        if($this->db->execute()) {
            return true;
        }else {
            return false;
        }
    }
    
    // make an article featured
    public function makeArticleFeatured($id) {
        $this->db->query("UPDATE articles SET isFeatured = '1' WHERE id = :id");
        $this->db->bind(':id', $id);
        if ($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }
    
    // remove an featured article 
    public function removeArticleFeatured($id) {
        $this->db->query("UPDATE articles SET isFeatured = '0' WHERE id = :id");
        $this->db->bind(':id', $id);
        if ($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }
    
    
    // this uploads an image for articles
            public function uploadArticleImage()
        {

            if (!empty($_FILES['article-image']['name'])) {


                // array containg the extensions of popular image files
                $allowedExtensions = array("gif", "jpeg", "jpg", "png");

                //echo $_FILES['article-image']['name'];


                // make it so i can get the .extension
                $tmp = explode(".", $_FILES['article-image']['name']);


                // get the end part of the file
                $newfilename = hash('sha256', reset($tmp) . time());

                $extension = end($tmp);

                $_FILES['article-image']['name'] = $newfilename . "." . $extension;
                //echo $_FILES['avatar']['name'];
                
    
             
                // ok here we go.. maybe a switch might be nice?.. meh..
                if ((($_FILES['article-image']['type'] == "image/gif")
                        || ($_FILES['article-image']['type'] == "image/jpeg")
                        || ($_FILES['article-image']['type'] == "image/jpg")
                        || ($_FILES['article-image']['type'] == "image/png"))
                    && ($_FILES['article-image']['size'] < 2000000)
                    && in_array($extension, $allowedExtensions)
                ) {

                    // get the error
                    if ($_FILES['article-image']['error'] > 0) {
                        redirect('create.php', $_FILES['article-image']['error'], 'error');
                    } else {
                         
                        // and finally move the file into the folder
                        move_uploaded_file($_FILES['article-image']['tmp_name'], "images/article_images/" . $_FILES['article-image']['name']);
                        //redirect('index.php', 'Amazing! your all signed up', 'success');
                        $_SESSION['article-image'] = $_FILES['article-image']['name'];
                        return true;
                    }

                } else {
                    // stop and warn the user that the file type is not suppoerted
                    redirect('create.php', 'Invalid File Type! We only accept "gif", "jpeg", "jpg", or "png"', 'error');
                }
            } else {
                //move_uploaded_file($_FILES['avatar']['tmp_name'], SERVER_URI . "images/avatars/" . $_FILES['avatar']['name']);
                return false;
            }

        }

}