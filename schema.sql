CREATE TABLE `awesome_api`.`checks` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `amount` FLOAT NOT NULL,
  `gratuity` FLOAT,
  `table_number` INTEGER NOT NULL,
  `restaurant_location` INTEGER NOT NULL,
  `reference` VARCHAR(255) NOT NULL,
  `card_type` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_general_ci`
