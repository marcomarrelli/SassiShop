INSERT INTO Category (name, description) VALUES
('Sassi Decorati', 'Sassi dipinti a mano con disegni creativi'),
('Sassi Personalizzati', 'Sassi con nomi, frasi o messaggi personalizzati'),
('Sassi da Collezione', 'Sassi rari e particolari per collezionisti'),
('Kit per Dipingere Sassi', 'Kit completi per decorare i sassi a casa');

INSERT INTO Size (size) VALUES
('Piccolo'), ('Medio'), ('Grande'), ('Extra Grande');

INSERT INTO Product (name, description, price, category, size) VALUES
('Sasso Sorridente', 'Sasso dipinto con una faccia sorridente', 9.99, 1, 2),
('Sasso Cuore', 'Sasso a forma di cuore con scritta "Amore"', 12.99, 1, 1),
('Sasso Personalizzato "Famiglia"', 'Sasso con i nomi della tua famiglia', 14.99, 2, 3),
('Sasso Raro Cristallino', 'Sasso trasparente con venature uniche', 29.99, 3, 2),
('Kit Dipingi Sassi', 'Kit con colori, pennelli e vernice per decorare sassi', 19.99, 4, 4);

INSERT INTO Privilege (type) VALUES
('Admin'),
('User');

INSERT INTO User (firstName, lastName, email, password, privilege) VALUES
('Mario', 'Rossi', 'mario.rossi@example.com', 'password123', 1),
('Luigi', 'Verdi', 'luigi.verdi@example.com', 'password456', 2),
('Giulia', 'Bianchi', 'giulia.bianchi@example.com', 'password789', 2);

INSERT INTO Post (seller, product, title, description) VALUES
(1, 1, 'Sasso Sorridente - Perfetto per regali!', 'Un sasso che porta allegria con il suo sorriso.'),
(1, 4, 'Sasso Cristallino Raro', 'Un sasso unico per veri collezionisti.'),
(1, 5, 'Kit per Dipingere Sassi', 'Crea i tuoi sassi personalizzati con questo kit completo.');

INSERT INTO Cart (user, product, quantity) VALUES
(2, 1, 3),
(2, 3, 2),
(3, 4, 1);

INSERT INTO Purchase (user, product, quantity, status) VALUES
(2, 1, 2, 'COMPLETED'),
(2, 2, 2, 'COMPLETED'),
(3, 4, 1, 'PENDING');

INSERT INTO Comment (post, user, comment) VALUES
(1, 3, 'Adoro questo sasso sorridente! Perfetto per il mio giardino.'),
(2, 2, 'Il sasso cristallino è davvero unico, lo consiglio!');

INSERT INTO Rating (post, user, rating) VALUES
(1, 3, 5),
(2, 2, 4);

INSERT INTO Wishlist (user, product) VALUES
(2, 1),
(2, 3),
(3, 4);

INSERT INTO NotificationType (name) VALUES
('STOCK_EMPTY'),
('STOCK_REFILL'),
('PURCHASE'),
('POST_COMMENT'),
('POST_RATING');