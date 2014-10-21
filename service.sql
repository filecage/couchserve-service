-- redefine database names if neccessary
SET @couchServeSchemaName = 'couchServe';
USE couchServe;

-- do not modify anything below
DELIMITER $$

-- compatible until d3d866a0d467880166636a0fa8fd248296e919f4
CREATE TABLE IF NOT EXISTS `environment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `environmentKey` varchar(68) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `type` text,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

CREATE TABLE IF NOT EXISTS `sensors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `type` text,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

-- compatible until 9ec4230b47557815ec685290365fbf5ca17c19f5
CREATE TABLE IF NOT EXISTS `controller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

-- compatible until 399ac369c8b8e744492119580c84b56d74d42226
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$

CREATE PROCEDURE SQL_Update_HEAD()
BEGIN
    IF NOT EXISTS ((SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @couchServeSchemaName AND TABLE_NAME = 'modules' AND COLUMN_NAME = 'groupId'))
        THEN
            ALTER TABLE `modules` ADD COLUMN `groupId` INT(11) NULL AFTER `type` ;
    END IF;

    IF NOT EXISTS ((SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @couchServeSchemaName AND TABLE_NAME = 'sensors' AND COLUMN_NAME = 'groupId'))
        THEN
            ALTER TABLE `sensors` ADD COLUMN `groupId` INT(11) NULL AFTER `type` ;
    END IF;
END $$

CALL SQL_Update_HEAD() $$
DROP PROCEDURE SQL_Update_HEAD $$

-- compatible until HEAD
CREATE TABLE IF NOT EXISTS `sensorValues` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sensorId` INT NOT NULL ,
  `sensorValue` TEXT NULL ,
  `senseTime` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `id,time` (`sensorId` ASC, `senseTime` ASC) ) $$


-- finish!
DELIMITER ;