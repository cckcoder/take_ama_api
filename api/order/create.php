<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/OrderModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $order = new OrderModel($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $order->careTakerId = $data->careTakerId;
    $order->hours = $data->hours;
    $order->price = $data->price;
    $order->amaId = $data->amaId;
    $order->userType = 1;
    $order->userId = $data->amaId;
    $order->amaLat = $data->amaLat;
    $order->amaLong = $data->amaLong;

    if ($order->create()) 
    {
        $order->read_single();

        $order_arr = array(
            'id' => $order->id,
            'date' => $order->date,
            'careTaker' => $order->careTaker ,
            'hours' => $order->hours,
            'price' => $order->price,
            'amaName' => $order->amaName,
            'orderStatus' => $order->orderStatus,
        );

        echo json_encode(
            array(
                'message' => 'User Create',
                'data' => $order_arr
            )
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'User Not Create')
        );
    }
