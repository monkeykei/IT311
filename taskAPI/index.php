<?php
include"../taskAPI/config/config";

header(header: "Content-Type: application/json");


$requestMethod = $_SERVER["REQUEST_METHOD"];

$request = isset($_GET['request']) ? explode("/", trim($_GET['request'], "/")) : [];

$requestMethod; 

$task_id = isset ($request[1]) ? $request[1] : null;

 switch($requestMethod){
    case 'POST';
    createTask();
    break;

    case 'GET';
        if($task_id){
            getTask($task_id);
        }else{
            getTasks();
        }    
    break;


    default:
    http_response_code(405);
    echo json_encode(["message" => "Method not Existing"]);
    break;
 }

 mysqli_close($connection);
?>


<?php

function createTask() {
    global $connection;

    $data = json_decode(file_get_contents("php://input"), true);

    $title = $data['title'];
    $description = $data['description'];

    if (!empty($title)) {
        $sql = "INSERT INTO task (title, description) VALUES ('$title', '$description')";
        
        if (mysqli_query($connection, $sql)) {
            http_response_code(201);
            echo json_encode(["message" => "Task created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Task creation failed"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Title is required"]);
    }
}

function getTasks(){
    global $connection;
    
    $sql = "SELECT * FROM task";
    $result = mysqli_query($connection, $sql);

    $task = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($task);
}

function getTask($id){
    global $connection;

    $sql = "SELECT * FROM task WHERE id = '$id'";
    $result = mysqli_query($connection, $sql);

    if ($row = mysqli_fetch_assoc($result)){
        echo json_encode($row);
    }else{
        echo json_encode(["message" => "Waley task bro"]);
    }
}
?>

