<?php

include_once 'Task.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':{
        $result['Tasks'] = [];
        $tasks = Task::getAll();
        foreach ($tasks as $task) {
            array_push($result['Tasks'], 
                [
                    'id' => $task->getId(),
                    'text' => $task->getText(),
                    'completed' => $task->getCompleted(),
                ]
            );
        }

        echo json_encode($result);
        break;
    }

    case 'PUT':{
        $_PUT = json_decode(file_get_contents('php://input'), true);
        //parse_str(file_get_contents('php://input'), $_PUT);
        
        $isRequestValid = isset($_PUT['id']) && isset($_PUT['text']) && isset($_PUT['completed']);

        //var_dump($_PUT);
        
        if($isRequestValid){

            $id = $_PUT['id'];
            $text = $_PUT['text'];
            $completed = $_PUT['completed'];
            
            $task = Task::getById($id);

            $task->setText($text);
            $task->setCompleted($completed);

            if($task->save()){
                echo json_encode([
                    'Message' => 'TASK UPDATED SUCCESSFULY'
                ]);
            }
            //var_dump($task->toArray());
        }
        else{
            echo json_encode(['Message' => 'BAD REQUEST']);
        }
        break;
    }
    
    case 'POST':{
        $_POST = json_decode(file_get_contents('php://input'), true);
        //parse_str(file_get_contents('php://input'), $_POST);
        
        $isRequestValid = isset($_POST['text']) && isset($_POST['completed']);

        //var_dump($_PUT);
        
        if($isRequestValid){
            
            $text = $_POST['text'];
            $completed = $_POST['completed'];
            
            $task = Task::insert($text, $completed);

            if($task){
                echo json_encode([
                    'Message' => 'TASK CREATED SUCCESSFULY',
                    'Task' => $task->toArray(),
                ]);
            }
            else{
                echo json_encode([
                    'Message' => 'TASK CANNOT CREATED'
                ]);
            }
            //var_dump($task->toArray());
        }
        else{
            echo json_encode(['Message' => 'BAD REQUEST']);
        }
        break;
    }

    case 'DELETE':{
        $_DELETE = json_decode(file_get_contents('php://input'), true);
        //parse_str(file_get_contents('php://input'), $_DELETE);
        
        $isRequestValid = isset($_DELETE['id']);

        //var_dump($_PUT);
        
        if($isRequestValid){
            
            $id = $_DELETE['id'];
            $task = Task::getById($id);

            if($task){
                if($task->delete()){
                    echo json_encode([
                        'Message' => 'TASK DELETED SUCCESSFULY'
                    ]);    
                }
                else{
                    echo json_encode([
                        'Message' => 'TASK CANNOT DELETED'
                    ]);    
                }
            }else{
                echo json_encode([
                    'Message' => 'TASK NOT FOUND'
                ]);
            }
        }
        else{
            echo json_encode(['Message' => 'BAD REQUEST']);
        }
        break;
    }
}
