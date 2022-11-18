<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/NewsModel.php';

    $database = new Database();
    $db = $database->connect();

    $news = new NewsModel($db);

    $news->id = isset($_GET['id']) ? $_GET['id']: die();

    $news->read_by_id();

    if ($news->title)
    {
        $news_arr = array(
            'id' => $news->id,
            'title' => $news->title,
            'description' => $news->description
        );

        echo json_encode($news_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No news Found')
        );
    }
