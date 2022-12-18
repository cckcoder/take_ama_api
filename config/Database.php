<?php
    class Database {
        private $host = 'localhost';
        private $db_name = 'take_ama';
        private $username = 'take_ama';
        private $password = 'Tutor@123Y';
        private $conn;

        public function connect()
        {
            $this->conn = null;
            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                var_dump("Connect Error: {$e->getMessage()}");
                exit();
            }
            
            return $this->conn;
        }
    }
