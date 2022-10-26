CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `userType` int(5) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `user_type` (`id`, `type`) VALUES
(1, 'elderly'),
(2, 'caretaker'),
(3, 'admin');

CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `detail` TEXT NOT NULL,
  `birthDay` int(5) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` int(1) NOT NULL DEFAULT 1,
  `startWorkDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endWorkDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE `order_transection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `careTakerId` int(11) NOT NULL,
  `hours` int(5) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `status` int(5) NOT NULL,
  `amaId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

CREATE TABLE `order_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(5) NOT NULL,
  `description` varchar(255) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `order_type` (`id`, `status`, `description`) VALUES
(1, 0, 'pending'),
(2, 1, 'active'),
(3, 2, 'complete'),
(4, 9, 'cancel');