CREATE TABLE `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nachname` varchar(255) NOT NULL,
  `vorname` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `perm_login` tinyint(1) NOT NULL DEFAULT 0,
  `perm_event` tinyint(1) NOT NULL DEFAULT 0,
  `perm_blog` tinyint(1) NOT NULL DEFAULT 0,
  `perm_fest` tinyint(1) NOT NULL DEFAULT 0,
  `perm_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
);

CREATE TABLE `securitytokens` (
  `securitytoken_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `securitytoken` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`securitytoken_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
);

CREATE TABLE `blog_entrys` (
  `blog_entrys_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `prev_text` text NOT NULL,
  `text` mediumtext NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 0,
  `views` double NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(10) NOT NULL,
	PRIMARY KEY (`blog_entrys_id`),
  FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
);

CREATE TABLE `blog_images` (
  `blog_images_id` int(10) NOT NULL AUTO_INCREMENT,
  `blog_entrys_id` int(10) NOT NULL,
  `source` varchar(255) NOT NULL,
  `alt` text DEFAULT NULL,
  `owner` text DEFAULT NULL,
  `prev_img` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`blog_images_id`),
  FOREIGN KEY (`blog_entrys_id`) REFERENCES `blog_entrys` (`blog_entrys_id`)
);

CREATE TABLE `fest` (
  `fest_id` int(10) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `url` varchar(255),
  `name` varchar(255) NOT NULL,
  `fest_text` text DEFAULT NULL ,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(10) NOT NULL,
	PRIMARY KEY (`fest_id`),
  FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
);

CREATE TABLE `fest_food_cat` (
  `fest_food_cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
	PRIMARY KEY (`fest_food_cat_id`)
);

CREATE TABLE `fest_food` (
  `fest_food_id` int(10) NOT NULL AUTO_INCREMENT,
  `fest_id` int(10) NOT NULL,
  `fest_food_cat_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `liters` varchar(32),
  `price` varchar(32) NOT NULL,
  `text` mediumtext NOT NULL,
  `img_path` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(10) NOT NULL,
	PRIMARY KEY (`fest_food_id`),
  FOREIGN KEY (`fest_food_cat_id`) REFERENCES `fest_food_cat` (`fest_food_cat_id`)
  FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  FOREIGN KEY (`fest_id`) REFERENCES `fest` (`fest_id`)
);

-- Termine
CREATE TABLE `events` (
    `events_id` INT(10) NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `title` varchar(255) NOT NULL,
    `text` mediumtext NOT NULL,
    `visible` tinyint(1) NOT NULL DEFAULT 0,
    `views` double NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `created_by` INT(10) NOT NULL,
    PRIMARY KEY (`events_id`),
    FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
);