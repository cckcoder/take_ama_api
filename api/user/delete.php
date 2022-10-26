<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


    include_once '../../config/Database.php';
    include_once '../../models/UserModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $user = new UserModel($db);

    // Get raw user data from client.
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $user->id = $data->user_id;

    if ($user->delete())
    {
        echo json_encode(
            array(
                'message' => "User ID: {$data->user_id} Delete",
                'status' => 'success'
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'message' => "User ID: {$data->user_id} not Delete",
                'status' => 'error'
            )
        );
    }