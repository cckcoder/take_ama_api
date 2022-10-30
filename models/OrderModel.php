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
                ON o.status = ot.status
            WHERE o.status IN (0, 1)";

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
                    ON o.status = ot.status
                WHERE o.status IN (0, 1)
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

        public function create()
        {
            $query = "INSERT INTO {$this->table}
                SET
                    careTakerId = :careTakerId ,
                    hours = :hours,
                    price = :price,
                    amaId = :amaId";

            $stmt = $this->conn->prepare($query);

            $this->careTakerId = htmlspecialchars(strip_tags($this->careTakerId));
            $this->hours = htmlspecialchars(strip_tags($this->hours));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->status = htmlspecialchars(strip_tags($this->status));
            $this->amaId = htmlspecialchars(strip_tags($this->amaId));

            $stmt->bindParam(':careTakerId', $this->careTakerId);
            $stmt->bindParam(':hours', $this->hours);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':amaId', $this->amaId);

            if ($stmt->execute())
            {
                return true;

            }
            else
            {
                print_r("Error Order: {$stmt->error}");
                return false;
            }

        }
    }