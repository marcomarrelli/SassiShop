CREATE DATABASE IF NOT EXISTS SassiShop;

USE SassiShop;

CREATE TABLE IF NOT EXISTS Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    name VARCHAR(127) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS Sizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    size VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(127) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category INT NOT NULL,
    size INT NOT NULL,

    FOREIGN KEY (category) REFERENCES Categories(id),
    FOREIGN KEY (size) REFERENCES Sizes(id)
);

CREATE TABLE IF NOT EXISTS ProductImages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    product INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (product) REFERENCES Product(id),
    CONSTRAINT maxImages CHECK (
        (SELECT COUNT(*) FROM ProductImages WHERE product = Product.id) <= 5
    )
);

CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    firstName VARCHAR(127) NOT NULL,
    lastName VARCHAR(127) NOT NULL,
    email VARCHAR(127) UNIQUE NOT NULL,
    password VARCHAR(127) NOT NULL,
    privilege INT NOT NULL,
    
    FOREIGN KEY (privilege) REFERENCES Privileges(id)
);

CREATE TABLE IF NOT EXISTS Privileges (
    id INT AUTO_INCREMENT PRIMARY KEY,

    type VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    seller INT NOT NULL,
    product INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (seller) REFERENCES Users(id),
    FOREIGN KEY (product) REFERENCES Product(id)
);

CREATE TABLE IF NOT EXISTS Cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    user INT NOT NULL,
    product INT NOT NULL,
    quantity INT DEFAULT 1,
    
    FOREIGN KEY (user) REFERENCES Users(id),
    FOREIGN KEY (product) REFERENCES Product(id)
);

CREATE TABLE IF NOT EXISTS Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    user INT NOT NULL,
    product INT NOT NULL,
    quantity INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user) REFERENCES Users(id),
    FOREIGN KEY (product) REFERENCES Product(id)
);

CREATE TABLE IF NOT EXISTS Comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    post INT NOT NULL,
    user INT NOT NULL,
    comment TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (post) REFERENCES Posts(id),
    FOREIGN KEY (user) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS Ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    post INT NOT NULL,
    user INT NOT NULL,
    rating INT NOT NULL,
    
    FOREIGN KEY (post) REFERENCES Posts(id),
    FOREIGN KEY (user) REFERENCES Users(id)
);