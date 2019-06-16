--
-- Database cmd
--
CREATE DATABASE cmd CHARACTER SET utf8 COLLATE utf8_general_ci;
USE cmd;
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmd`.`users` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `username` VARCHAR(255) NOT NULL , 
    `fullname` VARCHAR(255) NOT NULL , 
    `email` VARCHAR(255) NOT NULL , 
    `password` VARCHAR(50) NOT NULL , 
    PRIMARY KEY (`id`), UNIQUE (`username`)
) ENGINE = InnoDB;
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmd`.`categories` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(255) NOT NULL , 
    `description` VARCHAR(255) NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS `cmd`.`items` ( 
    `id` INT(11) NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(255) NOT NULL , 
    `description` VARCHAR(255) NOT NULL ,
    `price` SMALLINT NOT NULL,  
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
