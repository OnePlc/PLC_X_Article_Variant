ALTER TABLE `article_variant` ADD `price` FLOAT NOT NULL DEFAULT 0 AFTER `label`,
ADD `web_sort_id` INT(11) NOT NULL DEFAULT '0' AFTER `article_idfs`;