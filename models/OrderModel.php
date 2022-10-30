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
        
        public $userType;
        public $userId;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function read()
        {
            $query = "SELECT
                ot.id,
                ot.`date`,
                p2.firstName as 'careTaker',
                ot.hours,
                ot.price,
                p.firstName as 'amaName',
                o.description as 'orderStatus'
            FROM order_transection as ot
            LEFT JOIN profile as p
                ON p.user_id = ot.amaId
            LEFT JOIN profile as p2
                ON p2.user_id = ot.careTakerId
            LEFT JOIN order_type as o
                ON o.id = ot.status
            WHERE o.id IN (0, 1)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        }

        public function read_single()
        {
            if ($this->userType == 1)
            {
                $selection = "p.user_id";
            }
            else
            {
                $selection = "p2.user_id";
            }

            $this->order_by_id($selection);
        }

        public function order_by_id($selection)
        {
            $query = "SELECT
                    ot.id,
                    ot.`date`,
                    p2.firstName as 'careTaker',
                    ot.hours,
                    ot.price,
                    p.firstName as 'amaName',
                    o.description as 'orderStatus'
                FROM {$this->table} as ot
                LEFT JOIN profile AS p
                    ON p.user_id = ot.amaId
                LEFT JOIN profile AS p2
                    ON p2.user_id = ot.careTakerId
                LEFT JOIN order_type AS o
                    ON o.id = ot.status
                WHERE o.id IN (0, 1)
                    AND {$selection} = :userId";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userId', $this->userId);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row)
            {
                $this->id = $row['id'];
                $this->date = $row['date'];
                $this->careTaker = $row['careTaker'];
                $this->hours = $row['hours'];
                $this->price = $row['price'];
                $this->amaName = $row['amaName'];
                $this->orderStatus = $row['orderStatus'];
            }
            else
            {
                $this->id = null;
            }
        }
    }