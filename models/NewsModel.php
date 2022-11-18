<?php
    class NewsModel
    {
        private $conn;
        private $table = 'news';

        public $id;
        public $title;
        public $description;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function read()
        {
            $query = "SELECT * FROM {$this->table} ORDER BY id ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        }

        public function read_by_id()
        {
            $query = "SELECT * FROM {$this->table} WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row)
            {
                $this->title = $row['title'];
                $this->description = $row['description'];
            }
        }
    }