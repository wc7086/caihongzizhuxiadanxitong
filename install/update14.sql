ALTER TABLE `shua_cache`
ADD COLUMN `expire` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `shua_class`
ADD COLUMN `block` text DEFAULT NULL;

ALTER TABLE `shua_class`
ADD COLUMN `blockpay` VARCHAR(80) DEFAULT NULL;

ALTER TABLE `shua_workorder`
ADD COLUMN `picurl` VARCHAR(150) DEFAULT NULL;

ALTER TABLE `shua_site`
ADD COLUMN `rmbtc` decimal(10,2) NOT NULL DEFAULT '0.00';

ALTER TABLE `shua_pay`
ADD COLUMN `api_trade_no` varchar(64) DEFAULT NULL,
ADD COLUMN `blockdj` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `shua_cart`
ADD COLUMN `blockdj` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `shua_tools`
ADD COLUMN `valiserv` varchar(15) DEFAULT NULL;

ALTER TABLE `shua_points`
ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `shua_points`
ADD INDEX action (`action`),
ADD INDEX orderid (`orderid`);

INSERT INTO `shua_config` VALUES ('workorder_type', '业务补单|卡密错误|充值没到账|订单中途改了密码');
INSERT INTO `shua_config` VALUES ('fenzhan_rank', '1');

DROP TABLE IF EXISTS `shua_kms`;
CREATE TABLE `shua_kms` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT 0,
  `km` varchar(255) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` timestamp NULL DEFAULT NULL,
  `usetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `shua_article`;
CREATE TABLE `shua_article`(
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `keywords` VARCHAR(255) DEFAULT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `color` VARCHAR(20) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT 0,
  `top` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `shua_account`;
CREATE TABLE `shua_account`(
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(32) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `permission` TEXT DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `shua_invite`
ADD INDEX `ikey` (`key`);

ALTER TABLE `shua_faka`
ADD INDEX `orderid` (`orderid`);

ALTER TABLE `shua_site`
ADD INDEX `user` (`user`),
ADD INDEX `qq` (`qq`);

ALTER TABLE `shua_message`
ADD INDEX `type` (`type`);