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

    $result = $order->read();

    $num = $result->rowCount();

    if ($num > 0)
    {
        // Order Array
        $order_arr = array();
        $order_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            $order = array(
                'id' => $id,
                'date' => $date,
                'careTaker' => $careTaker,
                'hours' => $hours,
                'price' => $price,
                'amaName' => $amaName,
                'orderStatus' => $orderStatus
            );

            array_push($order_arr['data'], $order);
        }

        // Return order output with JSON
        echo json_encode($order_arr);
    }
    else
    {
        echo json_encode(
            array('message' => 'No order Found')
        );
    }