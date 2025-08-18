CREATE TABLE `message` 
(
  `id` INT NOT NULL AUTO_INCREMENT ,
  `send_date` DATE DEFAULT NULL,
  `send_time` TIME DEFAULT NULL,
  `phone` VARCHAR(25),
  `status` INT(2) DEFAULT 1,
  `request_id` VARCHAR(400),
  `clinic_id` INT,
  `user_id` INT,
  `sample_id` INT,
  `data_request` TEXT DEFAULT NULL COMMENT 'json объект',
  `data_response` TEXT DEFAULT NULL COMMENT 'json объект',
  `update` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cdate` DATETIME DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
  ) 
ENGINE = InnoDB;