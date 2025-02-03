<?php

/**
 * Classe di utilità per la gestione del database.
 * Utilizza il pattern Singleton per la connessione.
 */
class DatabaseHelper {
    private ?mysqli $db = null;
    private static ?DatabaseHelper $instance = null;
    private array $configuration;

    /**
     * Costruttore - Inizializza la configurazione e stabilisce la connessione.
     */
    public function __construct() {
        $this->configuration = [
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'SassiShop',
            'charset' => 'utf8mb4'
        ];
        $this->connect();
    }

    /**
     * Restituisce l'istanza Singleton di DatabaseHelper.
     * 
     * @return DatabaseHelper Istanza Singleton di DatabaseHelper.
     */
    public static function getInstance(): DatabaseHelper {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Effettua la connessione al database (se non già connesso).
     */
    private function connect(): void {
        if ($this->db === null) {
            try {
                $this->db = new mysqli(
                    $this->configuration['servername'],
                    $this->configuration['username'],
                    $this->configuration['password'],
                    $this->configuration['database']
                );

                if ($this->db->connect_error) {
                    throw new Exception("Connection failed: " . $this->db->connect_error);
                }

                $this->db->set_charset($this->configuration['charset']);
            } catch(Exception $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Restituisce l'oggetto mysqli.
     * 
     * @return mysqli Connessione al database.
     */
    public function getConnection(): mysqli {
        $this->connect();
        return $this->db;
    }

    /**
     * Prepara ed esegue una query con parametri.
     *
     * @param string $sql Query SQL con eventuali placeholder.
     * @param array  $params Parametri per il bind.
     * 
     * @return mysqli_stmt Statement eseguito.
     */
    private function execute(string $sql, array $params = []): mysqli_stmt {
        $this->connect();
        $temp = $this->db->prepare($sql);
        
        if (!$temp) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $temp->bind_param($types, ...array_values($params));
        }

        if (!$temp->execute()) {
            throw new Exception("Execute failed: " . $temp->error);
        }

        return $temp;
    }

    /**
     * Aggiunge un nuovo utente nella tabella User.
     * 
     * @param string $firstName Nome dell'utente.
     * @param string $lastName Cognome dell'utente.
     * @param string $email Email dell'utente.
     * @param string $password Password dell'utente.
     * @param int    $privilege Privilegio dell'utente (default a User - 1).
     * 
     * @return bool True se l'utente è stato aggiunto correttamente, altrimenti false.
     */
    public function addUser(string $firstName, string $lastName, string $username, string $email, string $password, int $privilege = 2): bool {
        $sql = "INSERT INTO User (firstName, lastName, username, email, password, privilege)
                VALUES (?, ?, ?, ?, ?, ?)";
        $temp = $this->execute($sql, [
            trim($firstName),
            trim($lastName),
            trim($username),
            filter_var(trim($email), FILTER_SANITIZE_EMAIL),
            password_hash($password, PASSWORD_DEFAULT),
            $privilege
        ]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    public function updateUser(int $userId, string $firstName = "", string $lastName = "", string $username = "", string $email = "") {
        $sql = "UPDATE `user` SET
        firstName = ?,
        lastName = ?,
        username = ?,
        email = ?
        WHERE User.id = ?";

        $temp = $this->execute($sql, [$firstName, $lastName, $username, $email, $userId]);
        $temp->close();
    }

    /**
     * Restituisce la lista delle categorie presenti nella tabella Category.
     * 
     * @return array Array di categorie.
     */
    public function getCategories(): array {
        $sql = "SELECT * FROM Category";
        $temp = $this->execute($sql);
        $result = $temp->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();

        return $categories;
    }

    /**
     * Restituisce una lista di post dalla tabella Post, con info utente e prodotto.
     * 
     * @param int $limit Limite di post da restituire.
     * @param int $offset Offset per la paginazione.
     * 
     * @return array Array di post.
     */
    public function getPosts(int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name AS productName,
                (SELECT AVG(rating) FROM Rating WHERE post = p.id) AS averageRating
                FROM Post p
                JOIN User u ON p.seller = u.id
                JOIN Product pr ON p.product = pr.id
                ORDER BY p.date DESC LIMIT ? OFFSET ?";

        $temp = $this->execute($sql, [$limit, $offset]);
        $result = $temp->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();

        return $posts;
    }

    /**
     * Restituisce un singolo post con dettagli, inclusa valutazione media e numero di commenti.
     * 
     * @param int $postId ID del post.
     * 
     * @return array Array con i dettagli del post.
     */
    public function getPostWithDetails(int $postId): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.*,
                (SELECT AVG(rating) FROM Rating WHERE post = p.id) AS averageRating,
                (SELECT COUNT(*) FROM Comment WHERE post = p.id) AS commentCount
                FROM Post p
                JOIN User u ON p.seller = u.id
                JOIN Product pr ON p.product = pr.id
                WHERE p.id = ?";

        $temp = $this->execute($sql, [$postId]);
        $result = $temp->get_result();
        $post = $result->fetch_assoc() ?: [];
        $temp->close();

        return $post;
    }

    /**
     * Restituisce i commenti di un post dalla tabella Comment.
     * 
     * @param int $postId ID del post.
     * 
     * @return array Array di commenti.
     */
    public function getComments(int $postId): array {
        $sql = "SELECT c.*, u.firstName, u.lastName
                FROM Comment c
                JOIN User u ON c.user = u.id
                WHERE c.post = ?
                ORDER BY c.date DESC";

        $temp = $this->execute($sql, [$postId]);
        $result = $temp->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();

        return $comments;
    }

    /**
     * Aggiunge un commento nella tabella Comment.
     * 
     * @param int    $postId ID del post.
     * @param int    $author ID dell'autore del commento.
     * @param string $content Contenuto del commento.
     * 
     * @return bool True se il commento è stato aggiunto correttamente, altrimenti false.
     */
    public function addComment(int $postId, int $author, string $content): bool {
        $sql = "INSERT INTO Comment (post, user, comment) VALUES (?, ?, ?)";
        $temp = $this->execute($sql, [$postId, $author, $content]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    /**
     * Aggiunge una valutazione (rating) nella tabella Rating.
     * 
     * @param int $postId ID del post.
     * @param int $userId ID dell'utente.
     * @param int $rating Valutazione da assegnare.
     * 
     * @return bool True se la valutazione è stata aggiunta correttamente, altrimenti false.
     */
    public function addRating(int $postId, int $userId, int $rating): bool {
        $sql = "INSERT INTO Rating (post, user, rating) VALUES (?, ?, ?)";
        $temp = $this->execute($sql, [$postId, $userId, $rating]);
        $result = $temp->affected_rows > 0;
        $temp->close();
        
        return $result;
    }

    /**
     * Aggiunge un prodotto alla wishlist.
     * 
     * @param int $productId ID del prodotto.
     * @param int $userId ID dell'utente.
     * 
     * @return bool True se il prodotto è stato aggiunto correttamente, altrimenti false.
     * 
     */
    public function addProductWishlist(int $productId, int $userId): bool{
        $sql = "INSERT INTO Wishlist (user, product) VALUES (?, ?)";
        $temp = $this->execute($sql, [$userId, $productId]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    /**
     * Rimuove un prodotto dalla wishlist.
     * 
     * @param int $productId ID del prodotto.
     * @param int $userId ID dell'utente.
     * 
     * @return bool True se il prodotto è stato rimosso correttamente, altrimenti false.
     * 
     */
    public function removeProductWishlist(int $productId, int $userId): bool{
        $sql = "DELETE from wishlist WHERE product = ? AND user = ?";
        $temp = $this->execute($sql, [$productId, $userId]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    /**
     * Controlla se un prodotto è già presente nella wishlist.
     * 
     * @param int $productId ID del prodotto.
     * @param int $userId ID dell'utente.
     * 
     * @return bool True se il prodotto è già stato aggiunto, altrimenti false.
     * 
     */
    public function checkProductWishlist(int $productId, int $userId): bool{
        $sql = "SELECT * from wishlist WHERE product = ? AND user = ?";
        $temp = $this->execute($sql, [$productId, $userId]);
        $result = $temp->get_result();
        $temp->close();

        return (count($result->fetch_all(MYSQLI_ASSOC)) > 0);
    }
    /**
     * Restituisce il rating di un utente su un post, se esiste.
     * 
     * @param int $postId ID del post.
     * @param int $userId ID dell'utente.
     * 
     * @return int|null Valutazione dell'utente sul post, se esiste, altrimenti null.
     */
    public function getRating(int $postId, int $userId): ?int {
        $sql = "SELECT rating FROM Rating WHERE post = ? AND user = ?";
        $temp = $this->execute($sql, [$postId, $userId]);
        $result = $temp->get_result();
        $rating = $result->fetch_row()[0] ?? null;
        $temp->close();

        return $rating;
    }

    /**
     * Restituisce tutti i prodotti presenti nella tabella Product, con un filtro opzionale per il nome.
     * 
     * @param string $name Filtro per il nome del prodotto (default = "").
     * 
     * @return array Array di prodotti.
     */
    public function getProducts(string $name = "", int $category = -1): array {
        $sql = "SELECT * FROM Product";
        $params = [];

        if(!empty($name)) {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%$name%";
        }
        if($category != -1) {
            $sql .= empty($name)  ? " WHERE category = ?" : " AND category = ?";
            $params[] = $category;
        }

        $temp = $this->execute($sql, $params);
        
        $result = $temp->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();
        
        return $products;
    }

    /**
     * Restituisce il dettaglio di un prodotto.
     * 
     * @param int $productId ID del prodotto.
     * 
     * @return array Array con i dettagli del prodotto.
     */
    public function getProductInfo(int $productId): array {
        $sql = "SELECT * FROM Product WHERE id = ?";
        $temp = $this->execute($sql, [$productId]);
        $result = $temp->get_result();
        $product = $result->fetch_assoc() ?: [];
        $temp->close();

        return $product;
    }

    /**
     * Ricerca tra i post per titolo o descrizione, restituendo post, utente e prodotto associati.
     * 
     * @param string $query Testo da cercare.
     * @param int    $limit Limite di post da restituire.
     * @param int    $offset Offset per la paginazione.
     * 
     * @return array Array di post, utenti e prodotti.
     */
    public function searchPosts(string $query, int $limit = 10, int $offset = 0): array {
        $sql = "SELECT p.*, u.firstName, u.lastName, pr.name AS productName,
                (SELECT AVG(rating) FROM Rating WHERE post = p.id) AS averageRating
                FROM Post p
                JOIN User u ON p.seller = u.id
                JOIN Product pr ON p.product = pr.id
                WHERE p.title LIKE ? OR p.description LIKE ?
                ORDER BY p.date DESC LIMIT ? OFFSET ?";

        $searchQuery = "%$query%";
        $temp = $this->execute($sql, [$searchQuery, $searchQuery, $limit, $offset]);
        $result = $temp->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();

        return $posts;
    }

    /**
     * Verifica le credenziali di un utente e, se valide, restituisce i dati dell'utente senza la password.
     * 
     * @param string $email Email dell'utente.
     * @param string $password Password dell'utente.
     * 
     * @return array|null Dati dell'utente senza la password, se le credenziali sono valide, altrimenti null.
     */
    public function login(string $email, string $password): ?array {
        $sql = "SELECT * FROM User WHERE email = ?";
        $temp = $this->execute($sql, [$email]);
        $result = $temp->get_result();
        $user = $result->fetch_assoc();
        $temp->close();
        
        if ($user && $user["password"] == password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return null;
    }

    /**
     * Restituisce tutti i purchase effettuati da un utente con i relativi prodotti.
     * 
     * @param int $userId ID dell'utente.
     * 
     * @return array Array di purchase con i relativi prodotti.
     */
    public function getUserOrders(int $userId): array {
        $sql = "SELECT * FROM Purchase, Product WHERE Purchase.user = ? AND Purchase.product = Product.id";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce la wishlist di un utente con i relativi prodotti.
     * 
     * @param int $userId ID dell'utente.
     * 
     * @return array Array di prodotti nella wishlist.
     */
    public function getUserWishlist(int $userId): array {
        $sql = "SELECT * FROM Wishlist, Product WHERE Wishlist.user = ? AND Wishlist.product = Product.id";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce il carrello di un utente con i relativi prodotti.
     * 
     * @param int $userId ID dell'utente.
     * 
     * @return array Array di prodotti nel carrello.
     */
    public function getUserCart(int $userId): array {
        $sql = "SELECT * FROM Cart, Product WHERE Cart.user = ? AND Cart.product = Product.id";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * controlla che non ci siano altri utenti con lo stesso username.
     * 
     * @param string $username username identificativo dell'utente.
     * 
     * @return bool false se esiste già un utente con quell'username, se no true.
     */
    public function checkUsername(string $username): bool{
        $sql = "SELECT * FROM User WHERE User.username = ?";
        $temp = $this->execute($sql, [$username]);
        $result = $temp->get_result();
        $temp->close();
        
        return (count($result->fetch_all(MYSQLI_ASSOC)) > 0);
    }

    public function getUserInfo(int $userId){
        $sql = "SELECT * FROM User WHERE User.id = ?";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $user = $result->fetch_assoc();
        unset($user["password"]);
        $temp->close();

        return $user;
    }

    /**
     * Distruttore - Chiude la connessione al database.
     */
    public function __destruct() {
        if ($this->db !== null) {
            $this->db->close();
        }
    }
}

?>