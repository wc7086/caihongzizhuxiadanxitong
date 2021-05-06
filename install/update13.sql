ALTER TABLE `shua_site`
ADD COLUMN `iprice` text DEFAULT NULL;

INSERT INTO `shua_config` VALUES ('captcha_open_free', '1');
INSERT INTO `shua_config` VALUES ('captcha_open_reg', '1');