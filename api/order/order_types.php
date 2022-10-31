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

    $result = $order->read_order();

    $num = $result->rowCount();

    if ($num > 0)
    {
        // Order Array
        $order_type_arr = array();
        $order_type_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $order_type = array(
                'status' => $status,
                'description' => $description
            );

            array_push($order_type_arr['data'], $order_type);
        }
        // Return order type output with JSON
        echo json_encode($order_type_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No Order Type Found')
        );
    }