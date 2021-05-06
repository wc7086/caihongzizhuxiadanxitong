ALTER TABLE `pre_orders`
ADD COLUMN `uptime` int DEFAULT NULL;
ALTER TABLE `pre_orders`
ADD COLUMN `cost` decimal(10,2) NOT NULL DEFAULT '0.00';

ALTER TABLE `pre_site`
ADD COLUMN `email` VARCHAR(64) DEFAULT NULL;
ALTER TABLE `pre_site`
ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL;
ALTER TABLE `pre_site`
ADD COLUMN `appurl` varchar(150) DEFAULT NULL;

DROP TABLE IF EXISTS `pre_sendcode`;
CREATE TABLE `pre_sendcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0邮箱 1手机',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0注册 1找回 2改绑',
  `code` varchar(32) NOT NULL,
  `to` varchar(32) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY code (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `pre_config` VALUES ('updatestatus', '0');
INSERT INTO `pre_config` VALUES ('updatestatus_interval', '6');
INSERT INTO `pre_config` VALUES ('fenzhan_pricelimit', '1');