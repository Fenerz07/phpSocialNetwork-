CREATE TABLE `article_likes` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`article_id` INT(10) NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `unique_like` (`user_id`, `article_id`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=38
;
