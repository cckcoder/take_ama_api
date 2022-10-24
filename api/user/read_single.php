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

    $user->id = isset($_GET['id']) ? $_GET['id']: die();

    // User query
    $user->read_single();

    // Check if any user by ID
    if ($user->id)
    {
        // User Array
        $user_arr = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'userType' => $user->userType,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'lastName' => $user->detail,
            'birthDay' => $user->birthDay,
            'isActive' => $user->isActive
        );
        // Turn to JSON & output
        echo json_encode($user_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No User Found')
        );
    }
