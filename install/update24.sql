ALTER TABLE `pre_site`
ADD COLUMN `wx_openid` VARCHAR(64) DEFAULT NULL;

ALTER TABLE `pre_site`
ADD INDEX `wx_openid` (`wx_openid`);