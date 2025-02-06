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
    public function addUser(string $firstName, string $lastName, string $username, string $email, string $password, string $creditCard = "", int $privilege = 2): bool {
        $sql = "INSERT INTO User (firstName, lastName, username, email, password, creditCard, privilege)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $temp = $this->execute($sql, [
            trim($firstName),
            trim($lastName),
            trim($username),
            filter_var(trim($email), FILTER_SANITIZE_EMAIL),
            password_hash($password, PASSWORD_DEFAULT),
            $creditCard,
            $privilege
        ]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    public function updateUser(int $userId, string $firstName = "", string $lastName = "", string $username = "", string $email = "", string $creditCard = ""): void {
        $sql = "UPDATE `user` SET
        firstName = ?,
        lastName = ?,
        username = ?,
        email = ?,
        creditCard = ?
        WHERE User.id = ?";

        $temp = $this->execute($sql, [$firstName, $lastName, $username, $email, $creditCard, $userId]);
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
     * Aggiorna la password dell'utente
     * 
     * @param string $password nuova password dell'utente.
     * @param string $userId ID dell'utente.
     * 
     * @return bool se la modifica è andata a buon fine true, altrimenti false
     */
    public function updatePassword(string $password, int $userId): bool{
        $sql = "UPDATE User SET password = ? WHERE id = ?";
        $temp = $this->execute($sql, [password_hash($password, PASSWORD_DEFAULT), $userId]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    /**
     * Restituisce tutti i purchase effettuati da un utente con i relativi prodotti.
     * 
     * @param int $userId ID dell'utente.
     * 
     * @return array Array di purchase con i relativi prodotti.
     */
    public function getUserOrders(int $userId): array {
        $sql = "SELECT Purchase.id as purchaseId, Purchase.user, Purchase.date, Purchase.status FROM Purchase WHERE Purchase.user = ? ORDER BY date desc";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminOrders(): array{
        $sql = "SELECT Purchase.id as purchaseId, Purchase.user, Purchase.date, Purchase.status, User.id AS userId, User.username FROM Purchase, User WHERE Purchase.user = User.id ORDER BY date desc";
        $temp = $this->execute($sql);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductList(int $purchaseId): array {
        $sql = "SELECT p.id, p.name, p.description, p.image, p.price, pl.quantity as orderQuantity, pl.productPrice as orderPrice
                FROM ProductList pl
                JOIN Product p ON pl.product = p.id
                WHERE pl.purchase = ?";
        
        $temp = $this->execute($sql, [$purchaseId]);
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
        $sql = "SELECT Cart.product, Cart.quantity as cartQuantity, Product.quantity as availableQuantity,
                Product.name, Product.description, Product.price, Product.image, Product.id
                FROM Cart 
                INNER JOIN Product ON Cart.product = Product.id 
                WHERE Cart.user = ?";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserNotification(int $userId): array {
        $sql = "SELECT * FROM Notification WHERE Notification.user = ? ORDER BY date DESC";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserNotificationUnread(int $userId): array {
        $sql = "SELECT COUNT(*) AS numNotification FROM Notification WHERE Notification.user = ? AND Notification.isRead = 0";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_assoc();
    }

    public function readNotification(int $notificationId): bool{
        $sql = "UPDATE notification SET isRead = 1 WHERE notification.id = ?";
        $temp = $this->execute($sql, [$notificationId]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    public function getNotificationInfo(int $notificationId): array{
        $sql = "SELECT * FROM Notification WHERE Notification.id = ?";
        $temp = $this->execute($sql, [$notificationId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->fetch_assoc();
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

    /**
     * controlla che non ci siano altri utenti con la stessa email.
     * 
     * @param string $email email identificativo dell'utente.
     * 
     * @return bool false se esiste già un utente con quell'username, se no true.
     */
    public function checkEmail(string $email): bool{
        $sql = "SELECT * FROM User WHERE User.email = ?";
        $temp = $this->execute($sql, [$email]);
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
     * Restituisce il nome di una categoria dato il suo ID.
     * 
     * @param int $categoryId ID della categoria.
     * 
     * @return string Nome della categoria.
     */
    public function getCategoryName(int $categoryId): string {
        $sql = "SELECT name FROM Category WHERE id = ?";
        $temp = $this->execute($sql, [$categoryId]);
        $result = $temp->get_result();
        $category = $result->fetch_assoc();
        $temp->close();
        return $category['name'] ?? '';
    }

    /**
     * Restituisce le size disponibili.
     * 
     * @return array Array delle size.
     */
    public function getSizes(): array {
        $sql = "SELECT * FROM Size";
        $temp = $this->execute($sql);
        $result = $temp->get_result();
        $sizes = $result->fetch_all(MYSQLI_ASSOC);
        $temp->close();

        return $sizes;
    }

    /**
     * Restituisce il nome di una dimensione dato il suo ID.
     * 
     * @param int $sizeId ID della dimensione.
     * 
     * @return string Nome della dimensione.
     */
    public function getSizeName(int $sizeId): string {
        $sql = "SELECT size FROM Size WHERE id = ?";
        $temp = $this->execute($sql, [$sizeId]);
        $result = $temp->get_result();
        $size = $result->fetch_assoc();
        $temp->close();
        return $size['size'] ?? '';
    }

    /**
     * Funzione per aggiungere un prodotto al database.
     * 
     * @param string $name Nome del prodotto.
     * @param string $description Descrizione del prodotto.
     * @param float  $price Prezzo del prodotto.
     * @param int    $quantity Quantità del prodotto.
     * @param int    $category Categoria del prodotto.
     * @param int    $size Dimensione del prodotto.
     * @param string $image Immagine del prodotto.
     * 
     * @return bool True se il prodotto è stato aggiunto correttamente, altrimenti false.
     */
    public function addProduct(string $name, string $description, float $price, int $quantity, int $category, int $size, string $image): bool {
        $sql = "INSERT INTO Product (name, description, price, quantity, category, size, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $temp = $this->execute($sql, [trim($name), trim($description), $price, $quantity, $category, $size, $image]);
        $result = $temp->affected_rows > 0;
        $temp->close();

        return $result;
    }

    /**
     * Funzione per modificare un prodotto sul database.
     * 
     * @param int    $productId ID del prodotto.
     * @param string $name Nome del prodotto.
     * @param string $description Descrizione del prodotto.
     * @param float  $price Prezzo del prodotto.
     * @param int    $quantity Quantità del prodotto.
     * @param int    $category Categoria del prodotto.
     * @param int    $size Dimensione del prodotto.
     * @param string $image Immagine del prodotto.
     * 
     * @return bool True se il prodotto è stato modificato correttamente, altrimenti false.     
     */
    public function updateProduct(int $productId, string $name = "", string $description = "", float $price = 0, int $quantity = 0, int $category = -1, int $size = -1, string $image = ""): bool {
        $this->db->begin_transaction();
        try {
            $sql = "UPDATE Product SET";
            $params = [];
    
            if(!empty($name)) {
                $sql .= " name = ?,";
                $params[] = $name;
            }
            if(!empty($description)) {
                $sql .= " description = ?,";
                $params[] = $description;
            }
            if($price > 0) {
                $sql .= " price = ?,";
                $params[] = $price;
            }
            if($quantity >= 0) {
                $sql .= " quantity = ?,";
                $params[] = $quantity;
            }
            if($category != -1) {
                $sql .= " category = ?,";
                $params[] = $category;
            }
            if($size != -1) {
                $sql .= " size = ?,";
                $params[] = $size;
            }
            if(!empty($image)) {
                $sql .= " image = ?,";
                $params[] = $image;
            }
    
            if(empty($params)) {
                $this->db->rollback();
                return false;
            }
    
            $sql = rtrim($sql, ",");
            $sql .= " WHERE id = ?";
            $params[] = $productId;
    
            $stmt = $this->execute($sql, $params);
            $result = $stmt->affected_rows > 0;
            $stmt->close();
    
            if($result && $quantity >= 0) {
                $this->checkAndNotifyRefill($productId, $quantity);
                $this->checkAndNotifyLowStock($productId, $quantity);
            }
    
            $this->db->commit();
            return $result;
    
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function checkProductCart(int $productId, int $userId) : bool {
        $sql = "SELECT * FROM Cart WHERE user = ? AND product = ?";
        $temp = $this->execute($sql, [$userId, $productId]);
        $result = $temp->get_result();
        $temp->close();

        return $result->num_rows > 0;
    }

    public function addProductCart(int $productId, int $userId, int $quantity) : bool {
        try {
            $sql = "INSERT INTO Cart (user, product, quantity) VALUES (?, ?, ?)";
            $stmt = $this->execute($sql, [$userId, $productId, $quantity]);
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function removeProductCart(int $productId, int $userId): bool {
        try {
            $sql = "DELETE FROM Cart WHERE user = ? AND product = ?";
            $stmt = $this->execute($sql, [$userId, $productId]);
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function addCartProductQuantity(int $productId, int $userId, int $increment = 1): bool {
        $this->db->begin_transaction();
        try {
            $sql = "SELECT c.quantity as cartQuantity, p.quantity as maxQuantity 
                    FROM Cart c 
                    JOIN Product p ON c.product = p.id 
                    WHERE c.user = ? AND c.product = ?";
            $stmt = $this->execute($sql, [$userId, $productId]);
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
    
            if (!$result) {
                throw new Exception("Product not in cart");
            }
    
            $newQuantity = $result['cartQuantity'] + $increment;
            if ($newQuantity > $result['maxQuantity']) {
                $this->db->rollback();
                return false;
            }
    
            $sql = "UPDATE Cart SET quantity = quantity + ? WHERE user = ? AND product = ?";
            $stmt = $this->execute($sql, [$increment, $userId, $productId]);
            $updated = $stmt->affected_rows > 0;
            $stmt->close();
    
            if ($updated) {
                $this->db->commit();
                return true;
            }
    
            $this->db->rollback();
            return false;
    
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function removeCartProductQuantity(int $productId, int $userId, int $decrement = 1): bool {
        try {
            $sql = "UPDATE Cart SET quantity = quantity - ? WHERE user = ? AND product = ? AND quantity > ?";
            $stmt = $this->execute($sql, [$decrement, $userId, $productId, $decrement]);
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function createPurchase(int $userId): bool {
        $this->db->begin_transaction();
        try {
            // Create purchase record
            $sql = "INSERT INTO Purchase (user, status) VALUES (?, 'PENDING')";
            $stmt = $this->execute($sql, [$userId]);
            if ($stmt->affected_rows <= 0) {
                throw new Exception("Failed to create purchase");
            }
            $purchaseId = $this->db->insert_id;
            $stmt->close();
    
            // Get cart items
            $sql = "SELECT c.product, c.quantity as cartQuantity, p.price, p.quantity as available 
                    FROM Cart c 
                    JOIN Product p ON c.product = p.id 
                    WHERE c.user = ?";
            $stmt = $this->execute($sql, [$userId]);
            $cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
    
            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }
    
            // Get admin users once
            $sql = "SELECT id FROM User WHERE privilege = 1";
            $stmt = $this->execute($sql);
            $admins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
    
            foreach ($cartItems as $item) {
                // Check stock
                if ($item['available'] < $item['cartQuantity']) {
                    throw new Exception("Insufficient stock for product " . $item['product']);
                }
    
                // Add to purchase list
                $sql = "INSERT INTO ProductList (purchase, product, quantity, productPrice) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->execute($sql, [
                    $purchaseId, 
                    $item['product'], 
                    $item['cartQuantity'],
                    $item['price']
                ]);
                $stmt->close();
    
                // Update product quantity
                $newQuantity = $item['available'] - $item['cartQuantity'];
                if (!$this->updateProduct($item['product'], "", "", 0, $newQuantity)) {
                    throw new Exception("Failed to update stock");
                }
    
                if($newQuantity == 0){
                    $sql = "SELECT DISTINCT c.user FROM Cart c WHERE c.product = ?";
                    $stmt = $this->execute($sql, [$item['product']]);
                    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
    
                    if (!empty($users)) {
                        $sql = "INSERT INTO Notification (type, user, product, isRead) VALUES (1, ?, ?, 0)";
                        foreach ($users as $user) {
                            if($user['user']!=$userId){
                                $stmt = $this->execute($sql, [$user['user'], $item['product']]);
                                if ($stmt->affected_rows <= 0) {
                                    throw new Exception("Failed to add notification");
                                }
                                $stmt->close();
                            }
                        }
                    }
                    // Notify admins for empty stock
                    foreach ($admins as $admin) {
                        $sql = "INSERT INTO Notification (type, user, product, isRead) 
                                VALUES (1, ?, ?, 0)";
                        $stmt = $this->execute($sql, [$admin['id'], $item['product']]);
                        $stmt->close();
                    }
                }
                
    
                // Notify admins for low stock
                if ($newQuantity > 0 && $newQuantity <= 5) {
                    foreach ($admins as $admin) {
                        $sql = "INSERT INTO Notification (type, user, product, isRead) 
                                VALUES (4, ?, ?, 0)";
                        $stmt = $this->execute($sql, [$admin['id'], $item['product']]);
                        $stmt->close();
                    }
                }
            }
    
            // Notify admins of new purchase
            foreach ($admins as $admin) {
                $sql = "INSERT INTO Notification (type, user, isRead, purchaseUser) 
                        VALUES (3, ?, 0, ?)";
                $stmt = $this->execute($sql, [$admin['id'], $userId]);
                $stmt->close();
            }
    
            // Clear cart
            $sql = "DELETE FROM Cart WHERE user = ?";
            $stmt = $this->execute($sql, [$userId]);
            $stmt->close();
    
            $this->db->commit();
            return true;
    
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getCartCount(int $userId) : int {
        $sql = "SELECT SUM(quantity) as total FROM Cart WHERE user = ?";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $cartCount = $result->fetch_assoc()['total'] ?? 0;
        $temp->close();

        return $cartCount;
    }

    public function getCartTotal(int $userId) : float {
        $sql = "SELECT SUM(price * Cart.quantity) as total FROM Cart, Product WHERE user = ? AND product = Product.id";
        $temp = $this->execute($sql, [$userId]);
        $result = $temp->get_result();
        $cartTotal = $result->fetch_assoc()['total'] ?? 0;
        $temp->close();

        return $cartTotal;
    }

    public function checkAndNotifyRefill(int $productId, int $newQuantity): void {
        if ($newQuantity <= 0) return;
    
        $this->db->begin_transaction();
        try {
            $sql = "SELECT DISTINCT c.user 
                    FROM Cart c 
                    WHERE c.product = ? 
                    AND c.quantity <= ?";
            
            $stmt = $this->execute($sql, [$productId, $newQuantity]);
            $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
    
            if (empty($users)) {
                $this->db->rollback();
                return;
            }
    
            // Insert refill notification for each user
            $sql = "INSERT INTO Notification (type, user, product, isRead) VALUES (2, ?, ?, 0)";
            foreach ($users as $user) {
                $stmt = $this->execute($sql, [$user['user'], $productId]);
                if ($stmt->affected_rows <= 0) {
                    throw new Exception("Failed to add notification");
                }
                $stmt->close();
            }
    
            $this->db->commit();
    
        } catch (Exception $e) {
            $this->db->rollback();
            return;
        }
    }
 
    public function checkAndNotifyLowStock(int $productId, int $quantity): void {
        if($quantity > 0 && $quantity <= 5) {
            try {
                $sql = "SELECT id FROM User WHERE privilege = 1";
                $stmt = $this->execute($sql);
                $admins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                $sql = "INSERT INTO Notification (type, user, product, isRead) VALUES (4, ?, ?, 0)";
                foreach ($admins as $admin) {
                    $stmt = $this->execute($sql, [$admin['id'], $productId]);
                    $stmt->close();
                }
            } catch (Exception $e) {
                return;
            }
        }
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