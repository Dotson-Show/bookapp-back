<?php

class Book {
    //DB Workings
    private $conn;
    private $table = 'books';

    //Book Properties
    public $name;
    public $isbn;
    public $authors;
    public $numOfPages;
    public $comments;
    public $characters;
    public $yearPublished;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get books
    public function read() {
        $sql = 'SELECT * FROM ' . $this->table. ' ORDER BY yearPublished';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    //Get a single book
    public function readSingle($book_id) {
        $sql = 'SELECT * FROM ' . $this->table. ' WHERE id = ?';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $book_id);
        $stmt->execute();
        return $stmt;
    }

}