<?php
    class UserModel 
    {
        // DB config
        private $conn;
        private $table = 'user';
        private $tb_profile = 'profile';

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
                $this->id = $user_id;
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

        public function update_profile()
        {
            // Create query
            $query = "UPDATE {$this->tb_profile}
                SET
                    firstName = :firstName,
                    lastName = :lastName,
                    detail = :detail,
                    birthDay = :birthDay
                WHERE
                    user_id = :user_id";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->firstName = htmlspecialchars(strip_tags($this->firstName));
            $this->lastName = htmlspecialchars(strip_tags($this->lastName));
            $this->detail = htmlspecialchars(strip_tags($this->detail));
            $this->birthDay = htmlspecialchars(strip_tags($this->birthDay));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':firstName', $this->firstName);
            $stmt->bindParam(':lastName', $this->lastName);
            $stmt->bindParam(':detail', $this->detail);
            $stmt->bindParam(':birthDay', $this->birthDay);
            $stmt->bindParam(':user_id', $this->id);

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
            // Delete profile first.
            if ($this->delete_profile())
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

        public function delete_profile()
        {
            // Delete profile first.
            $query = "DELETE FROM {$this->tb_profile} WHERE user_id = :id";
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

        public function login()
        {
            $query = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->conn->prepare($query);
            // Bind ID
            $stmt->bindParam(1, $this->username);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data)
            {
                if (password_verify($this->password, $user_data['password']))
                {
                    $this->id = $user_data['id'];
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }

        public function user_count()
        {
            $query = "SELECT count(id) AS user_count FROM user";
            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            $user_count = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user_count;
        }
    }