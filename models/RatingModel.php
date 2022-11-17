<?php
    class RatingModel
    {
        // DB config
        private $conn;
        private $table = 'rating';

        // User Properties
        public $start;
        public $user_id;

        public function __construct($db) 
        {
            $this->conn = $db;
        }

        public function add_rating()
        {
            // Create query
            $query = "INSERT INTO {$this->table}
                SET
                    star = :star,
                    user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':star', $this->star);
            $stmt->bindParam(':user_id', $this->user_id);

            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function avg_rating()
        {
            $query = "SELECT ROUND(AVG(star), 1) as star FROM rating";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row["star"];
        }
    }