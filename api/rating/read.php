<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/RatingModel.php';

    $database = new Database();
    $db = $database->connect();

    $rating = new RatingModel($db);

    $star = $rating->avg_rating();

    echo json_encode(
        array('total_star' => $star)
    );