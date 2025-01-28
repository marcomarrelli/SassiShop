<?php

class DatabaseHelper {
    private ?PDO $db = null;
    private static ?Database $instance = null;
    private array $config;

    public function __construct(array $config = []) {
        $this->config = array_merge([
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'your_database_name',
            'charset' => 'utf8mb4'
        ], $config);
    }

    public static function getInstance(array $config = []): Database {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    private function connect(): void {
        if ($this->db === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=%s",
                    $this->config['servername'],
                    $this->config['database'],
                    $this->config['charset']
                );

                $this->db = new PDO($dsn, $this->config['username'], $this->config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']}"
                ]);
            } catch(PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public function getConnection(): PDO {
        $this->connect();
        return $this->db;
    }

    private function execute(string $sql, array $params = []): PDOStatement {
        $this->connect();
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function addUser(string $firstName, string $lastName, string $email, string $password, int $privilege = 1): bool {
        $sql = "INSERT INTO Users (firstName, lastName, email, password, privilege) 
                VALUES (:firstName, :lastName, :email, :password, :privilege)";
        return $this->execute($sql, [
            ':firstName' => trim($firstName),
            ':lastName' => trim($lastName),
            ':email' => filter_var(trim($email), FILTER_SANITIZE_EMAIL),
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':privilege' => $privilege
        ])->rowCount() > 0;
    }

    public function getPosts(int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name as productName,
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                ORDER BY p.date DESC LIMIT :limit OFFSET :offset";
        
        return $this->execute($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ])->fetchAll();
    }

    public function getPostWithDetails(int $postId): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.*, 
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating,
                (SELECT COUNT(*) FROM Comments WHERE post = p.id) as commentCount
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                WHERE p.id = :postId";
        
        return $this->execute($sql, [':postId' => $postId])->fetch() ?: [];
    }

    public function getComments(int $postId): array {
        $sql = "SELECT c.*, u.firstName, u.lastName 
                FROM Comments c 
                JOIN Users u ON c.author = u.id 
                WHERE c.post = :postId 
                ORDER BY c.date DESC";
        
        return $this->execute($sql, [':postId' => $postId])->fetchAll();
    }

    public function addComment(int $postId, int $author, string $content): bool {
        $sql = "INSERT INTO Comments (post, author, content) 
                VALUES (:postId, :author, :content)";
        return $this->execute($sql, [
            ':postId' => $postId,
            ':author' => $author,
            ':content' => $content
        ])->rowCount() > 0;
    }

    public function addRating(int $postId, int $userId, int $rating): bool {
        $sql = "INSERT INTO Ratings (post, user, rating) 
                VALUES (:postId, :userId, :rating)";
        return $this->execute($sql, [
            ':postId' => $postId,
            ':userId' => $userId,
            ':rating' => $rating
        ])->rowCount() > 0;
    }

    public function getRating(int $postId, int $userId): ?int {
        $sql = "SELECT rating FROM Ratings WHERE post = :postId AND user = :userId";
        return $this->execute($sql, [':postId' => $postId, ':userId' => $userId])->fetchColumn();
    }

    public function getProducts(): array {
        $sql = "SELECT * FROM Product";
        return $this->execute($sql)->fetchAll();
    }

    public function getProduct(int $productId): array {
        $sql = "SELECT * FROM Product WHERE id = :productId";
        return $this->execute($sql, [':productId' => $productId])->fetch() ?: [];
    }

    public function searchPosts(string $query, int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name as productName,
                (SELECT AVG(rating) FROM Ratings WHERE post = p.id) as averageRating
                FROM Posts p 
                JOIN Users u ON p.seller = u.id 
                JOIN Product pr ON p.product = pr.id 
                WHERE p.title LIKE :query OR p.description LIKE :query
                ORDER BY p.date DESC LIMIT :limit OFFSET :offset";
        
        return $this->execute($sql, [
            ':query' => "%$query%",
            ':limit' => $limit,
            ':offset' => $offset
        ])->fetchAll();
    }

    public function login(string $email, string $password): ?array {
        $sql = "SELECT * FROM Users WHERE email = :email";
        $user = $this->execute($sql, [':email' => $email])->fetch();
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }

    public function __destruct() {
        $this->db = null;
    }
}
