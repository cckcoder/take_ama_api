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
        public $detail;
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
                    u.id,
                    u.username,
                    u.email,
                    u.userType,
                    p.firstName,
                    p.lastName,
                    p.detail,
                    p.birthDay,
                    p.isActive
                from user as u
                left join profile as p
                    on p.user_id = u.id
                ORDER BY
                    u.createdAt DESC";

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;

        }

        // Get Single Post
        public function read_single()
        {
            $query = "SELECT 
                    u.id,
                    u.username,
                    u.email,
                    u.userType,
                    p.firstName,
                    p.lastName,
                    p.detail,
                    p.birthDay,
                    p.isActive
                from user as u
                left join profile as p
                    on p.user_id = u.id
                where u.id = :userId";

            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind ID
            $stmt->bindParam(':userId', $this->id);
            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Set Properties
            if ($row)
            {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->userType = $row['userType'];
                $this->firstName = $row['firstName'];
                $this->lastName = $row['lastName'];
                $this->detail = $row['detail'];
                $this->birthDay = $row['birthDay'];
                $this->isActive = $row['isActive'];
            }
            else
            {
                $this->id = null;
            }
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
                    detail = :detail,
                    birthDay = :birthDay,
                    user_id = :user_id";

            // Prepare statement
            $stmt2 = $this->conn->prepare($query);
            // Clean Data
            $this->firstName = htmlspecialchars(strip_tags($this->firstName));
            $this->lastName = htmlspecialchars(strip_tags($this->lastName));
            $this->detail = htmlspecialchars(strip_tags($this->detail));
            $this->birthDay = htmlspecialchars(strip_tags($this->birthDay));
            // Bind data
            $stmt2->bindParam(':firstName', $this->firstName);
            $stmt2->bindParam(':lastName', $this->lastName);
            $stmt2->bindParam(':detail', $this->detail);
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