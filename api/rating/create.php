<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/RatingModel.php';

    $database = new Database();
    $db = $database->connect();

    $rating = new RatingModel($db);

    $data = json_decode(file_get_contents("php://input"));

    $rating->star = $data->star;
    $rating->user_id = $data->user_id;

    if ($rating->add_rating())
    {
        echo json_encode(
            array(
                'message' => 'Rate successful',
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'message' => 'Rate false',
            )
        );
    }