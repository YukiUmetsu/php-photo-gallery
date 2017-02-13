--
-- Table Structure `users`
--

CREATE TABLE IF NOT EXISTS `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `password` varchar(40) NOT NULL,
 `first_name` varchar(30) NOT NULL,
 `last_name` varchar(30) NOT NULL,
 `email` varchar(255) NOT NULL,
 PRIMARY KEY  (`id`)
) ;


--
-- Table Structure `images`
--

CREATE TABLE IF NOT EXISTS `images` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `filename` varchar(255) NOT NULL,
 `type` varchar(100) NOT NULL,
 `size` int(11) NOT NULL,
 `caption` varchar(255) NOT NULL,
 `user_id` int(11) NOT NULL,
 PRIMARY KEY  (`id`)
) ;


--
-- Table Structure `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `photo_id` int(11) NOT NULL,
 `author` varchar(255) NOT NULL,
 `body` text NOT NULL,
 `created_at` datetime NOT NULL,
 PRIMARY KEY  (`id`)
) ;