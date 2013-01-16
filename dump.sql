CREATE TABLE IF NOT EXISTS `prefix_magicrule_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `target` varchar(50) NOT NULL,
  `msg` varchar(500) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_block` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_block` (`date_block`),
  KEY `rule_target` (`target`),
  KEY `type` (`type`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;