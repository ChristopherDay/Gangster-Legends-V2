CREATE TABLE IF NOT EXISTS `premiumMembership` (
  `PM_id` int(11) NOT NULL AUTO_INCREMENT,
  `PM_desc` varchar(255) NOT NULL,
  `PM_seconds` int(11) NOT NULL,
  `PM_cost` int(11) NOT NULL,
  PRIMARY KEY (`PM_id`)
) DEFAULT CHARSET=utf8;