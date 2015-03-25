SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `khmermoo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `khmermoo` ;

-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_image` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(45) NULL,
  `lname` VARCHAR(45) NULL,
  `username` VARCHAR(30) NULL,
  `password` VARCHAR(70) NULL,
  `email` VARCHAR(150) NULL,
  `phone` VARCHAR(45) NULL,
  `address` TEXT NULL,
  `website` VARCHAR(45) NULL,
  `disable` SMALLINT(1) NULL DEFAULT 0,
  `location_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_user_tbl_location1_idx` (`location_id` ASC),
  INDEX `fk_tbl_user_tbl_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_tbl_user_tbl_location1`
    FOREIGN KEY (`location_id`)
    REFERENCES `khmermoo`.`tbl_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_user_tbl_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `khmermoo`.`tbl_role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_cateory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_cateory` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` TEXT NULL,
  `parent_id` INT NOT NULL,
  `disable` SMALLINT(1) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_cateory_tbl_cateory1_idx` (`parent_id` ASC),
  CONSTRAINT `fk_tbl_cateory_tbl_cateory1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `khmermoo`.`tbl_cateory` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL COMMENT 'Buy, Sell',
  `disable` SMALLINT(1) NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_brand`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_brand` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` TEXT NULL,
  `cateory_id` INT NOT NULL,
  `disable` SMALLINT(1) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_brand_tbl_cateory1_idx` (`cateory_id` ASC),
  CONSTRAINT `fk_tbl_brand_tbl_cateory1`
    FOREIGN KEY (`cateory_id`)
    REFERENCES `khmermoo`.`tbl_cateory` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_ads`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_ads` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `title` TINYTEXT NULL,
  `price` FLOAT NULL,
  `view` INT NULL DEFAULT 0,
  `description` TEXT NULL,
  `user_id` BIGINT NOT NULL,
  `cateory_id` INT NOT NULL,
  `type_id` INT NOT NULL,
  `brand_id` INT NOT NULL,
  `disable` SMALLINT(1) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_ads_tbl_user1_idx` (`user_id` ASC),
  INDEX `fk_tbl_ads_tbl_cateory1_idx` (`cateory_id` ASC),
  INDEX `fk_tbl_ads_tbl_type1_idx` (`type_id` ASC),
  INDEX `fk_tbl_ads_tbl_brand1_idx` (`brand_id` ASC),
  CONSTRAINT `fk_tbl_ads_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_ads_tbl_cateory1`
    FOREIGN KEY (`cateory_id`)
    REFERENCES `khmermoo`.`tbl_cateory` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_ads_tbl_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `khmermoo`.`tbl_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_ads_tbl_brand1`
    FOREIGN KEY (`brand_id`)
    REFERENCES `khmermoo`.`tbl_brand` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_ads_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_ads_image` (
  `image_id` BIGINT NOT NULL,
  `ads_id` BIGINT NOT NULL,
  INDEX `fk_tbl_ads_image_tbl_image1_idx` (`image_id` ASC),
  INDEX `fk_tbl_ads_image_tbl_ads1_idx` (`ads_id` ASC),
  CONSTRAINT `fk_tbl_ads_image_tbl_image1`
    FOREIGN KEY (`image_id`)
    REFERENCES `khmermoo`.`tbl_image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_ads_image_tbl_ads1`
    FOREIGN KEY (`ads_id`)
    REFERENCES `khmermoo`.`tbl_ads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`contactor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`contactor` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `phone` VARCHAR(45) NULL,
  `email` VARCHAR(150) NULL,
  `address` TEXT NULL,
  `ads_id` BIGINT NOT NULL,
  `location_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_contactor_tbl_ads1_idx` (`ads_id` ASC),
  INDEX `fk_contactor_tbl_location1_idx` (`location_id` ASC),
  CONSTRAINT `fk_contactor_tbl_ads1`
    FOREIGN KEY (`ads_id`)
    REFERENCES `khmermoo`.`tbl_ads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contactor_tbl_location1`
    FOREIGN KEY (`location_id`)
    REFERENCES `khmermoo`.`tbl_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_ads_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_ads_comment` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `comment` TEXT NULL,
  `user_id` BIGINT NOT NULL,
  `ads_id` BIGINT NOT NULL,
  `created_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  `is_read` SMALLINT(1) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_ads_comment_tbl_user1_idx` (`user_id` ASC),
  INDEX `fk_tbl_ads_comment_tbl_ads1_idx` (`ads_id` ASC),
  CONSTRAINT `fk_tbl_ads_comment_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_ads_comment_tbl_ads1`
    FOREIGN KEY (`ads_id`)
    REFERENCES `khmermoo`.`tbl_ads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_store`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_store` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `picture` TINYTEXT NULL,
  `banner` TINYTEXT NULL,
  `map` TEXT NULL,
  `contact` TEXT NULL,
  `about` TEXT NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` DATETIME NULL,
  `update_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_fanpage_tbl_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_tbl_fanpage_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_login_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_login_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `remote` VARCHAR(20) NULL,
  `ip` VARCHAR(20) NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` DATETIME NULL,
  `ended_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_login_history_tbl_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_tbl_login_history_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_favorite_ads`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_favorite_ads` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `ads_id` BIGINT NOT NULL,
  `created_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_favorite_category_tbl_user1_idx` (`user_id` ASC),
  INDEX `fk_tbl_favorite_category_tbl_ads1_idx` (`ads_id` ASC),
  CONSTRAINT `fk_tbl_favorite_category_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_favorite_category_tbl_ads1`
    FOREIGN KEY (`ads_id`)
    REFERENCES `khmermoo`.`tbl_ads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`tbl_rate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`tbl_rate` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `rate` INT NULL,
  `ads_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbl_user_rate_tbl_ads1_idx` (`ads_id` ASC),
  INDEX `fk_tbl_user_rate_tbl_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_tbl_user_rate_tbl_ads1`
    FOREIGN KEY (`ads_id`)
    REFERENCES `khmermoo`.`tbl_ads` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_user_rate_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `khmermoo`.`tbl_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `khmermoo`.`timestamps`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `khmermoo`.`timestamps` (
  `create_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` TIMESTAMP NULL);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
