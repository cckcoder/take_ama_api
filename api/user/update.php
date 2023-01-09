<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/UserModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $user = new UserModel($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set User ID
    $user->id = $data->user_id;
    // Set User Details
    $user->firstName = $data->firstName;
    $user->lastName = $data->lastName;
    $user->detail = $data->detail;
    $user->birthDay = $data->birthDay;
    $user->taxId = $data->taxId;

    if ($user->update_profile())
    {
        echo json_encode(
            array('message' => 'Profile Update')
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'Profile Not Update')
        );
    }