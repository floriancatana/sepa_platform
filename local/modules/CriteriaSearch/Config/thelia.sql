
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- criteria_search_category_feature
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `criteria_search_category_feature`;

CREATE TABLE `criteria_search_category_feature`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER,
    `feature_id` INTEGER,
    `searchable` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `FI_criteria_search_category_feature_category_id` (`category_id`),
    INDEX `FI_criteria_search_category_feature_feature_id` (`feature_id`),
    CONSTRAINT `fk_criteria_search_category_feature_category_id`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_criteria_search_category_feature_feature_id`
        FOREIGN KEY (`feature_id`)
        REFERENCES `feature` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- criteria_search_category_attribute
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `criteria_search_category_attribute`;

CREATE TABLE `criteria_search_category_attribute`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER,
    `attribute_id` INTEGER,
    `searchable` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `FI_criteria_search_category_attribute_category_id` (`category_id`),
    INDEX `FI_criteria_search_category_attribute_attribute_id` (`attribute_id`),
    CONSTRAINT `fk_criteria_search_category_attribute_category_id`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_criteria_search_category_attribute_attribute_id`
        FOREIGN KEY (`attribute_id`)
        REFERENCES `attribute` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- criteria_search_category_tax_rule
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `criteria_search_category_tax_rule`;

CREATE TABLE `criteria_search_category_tax_rule`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER,
    `tax_rule_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `FI_criteria_search_category_tax_rule_category_id` (`category_id`),
    INDEX `FI_criteria_search_category_tax_rule_taxe_rule_id` (`tax_rule_id`),
    CONSTRAINT `fk_criteria_search_category_tax_rule_category_id`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `fk_criteria_search_category_tax_rule_taxe_rule_id`
        FOREIGN KEY (`tax_rule_id`)
        REFERENCES `tax_rule` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
