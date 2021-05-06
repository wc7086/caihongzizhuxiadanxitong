ALTER TABLE `pre_kms`
ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT '0';

UPDATE `pre_kms` SET `status`=1 WHERE `usetime` IS NOT NULL;

ALTER TABLE `pre_qiandao`
ADD COLUMN `ip` varchar(50) DEFAULT NULL;

ALTER TABLE `pre_qiandao`
ADD INDEX `ip` (`ip`),
ADD INDEX `date` (`date`);
