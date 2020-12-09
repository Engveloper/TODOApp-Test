<?php

include_once('Database.php');

class Task{
    private $id;
    private $text;
    private $completed;

    public function __construct($id, $text, $completed){
        $this->id = $id;
        $this->text = $text;
        $this->completed = $completed;
    }

    /* ----- GETTERS ----- */
    public function getId(){
        return $this->id;
    }

    public function getText(){
        return $this->text;
    }

    public function getCompleted(){
        return $this->completed;
    }
    
    /*SETTERS */
    public function setText($text){
        $this->text = $text;
    }

    public function setCompleted($completed){
        $this->completed = $completed;
    }

    public static function getAll(){
        $db = new Database();
        $connection = $db->connect();
        $sql = 'SELECT * FROM task';

        $query = $connection->prepare($sql);
        $query->execute();

        $tasks = array();

        while($task = $query->fetch(PDO::FETCH_ASSOC)){
            array_push($tasks, new Task($task['id'], $task['text'], $task['completed']));
        }

        return $tasks;
    
    }

    public static function getById($id){
        $db = new Database();
        $connection = $db->connect();
        $sql = 'SELECT * FROM task WHERE id = :id';

        $query = $connection->prepare($sql);
        $query->execute([
            'id' => $id
        ]);

        $task = $query->fetch(PDO::FETCH_ASSOC);
        return new Task($task['id'], $task['text'], $task['completed']);
    }

    public static function insert($text, $completed){
        $db = new Database();
        $connection = $db->connect();
        $sql = 'INSERT INTO task(text, completed) values(:text, :completed)';

        $query = $connection->prepare($sql);
        $result = $query->execute([
            'text' => $text,
            'completed' => $completed
        ]);

        return new Task($connection->lastInsertId(), $text, $completed);
    }

    public function save(){
        $db = new Database();
        $connection = $db->connect();
        $sql = 'UPDATE task set text=:text, completed=:completed WHERE id = :id';

        $query = $connection->prepare($sql);

        return $query->execute([
            'text' => $this->text,
            'completed' => $this->completed,
            'id' => $this->id,
        ]);
    }
    
    public function delete(){
        $db = new Database();
        $connection = $db->connect();
        $sql = 'DELETE FROM task WHERE id = :id';

        $query = $connection->prepare($sql);

        return $query->execute([
            'id' => $this->id,
        ]);
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'text' => $this->text,
            'completed' => $this->completed,
        ];
    }
}