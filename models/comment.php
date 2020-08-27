<?php

class Comment {
    //DB Workings
    private $conn;
    private $table = 'comments';

    //Comment Properties
    public $book_id;
    public $title;
    public $content;
    public $commenter_pub_ip;
    public $created_at;

    //Create the db contructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Comments List
    public function readComments($book_id) {
        $sql = 'SELECT * FROM ' . $this->table. ' WHERE book_id = ? ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($sql);

        $this->book_id = $book_id;
        $stmt->bindParam(1, $this->book_id);
        $stmt->execute();
        return $stmt;
    }

    public function getCommentCount($book_id) {
        $commentResults = $this->readComments($book_id);
        return $commentResults->rowCount();
    }

    public function getComments($book_id) {
        $commentResults = $this->readComments($book_id);
        $commentRowCount = $commentResults->rowCount();

        if ($commentRowCount > 0) {
            
            $commentsArray = array();
        
            while ($row = $commentResults->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
        
                $comment_item = array(
                    'id' => $id,
                    'book_id' => $book_id,
                    'title' => $title,
                    'content' => $content,
                    'commenter_pub_ip' => $commenter_pub_ip,
                    'created_at' => $created_at
                );
        
                //Push to Data array
                array_push($commentsArray, $comment_item);
            }
        
            //Turn to json and output
            return $commentsArray;
        } else {
            //No Books
            return array('message' => 'No Comments Found');
        }
        
    }

    public function get_real_ip() {
        $ip = 'undefined';
        if (isset($_SERVER)) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            elseif (isset($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = getenv('REMOTE_ADDR');
            if (getenv('HTTP_X_FORWARDED_FOR')) $ip = getenv('HTTP_X_FORWARDED_FOR');
            elseif (getenv('HTTP_CLIENT_IP')) $ip = getenv('HTTP_CLIENT_IP');
        }
        $ip = htmlspecialchars($ip, ENT_QUOTES, 'UTF-8');
        return $ip;
    }


    //Create Comments
    public function createComment($book_id, $title, $content) {
        $sql = 'INSERT INTO ' .$this->table. ' 
            (book_id, title, content, commenter_pub_ip, created_at) 
            VALUES (?, ?, ?, ?, Now())';

        $stmt = $this->conn->prepare($sql);
        
        //Clean Up input data
        $this->book_id = htmlspecialchars(strip_tags($book_id));
        $this->title = htmlspecialchars(strip_tags($title));
        $this->content = htmlspecialchars(strip_tags($content));
        $this->commenter_pub_ip = $this->get_real_ip();

        //Bind to query
        $stmt->bindParam('1', $this->book_id);
        $stmt->bindParam('2', $this->title);
        $stmt->bindParam('3', $this->content);
        $stmt->bindParam('4', $this->commenter_pub_ip);

        if ($stmt->execute()) {
            return true;
        }

        //Print Error if any exist
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}