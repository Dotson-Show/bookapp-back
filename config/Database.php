<?php

class Database {
    //DB Params
    private $host = 'localhost';
    private $dbName = 'books_db';
    private $username = 'rootuser';
    private $password = 'rejoyce4me';
    private $conn;

    //DB Connect
    public function connect() {
        $this->conn  = null;
        $dsn = 'mysql:host=' . $this->host . ';dbname=' .$this->dbName;

        try {
             $this->conn = new PDO($dsn, $this->username, $this->password);
             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error:' . $e->getMessage();
        }

        return $this->conn;
    }
}