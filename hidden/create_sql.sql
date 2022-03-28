CREATE TABLE `blog_entrys` (
    `blog_entrys_id` INT(10) AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `prev_text` TEXT(500) NOT NULL,
   	`text` MEDIUMTEXT NOT NULL,
    `visible` TINYINT(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  	`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`blog_entrys_id`)
);
CREATE TABLE `blog_images` (
    `blog_images_id` INT(10) AUTO_INCREMENT NOT NULL,
    `blog_entrys_id` INT(10) NOT NULL,
    `source` VARCHAR(255) NOT NULL,
    `alt` TEXT(200),
   	`prev_img` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`blog_images_id`),
    FOREIGN KEY (`blog_entrys_id`) REFERENCES `blog_entrys` (`blog_entrys_id`)
);