CREATE DATABASE projet_php;
CREATE TABLE images(image_id INT PRIMARY KEY AUTO_INCREMENT,url VARCHAR(200) NOT NULL);
CREATE TABLE sports(sport_id INT PRIMARY KEY AUTO_INCREMENT,sport_name VARCHAR(20) NOT NULL);
CREATE TABLE niveaux(niveau_id INT PRIMARY KEY AUTO_INCREMENT,niveau_name VARCHAR NOT NULL(20));
CREATE TABLE users(user_id INT PRIMARY KEY AUTO_INCREMENT,nom VARCHAR(50) NOT NULL,prenom VARCHAR(50) NOT NULL,date_naissance DATE NOT NULL,email VARCHAR(50) NOT NULL,region VARCHAR(50) NOT NULL);
CREATE TABLE inscriptions(inscription_id INT PRIMARY KEY AUTO_INCREMENT,user_id INT NOT NULL,sport_id INT NOT NULL,niveau_id INT NOT NULL);
CREATE TABLE produits(produit_id INT PRIMARY KEY AUTO_INCREMENT,produit_name VARCHAR(50) NOT NULL,produit_prix DECIMAL(10,2) NOT NULL,produit_description VARCHAR(50) NOT NULL,produit_image VARCHAR(200) NOT NULL);

ALTER TABLE inscriptions
ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_sport_id FOREIGN KEY (sport_id) REFERENCES sports(sport_id),
ADD CONSTRAINT fk_niveau_id FOREIGN KEY (niveau_id) REFERENCES niveaux(niveau_id);

INSERT INTO sports(sport_name) VALUES("volleyball"),("rugby"),("tennis");
INSERT INTO niveaux(niveau_name) VALUES("debutant"),("confirme"),("professionnel"),("supporter");
INSERT INTO images(url) VALUES
("https://media.istockphoto.com/id/1473259144/photo/volleyball-ball-and-net-in-voleyball-arena-during-a-match.jpg?s=612x612&w=0&k=20&c=vbn1S1xDKY7XO5lGdR14FCLhHkWXcjoS49j4-Cc1Ukg="),
("https://t3.ftcdn.net/jpg/02/01/15/74/360_F_201157456_QwzTmohTtySWRhZxo6hDWevgn4h6tlCA.jpg"),("https://plus.unsplash.com/premium_photo-1666913667082-c1fecc45275d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dGVubmlzfGVufDB8fDB8fHww"),
("https://images.unsplash.com/photo-1546519638-68e109498ffc?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YmFza2V0fGVufDB8fDB8fHww");
INSERT INTO produits(produit_name,produit_prix,produit_description,produit_image) VALUES
("raquette de tenis",49.99,"marque wilson","https://tadssportinggoods.ca/cdn/shop/files/Screenshot2024-02-02at10-51-12Blade98_16x19_V9TennisRacket.png?v=1706899887"),
("ballon volleyball",49.99,"marque mikasa","https://www.mikasasports.ca//img/product/MGV500_1-Z.jpg"),
("balle de tennis",24.99,"paquet de 3","https://images.radio-canada.ca/v1/ici-premiere/4x3/balles-tennis-wilson-us-open-70877.jpg"),
("filet volleyball",99.99,"vient avec les pieds","https://cdn.rona.ca/webassets/images/332039742_FrontFacingImage_frCA_l.jpg");