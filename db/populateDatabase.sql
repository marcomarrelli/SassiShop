INSERT INTO Size (size) VALUES
('Piccolo'),
('Medio'),
('Grande'),
('Molto Grande');

INSERT INTO Privilege (type) VALUES
('Admin'),
('User');

-- NO - Needs Hashing

-- INSERT INTO User (firstName, lastName, username, email, password, privilege) VALUES
-- ('Admin', 'Admin', 'admin', 'admin@sassishop.it', 'admin', 1),
-- ('Pietro', 'Pietra', 'pietro_pietra', 'pietro.pietra@gmail.com', 'Pietrone99', 2),
-- ('Sara', 'Sassi', 'sassolover', 'sara.sassi@yahoo.it', 'S4SS0', 2),
-- ('Giovanni', 'Gioielli', 'giogio_vannielli', 'giovagiojells@gmail.com', 'GioGioVanni', 2),
-- ('Peter', 'Stone', 'peter_stone', 'peter.stone@gmail.com', 'PietraX2', 2);

INSERT INTO Category (name, description) VALUES
('Emozioni', "Rappresenta le tue emozioni!"),
('Animali', "I migliori amici dell'uomo!"),
('Bandiere', "Rappresenta il tuo paese!"),
('Naturali', "Pietra 100% naturale!"),
('Collezioni', "Pietre su Pietre su Pietre su Pietre"),
('Pietre Preziose', "Le pietre più costose e scintillanti!");

INSERT INTO Product (name, description, price, quantity, category, size, image, isCollection) VALUES
('Pietra Ambra', "Fatti conquistare dall'ambra più pura d'Italia! Fidati!",     99.99, 12, 6, 1, '../assets/images/products/ambra.jpg',      FALSE),
('Amiche di Pietra', "Pietre per te e per tutte le tue amiche!",                29.99, 05, 5, 4, '../assets/images/products/amiche.jpg',      TRUE),
('Bandiere di Pietra', "Una pietra per conquistare ogni paese del mondo!",      39.99, 03, 5, 4, '../assets/images/products/bandiere.jpg',    TRUE),
('Pietra Cane', "Woof Woof! Guarda che bel cagnolino!",                         09.99, 17, 2, 2, '../assets/images/products/cane1.jpg',      FALSE),
('Pietra Coniglietto', "Fai saltellare questo bel sasso!",                      07.50, 14, 2, 1, '../assets/images/products/coniglio.jpg',   FALSE),
('Pietra Ermellino', "SASSO! (Non so il verso dell'ermellino...)",              05.00, 11, 2, 1, '../assets/images/products/ermellino.jpg',  FALSE),
('Famiglia di Pietre', "Regalo per i familiari più antipatici? Eccolo!",        29.99, 02, 5, 4, '../assets/images/products/famiglia.jpg',    TRUE),
('Pietra Felice', "Cosa c'è di meglio di una bella pietra sorridente?",         09.99, 08, 1, 1, '../assets/images/products/felice.jpg',     FALSE),
('Pietra Francia', "Vive la France!",                                           00.99, 00, 3, 2, '../assets/images/products/francia.jpg',    FALSE),
('Pietra Gatto', "Miaoooo! Comprami, ne rimarrai di sasso!",                    05.00, 09, 2, 2, '../assets/images/products/gatto.jpg',      FALSE),
('Pietra Germania', "Ich Liebe Sassi",                                          08.99, 07, 3, 3, '../assets/images/products/germania.jpg',   FALSE),
('Pietra Paurosa', "BUUUUUUUUU - Ti osserverò la notte.",                       06.99, 18, 1, 3, '../assets/images/products/horror.jpg',     FALSE),
('Pietra Italia', "FRATELLIIIII DI SAAASSIIIIIIII",                             07.50, 21, 3, 2, '../assets/images/products/italia.jpg',     FALSE),
('Pietra Naturale', "Pietra al 100% composta da sassi.",                        59.99, 01, 4, 4, '../assets/images/products/natura1.jpg',    FALSE),
('Pietra Paura', "Ho tanta paura di non esser comprato...",                     12.99, 09, 1, 1, '../assets/images/products/paura.jpg',      FALSE),
('Pietra Polonia', "Apri la tua vodka con questa pietra!",                      08.00, 07, 3, 2, '../assets/images/products/polonia.jpg',    FALSE),
('Pietra Rabbia', "Se non mi compri... GRRRRRRR!",                              07.99, 14, 1, 2, '../assets/images/products/rabbia.jpg',     FALSE),
('Pietra Rubino', "Non rubare il nostro rubino!",                               99.99, 10, 6, 1, '../assets/images/products/rubino.jpg',     FALSE),
('Pietra Russia', "La NOSTRA pietra!",                                          11.99, 09, 3, 3, '../assets/images/products/russia.jpg',     FALSE),
('Pietra Spagna', "Yo quiero mucho pietra.",                                    13.50, 08, 3, 2, '../assets/images/products/spagna.jpg',     FALSE),
('Pietra Triste', "Se non mi compri rimarrò triste...",                         07.00, 04, 1, 1, '../assets/images/products/triste.jpg',     FALSE),
('Pietra Volpe', "Che verso fanno le pietre?",                                  09.99, 12, 2, 1, '../assets/images/products/volpe.jpg',      FALSE);

-- Populate NotificationType
INSERT INTO NotificationType (name) VALUES
('STOCK_EMPTY'),
('STOCK_REFILL'),
('PURCHASE'),
('LOW_STOCK');