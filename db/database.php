<?php

class DatabaseHelper {
    private ?mysqli $db = null;
    private static ?DatabaseHelper $instance = null;
    private array $config;

    public function __construct(array $config = []) {
        $this->config = array_merge([
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'your_database_name',
            'charset' => 'utf8mb4'
        ], $config);
        $this->connect();
    }

    public static function getInstance(array $config = []): DatabaseHelper {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    private function connect(): void {
        if ($this->db === null) {
            try {
                $this->db = new mysqli(
                    $this->config['servername'],
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['database']
                );

                if ($this->db->connect_error) {
                    throw new Exception("Connection failed: " . $this->db->connect_error);
                }

                $this->db->set_charset($this->config['charset']);
            } catch(Exception $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public function getConnection(): mysqli {
        $this->connect();
        return $this->db;
    }

    private function execute(string $sql, array $params = []): mysqli_stmt {
        $this->connect();
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...array_values($params));
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        return $stmt;
    }

    public function addUser(string $firstName, string $lastName, string $email, string $password, int $privilege = 1): bool {
        $sql = "INSERT INTO Users (firstName, lastName, email, password, privilege) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->execute($sql, [
            trim($firstName),
            trim($lastName),
            filter_var(trim($email), FILTER_SANITIZE_EMAIL),
            password_hash($password, PASSWORD_DEFAULT),
            $privilege
        ]);
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function getPosts(int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name as productName,
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                ORDER BY p.date DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->execute($sql, [$limit, $offset]);
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $posts;
    }

    public function getPostWithDetails(int $postId): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.*, 
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating,
                (SELECT COUNT(*) FROM Comments WHERE post = p.id) as commentCount
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                WHERE p.id = ?";
        
        $stmt = $this->execute($sql, [$postId]);
        $result = $stmt->get_result();
        $post = $result->fetch_assoc() ?: [];
        $stmt->close();
        return $post;
    }

    public function getComments(int $postId): array {
        $sql = "SELECT c.*, u.firstName, u.lastName 
                FROM Comments c 
                JOIN Users u ON c.author = u.id 
                WHERE c.post = ? 
                ORDER BY c.date DESC";
        
        $stmt = $this->execute($sql, [$postId]);
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $comments;
    }

    public function addComment(int $postId, int $author, string $content): bool {
        $sql = "INSERT INTO Comments (post, author, content) VALUES (?, ?, ?)";
        $stmt = $this->execute($sql, [$postId, $author, $content]);
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function addRating(int $postId, int $userId, int $rating): bool {
        $sql = "INSERT INTO Ratings (post, user, rating) VALUES (?, ?, ?)";
        $stmt = $this->execute($sql, [$postId, $userId, $rating]);
        $result = $stmt->affected_rows > 0;
        $stmt->close();
        return $result;
    }

    public function getRating(int $postId, int $userId): ?int {
        $sql = "SELECT rating FROM Ratings WHERE post = ? AND user = ?";
        $stmt = $this->execute($sql, [$postId, $userId]);
        $result = $stmt->get_result();
        $rating = $result->fetch_row()[0] ?? null;
        $stmt->close();
        return $rating;
    }

    public function getProducts(): array {
        $sql = "SELECT * FROM Product";
        $stmt = $this->execute($sql);
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $products;
    }

    public function getProduct(int $productId): array {
        $sql = "SELECT * FROM Product WHERE id = ?";
        $stmt = $this->execute($sql, [$productId]);
        $result = $stmt->get_result();
        $product = $result->fetch_assoc() ?: [];
        $stmt->close();
        return $product;
    }

    public function searchPosts(string $query, int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name as productName,
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                WHERE p.title LIKE ? OR p.description LIKE ?
                ORDER BY p.date DESC LIMIT ? OFFSET ?";
        
        $searchQuery = "%$query%";
        $stmt = $this->execute($sql, [$searchQuery, $searchQuery, $limit, $offset]);
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $posts;
    }

    public function login(string $email, string $password): ?array {
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $this->execute($sql, [$email]);
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }


    public function __destruct() {
        if ($this->db !== null) {
            $this->db->close();
        }
    }
}
