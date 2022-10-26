<?php
    class OrderModel
    {
        private $conn;
        private $table = 'order_transection';

        // Order Properties
        public $id;
        public $date;
        public $careTakerId;
        public $hours;
        public $price;
        public $status;
        public $amaId;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function read()
        {
            $query = "SELECT 
                ot.id,
                ot.`date`,
                c.firstName as careTaker,
                ot.hours,
                ot.price,
                t.description as status,
                a.firstName as amaName
            FROM order_transection as ot
            LEFT JOIN profile as c
                ON ot.careTakerId = c.user_id
            LEFT JOIN profile as a 
                ON ot.amaId = a.user_id
            LEFT JOIN order_type as t 
                ON ot.status = t.description
            WHERE t.status in (0, 1)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;

        }
    }