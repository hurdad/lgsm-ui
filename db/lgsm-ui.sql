-- MySQL Workbench Synchronization
-- Generated: 2016-06-09 02:45
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Alexander Hurd

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `lgsm-ui` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `virtualboxes_id` INT(11) NOT NULL,
  `script_name` VARCHAR(45) NOT NULL,
  `port` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `virtualboxes_id`),
  INDEX `fk_services_virtualboxes_idx` (`virtualboxes_id` ASC),
  CONSTRAINT `fk_services_virtualboxes`
    FOREIGN KEY (`virtualboxes_id`)
    REFERENCES `lgsm-ui`.`virtualboxes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`games` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(45) NOT NULL,
  `folder_name` VARCHAR(45) NOT NULL,
  `default_script_name` VARCHAR(45) NOT NULL,
  `glibc_version_min` DECIMAL(3,2) NULL DEFAULT NULL,
  `steamworks` TINYINT(1) NOT NULL DEFAULT 1,
  `hidden` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `full_name_UNIQUE` (`full_name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`virtualboxes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `games_id` INT(11) NOT NULL,
  `hostname` VARCHAR(45) NOT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `cpu` INT(11) NOT NULL,
  `memory_mb` FLOAT(11) NOT NULL,
  PRIMARY KEY (`id`, `games_id`),
  INDEX `fk_virtualboxes_games_idx` (`games_id` ASC),
  UNIQUE INDEX `hostname_UNIQUE` (`hostname` ASC),
  CONSTRAINT `fk_virtualboxes_games`
    FOREIGN KEY (`games_id`)
    REFERENCES `lgsm-ui`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `virtualboxes_id` INT(11) NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  `details` LONGTEXT NULL DEFAULT NULL,
  `timestamp` VARCHAR(45) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`, `virtualboxes_id`),
  INDEX `fk_events_virtualboxes_idx` (`virtualboxes_id` ASC),
  CONSTRAINT `fk_events_virtualboxes`
    FOREIGN KEY (`virtualboxes_id`)
    REFERENCES `lgsm-ui`.`virtualboxes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
