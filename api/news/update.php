<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/NewsModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    $news = new NewsModel($db);

    $data = json_decode(file_get_contents("php://input"));

    $news->id = $data->id;
    $news->title = $data->title;
    $news->description = $data->description;

    if ($news->update())
    {
        echo json_encode(
            array('message' => 'News Update')
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'News Not Update')
        );
    }