<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header(
        'Access-Control-Allow-Headers: Access-Control-Allow-Headers,
        Content-Type,
        Access-Control-Allow-Methods,
        Authorization,
        X-Requested-With'
    );

    include_once '../../config/Database.php';
    include_once '../../models/UserModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $user = new UserModel($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->username = $data->username;
    $user->password = password_hash($data->password, PASSWORD_BCRYPT);
    $user->email = $data->email;
    $user->userType = $data->userType;

    // Profile
    $user->firstName = $data->firstName;
    $user->lastName = $data->lastName;
    $user->detail = $data->detail;
    $user->birthDay = $data->birthDay;

    if ($user->create()) 
    {
        echo json_encode(
            array('message' => 'User Create')
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'User Not Create')
        );
    }