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
    public function readComments() {
        $sql = 'SELECT * FROM ' . $this->table . 
            'WWHERE book_id = ? ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind(1, $this->book_id);
        $stmt->execute();
        return $stmt;
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
        $this->commenter_pub_ip = $_SERVER['REMOTE_ADDR'];

        //Bind to query
        $stmt->bind('1', $this->book_id);
        $stmt->bind('2', $this->title);
        $stmt->bind('3', $this->content);
        $stmt->bind('4', $this->commenter_pub_ip);

        if ($stmt->execute()) {
            return true;
        }

        //Print Error if any exist
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}