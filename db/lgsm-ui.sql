-- MySQL Workbench Synchronization
-- Generated: 2016-06-21 21:22
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Alexander Hurd

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `lgsm-ui` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `games_id` INT(11) NOT NULL,
  `script_name` VARCHAR(45) NOT NULL,
  `port` INT(11) NOT NULL,
  `query_port` INT(11),
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_services_games_idx` (`games_id` ASC),
  CONSTRAINT `fk_services_games1`
    FOREIGN KEY (`games_id`)
    REFERENCES `lgsm-ui`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`games` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `query_engines_id` INT(11) NOT NULL,
  `full_name` VARCHAR(45) NOT NULL,
  `folder_name` VARCHAR(45) NOT NULL,
  `glibc_version_min` DECIMAL(3,2) NULL DEFAULT NULL,
  `hidden` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `full_name_UNIQUE` (`full_name` ASC),
  INDEX `fk_games_query_engines_idx` (`query_engines_id` ASC),
  UNIQUE INDEX `folder_name_UNIQUE` (`folder_name` ASC),
  CONSTRAINT `fk_games_query_engines`
    FOREIGN KEY (`query_engines_id`)
    REFERENCES `lgsm-ui`.`query_engines` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`virtualboxes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `vbox_soap_endpoints_id` INT(11) NOT NULL,
  `games_id` INT(11) NOT NULL,
  `github_id` INT(11) NOT NULL,
  `deploy_status` VARCHAR(45) NOT NULL,
  `hostname` VARCHAR(45) NULL DEFAULT NULL,
  `ip` VARCHAR(45) NOT NULL,
  `cpu` INT(11) NOT NULL,
  `memory_mb` FLOAT(11) NOT NULL,
  `ssh_username` VARCHAR(45) NULL DEFAULT NULL,
  `ssh_key` LONGTEXT NULL DEFAULT NULL,
  `ssh_password` VARCHAR(45) NULL DEFAULT NULL,
  `ssh_port` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_virtualboxes_games_idx` (`games_id` ASC),
  INDEX `fk_virtualboxes_vbox_soap_endpoints_idx` (`vbox_soap_endpoints_id` ASC),
  INDEX `fk_virtualboxes_github_idx` (`github_id` ASC),
  CONSTRAINT `fk_virtualboxes_games`
    FOREIGN KEY (`games_id`)
    REFERENCES `lgsm-ui`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_virtualboxes_vbox_soap_endpoints`
    FOREIGN KEY (`vbox_soap_endpoints_id`)
    REFERENCES `lgsm-ui`.`vbox_soap_endpoints` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_virtualboxes_github1`
    FOREIGN KEY (`github_id`)
    REFERENCES `lgsm-ui`.`github` (`id`)
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
  PRIMARY KEY (`id`),
  INDEX `fk_events_virtualboxes_idx` (`virtualboxes_id` ASC),
  CONSTRAINT `fk_events_virtualboxes`
    FOREIGN KEY (`virtualboxes_id`)
    REFERENCES `lgsm-ui`.`virtualboxes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`query_engines` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `launch_uri` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`vbox_soap_endpoints` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(100) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `machine_folder` VARCHAR(100) NOT NULL DEFAULT '~/VirtualBox VMs',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `url_UNIQUE` (`url` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`github` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(100) NOT NULL,
  `branch` VARCHAR(45) NOT NULL DEFAULT 'master',
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `use_ssh` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`base_images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `vbox_soap_endpoints_id` INT(11) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `glibc_version` DECIMAL(3,2) NULL DEFAULT NULL,
  `architecture` ENUM('32 bit', '64 bit') NOT NULL,
  `ssh_username` VARCHAR(45) NOT NULL,
  `ssh_key` LONGTEXT NULL DEFAULT NULL,
  `ssh_password` VARCHAR(45) NULL DEFAULT NULL,
  `ssh_port` INT(11) NOT NULL DEFAULT 22,
  PRIMARY KEY (`id`),
  INDEX `fk_base_images_vbox_soap_endpoints_idx` (`vbox_soap_endpoints_id` ASC),
  UNIQUE INDEX `vbox_soap_endpoints_id_nameUNIQUE` (`vbox_soap_endpoints_id` ASC, `name` ASC),
  CONSTRAINT `fk_base_images_vbox_soap_endpoints`
    FOREIGN KEY (`vbox_soap_endpoints_id`)
    REFERENCES `lgsm-ui`.`vbox_soap_endpoints` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`gearman_job_servers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `hostname` VARCHAR(50) NOT NULL,
  `port` SMALLINT(5) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `hostname_port_UNIQUE` (`hostname` ASC, `port` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`gearman_workers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pid` INT(11) NOT NULL,
  `function_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`gearman_functions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `function_name` VARCHAR(45) NOT NULL,
  `worker_count` INT(11) NOT NULL,
  `enabled` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `function_name_UNIQUE` (`function_name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `lgsm-ui`.`assigned_services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `services_id` INT(11) NOT NULL,
  `virtualboxes_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_assigned_services_services_idx` (`services_id` ASC),
  INDEX `fk_assigned_services_virtualboxes_idx` (`virtualboxes_id` ASC),
  UNIQUE INDEX `services_id_virtualboxes_id_UNIQUE` (`services_id` ASC, `virtualboxes_id` ASC),
  CONSTRAINT `fk_assigned_services_services`
    FOREIGN KEY (`services_id`)
    REFERENCES `lgsm-ui`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_assigned_services_virtualboxes`
    FOREIGN KEY (`virtualboxes_id`)
    REFERENCES `lgsm-ui`.`virtualboxes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
