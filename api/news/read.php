<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/NewsModel.php';

    $database = new Database();
    $db = $database->connect();

    $news = new NewsModel($db);

    $result= $news->read();
    $num = $result->rowCount();

    if ($num > 0)
    {
        $new_arr = array();
        $new_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $new = array(
                'id'    => $id,
                'title' => $title,
                'description' => $description,
            );

            array_push($new_arr['data'], $new);
        }
        echo json_encode($new_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No News Found')
        );
    }