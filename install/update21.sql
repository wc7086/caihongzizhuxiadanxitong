ALTER TABLE `pre_tools`
ADD COLUMN `stock` INT DEFAULT NULL;

ALTER TABLE `pre_tools`
ADD COLUMN `addtime` datetime DEFAULT NULL;

DROP TABLE IF EXISTS `pre_apps`;
CREATE TABLE `pre_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT 1,
  `taskid` int(11) unsigned NOT NULL DEFAULT 0,
  `domain` varchar(128) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `package` varchar(128) DEFAULT NULL,
  `android_url` varchar(256) DEFAULT NULL,
  `ios_url` varchar(256) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;