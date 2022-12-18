<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/NewsModel.php';

    $database = new Database();
    $db = $database->connect();

    $news = new NewsModel($db);
    $data = json_decode(file_get_contents("php://input"));

    $news->id = $data->id;

    if ($news->delete())
    {
        echo json_encode(
            array(
                'message' => "News ID: {$data->id} Delete",
                'status' => 'success'
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'message' => "News ID: {$data->id} Delete",
                'status' => 'error'
            )
        );
    }