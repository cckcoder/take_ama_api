<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    $user->username = $data->username;
    $user->password = $data->password;

    if ($user->login())
    {
        $user->read_single();
        $user_arr = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'userType' => $user->userType,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'detail' => $user->detail,
            'birthDay' => $user->birthDay,
            'isActive' => $user->isActive
        );
        echo json_encode(
            array(
                'message' => 'User login sucessful!',
                'data' => $user_arr
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'message' => 'Username or Password not corrct',
                'status' => 'error'
            )
        );
    }
