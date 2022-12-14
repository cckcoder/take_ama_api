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

        public function create()
        {
            $query = "INSERT INTO {$this->table}
                SET
                    title = :title,
                    description = :description";
            
            $stmt = $this->conn->prepare($query);

            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->title = htmlspecialchars(strip_tags($this->description));

            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);

            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function update()
        {
            $query = "UPDATE {$this->table}
                SET
                    title = :title,
                    description = :description
                WHERE
                    id = :id";

            $stmt = $this->conn->prepare($query);


            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));

            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);

            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                print_r("Error: {$stmt->error}");
                return false;
            }
        }

        public function delete()
        {
            $query = "DELETE FROM {$this->table} WHERE id = :id";
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            // Bind data
            $stmt->bindParam(':id', $this->id);
            // Execute query
            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                print_r("Error: {$stmt->error}");
                return false;
            }

        }
    }