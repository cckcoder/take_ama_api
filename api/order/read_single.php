<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/OrderModel.php';

    // Instantiate DB Connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog category object
    $order = new OrderModel($db);

    $order->userType = isset($_GET['userType']) ? $_GET['userType']: die();
    $order->userId = isset($_GET['userId']) ? $_GET['userId']: die();

    $order->read_single();


    if ($order->id)
    {
        // Order Array
        $order = array(
            'id' => $order->id,
            'date' => $order->date,
            'careTaker' => $order->careTaker,
            'hours' => $order->hours,
            'price' => $order->price,
            'amaName' => $order->amaName,
            'amaLat' => $order->amaLat,
            'amaLong' => $order->amaLong,
            'orderStatus' => $order->orderStatus
        );

        echo json_encode($order);
    }
    else
    {
        echo json_encode(
            array('message' => 'No order Found')
        );
    }