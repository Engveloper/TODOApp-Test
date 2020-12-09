<?php

class Database{
    private $host;
    private $database;
    private $username;
    private $password;
    private $charset;

    public function __construct(){
        $this->host = 'localhost';
        $this->database = 'todoapp';
        $this->username = 'engels';
        $this->password = 'engveloper';
        $this->charset = 'utf8mb4';
    }

    public function connect(){
        try{
            $connection = "mysql:host=".$this->host.";dbname=".$this->database.";charset=".$this->charset;
            $pdo = new PDO($connection, $this->username, $this->password);
            return $pdo;
        }
        catch(PDOException $ex){
            print_r('Error: ' . $ex->getMessage());
        }
    }
}