-- ------------------------------------------------------------
-- author:carson wang
-- date:20160426
-- description:增加支付日志的微信信息
-- ------------------------------------------------------------

ALTER TABLE `{pre}pay_log` ADD `openid` VARCHAR(255) NOT NULL AFTER `is_paid`, ADD `transid` VARCHAR(255) NOT NULL AFTER `openid`;
