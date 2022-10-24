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

    // Blog post query
    $result = $user->read();
    // Get row count
    $num = $result->rowCount();

    // Check if any posts
    if ($num > 0)
    {
        // Post Array
        $user_arr = array();
        $user_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) 
        {
            extract($row);
            $user = array(
                'id' => $id,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'userType' => $userType,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'detail' => $detail,
                'birthDay' => $birthDay,
                'isActive' => $isActive,
            );

            // Category to data
            array_push($user_arr['data'], $user);
        }

        // Turn to JSON & output
        echo json_encode($user_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }
