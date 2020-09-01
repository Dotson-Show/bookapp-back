<?php

class Database {
    // //DB Params local
    // private $host = 'localhost';
    // private $dbName = 'books_db';
    // private $username = 'rootuser';
    // private $password = 'rejoyce4me';
    // private $conn;

    //DB Params remote
    private $host = 'q3vtafztappqbpzn.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    private $dbName = 'fw9xkvptjika3qyv';
    private $username = 'rgtzd83yynt046c6';
    private $password = 'pbuoxs99o3tl5y01';
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