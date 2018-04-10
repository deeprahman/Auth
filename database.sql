/*Create a User named name*/
CREATE USER 'goro'@'localhost';

/*Grant purmission*/

GRANT ALL ON register.* TO goro@localhost;

/*Assgn Password 123456*/

UPDATE mysql.user SET authentication_string=password(123456) WHERE User='goro';

/* Create a database named 'register'.*/

CREATE DATABASE register;

/* Create  a table nemed 'users' in the 'register' database */

CREATE TABLE users(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(100),
    join_date TIMESTAMP
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;


-- Flush Privileges
FLUSH PRIVILEGES;