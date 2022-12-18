<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/UserModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog category object
    $user = new UserModel($db);

    // User query
    $user_count = $user->user_count();

    echo json_encode(
        array(
            'user_count' => $user_count['user_count']
        )
    );