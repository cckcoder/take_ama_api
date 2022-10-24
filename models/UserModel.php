<?php
    class UserModel 
    {
        // DB config
        private $conn;
        private $table = 'user';

        // User Properties
        public $id;
        public $username;
        public $password;
        public $email;
        public $userType;

        // Profile Properties
        public $firstName;
        public $lastName;
        public $birthDay;


        // Constructor with DB
        public function __construct($db) 
        {
            $this->conn = $db;
        }

        // Get Posts
        public function read()
        {
            $query = "SELECT 
                id,
                username,
                password,
                email,
                userType,
                createdAt
            FROM {$this->table}
            ORDER BY
                createdAt DESC" ;

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;

        }

        // Get Single Post
        public function read_single()
        {
           $query = "SELECT 
                    c.name as category_name,
                    p.id,
                    p.category_id,
                    p.title,
                    p.body,
                    p.author,
                    p.created_at
                FROM {$this->table} as p
                LEFT JOIN categories c
                    ON p.category_id = c.id
                WHERE 
                    p.id = ?"; 
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind ID
            $stmt->bindParam(1, $this->id);
            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Set Properties
            $this->title = $row['title'];
            $this->body = $row['body'];
            $this->author = $row['author'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
        }

        public function create()
        {
            // Create query
            $query = "INSERT INTO {$this->table}
                SET
                    username = :username,
                    password = :password,
                    email = :email,
                    userType = :userType";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = $this->password;
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->userType = htmlspecialchars(strip_tags($this->userType));

            // Bind data
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':userType', $this->userType);

            // Execute query
            if ($stmt->execute())
            {
                $user_id = $this->conn->lastInsertId();
                if ($this->profile($user_id));
                return true;
            }
            else
            {
                print_r("Error User: {$stmt->error}");
                return false;
            }
        }

        public function profile($user_id)
        {
            $query = "INSERT INTO profile
                SET
                    firstName = :firstName,
                    lastName = :lastName,
                    birthDay = :birthDay,
                    user_id = :user_id";

            // Prepare statement
            $stmt2 = $this->conn->prepare($query);
            // Clean Data
            $this->firstName = htmlspecialchars(strip_tags($this->firstName));
            $this->lastName = htmlspecialchars(strip_tags($this->lastName));
            $this->birthDay = htmlspecialchars(strip_tags($this->birthDay));
            // Bind data
            $stmt2->bindParam(':firstName', $this->firstName);
            $stmt2->bindParam(':lastName', $this->lastName);
            $stmt2->bindParam(':birthDay', $this->birthDay);
            $stmt2->bindParam(':user_id', $user_id);

            if ($stmt2->execute())
            {
                return true;
            }
            else
            {
                print_r("Error Profile: {$stmt2->error}");
                return false;
            }
        }

        public function update()
        {
            // Create query
            $query = "UPDATE {$this->table}
                SET
                    title = :title,
                    body = :body,
                    author = :author,
                    category_id = :category_id
                WHERE
                    id = :id";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->body = htmlspecialchars(strip_tags($this->body));
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':category_id', $this->category_id);
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

        // Delete Post
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