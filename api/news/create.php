<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/NewsModel.php';

    $database = new Database();
    $db = $database->connect();

    $news = new NewsModel($db);

    $data = json_decode(file_get_contents("php://input"));

    $news->title = $data->title;
    $news->description = $data->description;

    if ($news->create())
    {
        $news_arr = array(
            'title' => $news->title,
            'description' => $news->description,
        );

        echo json_encode(
            array(
                'message' => 'News Create',
                'data' => $news_arr
            )
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'News Not Create')
        );
    }