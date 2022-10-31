<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    $order->id = $data->orderId;
    $order->status = $data->status;

    if ($order->update_order())
    {
        echo json_encode(
            array('message' => 'Order Status Update')
        );
    }
    else
    {
        echo json_encode(
            array('message' => 'Order Not Update')
        );
    }