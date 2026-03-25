-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema skillshop_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema skillshop_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `skillshop_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `skillshop_db` ;

-- -----------------------------------------------------
-- Table `skillshop_db`.`account_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`account_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`country`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`country` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`city`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`city` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `country_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_city_country1_idx` (`country_id` ASC) VISIBLE,
  CONSTRAINT `fk_city_country1`
    FOREIGN KEY (`country_id`)
    REFERENCES `skillshop_db`.`country` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`address` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `line_1` VARCHAR(100) NOT NULL,
  `line_2` VARCHAR(100) NULL DEFAULT NULL,
  `city_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_address_city1_idx` (`city_id` ASC) VISIBLE,
  CONSTRAINT `fk_address_city1`
    FOREIGN KEY (`city_id`)
    REFERENCES `skillshop_db`.`city` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(60) NOT NULL,
  `lname` VARCHAR(60) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `active_account_type_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_user_account_type1_idx` (`active_account_type_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_account_type1`
    FOREIGN KEY (`active_account_type_id`)
    REFERENCES `skillshop_db`.`account_type` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `seller_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `level` ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL DEFAULT 'Beginner',
  `image_url` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  INDEX `fk_product_user1_idx` (`seller_id` ASC) VISIBLE,
  INDEX `fk_product_category1_idx` (`category_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `skillshop_db`.`category` (`id`),
  CONSTRAINT `fk_product_user1`
    FOREIGN KEY (`seller_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 34
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`cart` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_cart_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_cart_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_cart_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `skillshop_db`.`product` (`id`),
  CONSTRAINT `fk_cart_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `skillshop_db`.`chat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`chat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `from_user_id` INT NOT NULL,
  `to_user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('unseen', 'seen') NOT NULL DEFAULT 'unseen',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_chat_user1_idx` (`from_user_id` ASC) VISIBLE,
  INDEX `fk_chat_user2_idx` (`to_user_id` ASC) VISIBLE,
  CONSTRAINT `fk_chat_user1`
    FOREIGN KEY (`from_user_id`)
    REFERENCES `skillshop_db`.`user` (`id`),
  CONSTRAINT `fk_chat_user2`
    FOREIGN KEY (`to_user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `skillshop_db`.`feedback`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`feedback` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `rating` INT NOT NULL,
  `message` TEXT NOT NULL,
  `is_featured` TINYINT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_feedback_product1_idx` (`product_id` ASC) VISIBLE,
  INDEX `fk_feedback_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_feedback_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `skillshop_db`.`product` (`id`),
  CONSTRAINT `fk_feedback_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 20;


-- -----------------------------------------------------
-- Table `skillshop_db`.`gender`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`gender` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`order` (
  `order_id` VARCHAR(100) NOT NULL,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_status` ENUM('pending', 'in_progress', 'completed', 'cancelled') NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  INDEX `fk_order_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_order_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `skillshop_db`.`product` (`id`),
  CONSTRAINT `fk_order_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`invoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`invoice` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_order_id` VARCHAR(100) CHARACTER SET 'utf8mb3' NOT NULL,
  `user_id` INT NOT NULL,
  `subtotal` DOUBLE NOT NULL,
  `delivery_fee` DOUBLE NOT NULL,
  `total` DOUBLE NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_invoice_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_invoice_order1_idx` (`order_order_id` ASC) VISIBLE,
  CONSTRAINT `fk_invoice_order1`
    FOREIGN KEY (`order_order_id`)
    REFERENCES `skillshop_db`.`order` (`order_id`),
  CONSTRAINT `fk_invoice_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `skillshop_db`.`invoice_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`invoice_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DOUBLE NOT NULL,
  `seller_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_invoice_item_invoice1_idx` (`invoice_id` ASC) VISIBLE,
  INDEX `fk_invoice_item_product1_idx` (`product_id` ASC) VISIBLE,
  INDEX `fk_invoice_item_user1_idx` (`seller_id` ASC) VISIBLE,
  CONSTRAINT `fk_invoice_item_invoice1`
    FOREIGN KEY (`invoice_id`)
    REFERENCES `skillshop_db`.`invoice` (`id`),
  CONSTRAINT `fk_invoice_item_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `skillshop_db`.`product` (`id`),
  CONSTRAINT `fk_invoice_item_user1`
    FOREIGN KEY (`seller_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `skillshop_db`.`password_reset_tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`password_reset_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `expiry` DATETIME NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_password_reset_tokens_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_password_reset_tokens_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`remember_tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`remember_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `expiry` DATETIME NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_remember_tokens_user_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_remember_tokens_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 40
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`user_has_account_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`user_has_account_type` (
  `user_id` INT NOT NULL,
  `account_type_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `account_type_id`),
  INDEX `fk_user_has_account_type_account_type1_idx` (`account_type_id` ASC) VISIBLE,
  INDEX `fk_user_has_account_type_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_has_account_type_account_type1`
    FOREIGN KEY (`account_type_id`)
    REFERENCES `skillshop_db`.`account_type` (`id`),
  CONSTRAINT `fk_user_has_account_type_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`user_profile`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`user_profile` (
  `user_id` INT NOT NULL,
  `avatar_url` LONGTEXT NULL DEFAULT NULL,
  `bio` TEXT NULL DEFAULT NULL,
  `mobile` VARCHAR(50) NULL DEFAULT NULL,
  `gender_id` INT NOT NULL,
  `address_id` INT NOT NULL,
  INDEX `fk_user_profile_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_user_profile_gender1_idx` (`gender_id` ASC) VISIBLE,
  INDEX `fk_user_profile_address1_idx` (`address_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_profile_address1`
    FOREIGN KEY (`address_id`)
    REFERENCES `skillshop_db`.`address` (`id`),
  CONSTRAINT `fk_user_profile_gender1`
    FOREIGN KEY (`gender_id`)
    REFERENCES `skillshop_db`.`gender` (`id`),
  CONSTRAINT `fk_user_profile_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `skillshop_db`.`watchlist`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`watchlist` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_watchlist_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_watchlist_product1_idx` (`product_id` ASC) VISIBLE,
  CONSTRAINT `fk_watchlist_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `skillshop_db`.`product` (`id`),
  CONSTRAINT `fk_watchlist_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `skillshop_db`.`user` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `skillshop_db`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `skillshop_db`.`admin` (
  `email` VARCHAR(100) NOT NULL,
  `fname` VARCHAR(45) NOT NULL,
  `lname` VARCHAR(45) NOT NULL,
  `vcode` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`))
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
