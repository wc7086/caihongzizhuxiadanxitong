INSERT INTO `pre_config` VALUES ('fenzhan_tixian_alipay', '1');
INSERT INTO `pre_config` VALUES ('fenzhan_tixian_wx', '1');
INSERT INTO `pre_config` VALUES ('fenzhan_tixian_qq', '1');
INSERT INTO `pre_config` VALUES ('fenzhan_kfqq', '1');

ALTER TABLE `pre_site`
ADD COLUMN `kfqq` VARCHAR(12) DEFAULT NULL,
ADD COLUMN `kfwx` VARCHAR(20) DEFAULT NULL;

ALTER TABLE `pre_tixian`
ADD COLUMN `note` text DEFAULT NULL;

UPDATE `pre_site` SET `kfqq`=`qq` WHERE `power`>0 AND `status`=1;