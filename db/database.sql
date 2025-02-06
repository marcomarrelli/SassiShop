CREATE DATABASE IF NOT EXISTS SassiShop;
USE SassiShop;

CREATE TABLE IF NOT EXISTS Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(127) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS Size (
    id INT AUTO_INCREMENT PRIMARY KEY,
    size VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(127) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    category INT NOT NULL,
    size INT NOT NULL,
    image TEXT DEFAULT "",
    isCollection BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (category) REFERENCES Category(id) ON DELETE RESTRICT,
    FOREIGN KEY (size) REFERENCES Size(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Privilege (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(127) NOT NULL,
    lastName VARCHAR(127) NOT NULL,
    username VARCHAR(127) NOT NULL UNIQUE,
    email VARCHAR(127) NOT NULL,
    password VARCHAR(255) NOT NULL,
    privilege INT NOT NULL,
    UNIQUE KEY email_unique (email),
    FOREIGN KEY (privilege) REFERENCES Privilege(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user INT NOT NULL,
    product INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (product) REFERENCES Product(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user INT NOT NULL,
    product INT NOT NULL,
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (product) REFERENCES Product(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist_item (user, product)
);

CREATE TABLE IF NOT EXISTS Purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT "PENDING",
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS ProductList (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase INT NOT NULL,
    product INT NOT NULL,
    quantity INT NOT NULL,
    productPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (purchase) REFERENCES Purchase(id) ON DELETE RESTRICT,
    FOREIGN KEY (product) REFERENCES Product(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product INT NOT NULL,
    user INT NOT NULL,
    comment TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product) REFERENCES Product(id) ON DELETE CASCADE,
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product INT NOT NULL,
    user INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (product) REFERENCES Product(id) ON DELETE CASCADE,
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rating (product, user)
);

CREATE TABLE IF NOT EXISTS NotificationType (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Notification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type INT NOT NULL,
    user INT,
    product INT,
    purchaseUser INT,
    isRead BOOLEAN DEFAULT FALSE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type) REFERENCES NotificationType(id) ON DELETE RESTRICT,
    FOREIGN KEY (user) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (purchaseUser) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase) REFERENCES Purchase(id) ON DELETE CASCADE
);