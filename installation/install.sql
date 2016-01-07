CREATE TABLE IF NOT EXISTS `ob_event` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `description` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `localisation` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `date` TIMESTAMP NULL DEFAULT NULL COMMENT '',
  `participants_max` INT(11) NULL DEFAULT NULL COMMENT '',
  `organizer` VARCHAR(255) NOT NULL COMMENT '',
  `organizer_email` VARCHAR(255) NOT NULL COMMENT '',
  `creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
  `open_to_registration` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '',
  `canceled` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ob_participant` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `first_name` VARCHAR(255) NOT NULL COMMENT '',
  `last_name` VARCHAR(255) NOT NULL COMMENT '',
  `email` VARCHAR(255) NOT NULL COMMENT '',
  `password` LONGTEXT NOT NULL COMMENT '',
  `registration_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
  `comments` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `status` ENUM('verified', 'unverified', 'ban') NOT NULL DEFAULT 'unverified' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `unique_participant_email` (`email` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ob_email_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `type` ENUM('remind1day', 'remind7day', 'waiting_list', 'participant_registration', 'participant_annulation', 'event_annulation', 'participant_waiting_list_place_available', 'event_modification') NOT NULL COMMENT '',
  `object` VARCHAR(255) NOT NULL COMMENT '',
  `body` LONGTEXT NOT NULL COMMENT '',
  `last_edit` TIMESTAMP NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `Unique_email_type` (`type` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `ob_participation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `id_participant` INT(11) NOT NULL COMMENT '			',
  `id_event` INT(11) NOT NULL COMMENT '',
  `comments` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `cancelled` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '',
  `registration_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  `present` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_id_participant_ob_participation_idx` (`id_participant` ASC)  COMMENT '',
  INDEX `fk_id_event_ob_participation_idx` (`id_event` ASC)  COMMENT '',
  UNIQUE INDEX `unique_ob_participation` (`id_participant` ASC, `id_event` ASC)  COMMENT '',
  CONSTRAINT `fk_id_participant_ob_participation`
    FOREIGN KEY (`id_participant`)
    REFERENCES `ob_participant` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_id_event_ob_participation`
    FOREIGN KEY (`id_event`)
    REFERENCES `ob_event` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
