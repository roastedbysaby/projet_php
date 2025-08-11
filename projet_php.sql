CREATE DATABASE projet_php;
CREATE TABLE images(image_id INT PRIMARY KEY AUTO_INCREMENT,url VARCHAR(200) NOT NULL);
CREATE TABLE sports(sport_id INT PRIMARY KEY AUTO_INCREMENT,sport_name VARCHAR(20) NOT NULL);
CREATE TABLE niveaux(niveau_id INT PRIMARY KEY AUTO_INCREMENT,niveau_name VARCHAR NOT NULL(20));
CREATE TABLE users(user_id INT PRIMARY KEY AUTO_INCREMENT,nom VARCHAR(50) NOT NULL,prenom VARCHAR(50) NOT NULL,date_naissance DATE NOT NULL,email VARCHAR(50) NOT NULL,region VARCHAR(50) NOT NULL);
CREATE TABLE inscriptions(inscription_id INT PRIMARY KEY AUTO_INCREMENT,user_id INT NOT NULL,sport_id INT NOT NULL,niveau_id INT NOT NULL);

ALTER TABLE inscriptions
ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_sport_id FOREIGN KEY (sport_id) REFERENCES sports(sport_id),
ADD CONSTRAINT fk_niveau_id FOREIGN KEY (niveau_id) REFERENCES niveaux(niveau_id);

INSERT INTO sports(sport_name) VALUES("volleyball"),("rugby"),("tennis");
INSERT INTO niveaux(niveau_name) VALUES("debutant"),("confirme"),("professionnel"),("supporter");
INSERT INTO images(url) VALUES("https://media.istockphoto.com/id/1473259144/photo/volleyball-ball-and-net-in-voleyball-arena-during-a-match.jpg?s=612x612&w=0&k=20&c=vbn1S1xDKY7XO5lGdR14FCLhHkWXcjoS49j4-Cc1Ukg="),("https://t3.ftcdn.net/jpg/02/01/15/74/360_F_201157456_QwzTmohTtySWRhZxo6hDWevgn4h6tlCA.jpg"),("https://plus.unsplash.com/premium_photo-1666913667082-c1fecc45275d?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dGVubmlzfGVufDB8fDB8fHww"),("https://images.unsplash.com/photo-1546519638-68e109498ffc?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YmFza2V0fGVufDB8fDB8fHww");