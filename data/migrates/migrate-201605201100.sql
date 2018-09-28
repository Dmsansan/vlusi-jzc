-- ------------------------------------------------------------
-- author:xiao rui
-- date:20160520
-- description:增加评论的字段
-- ------------------------------------------------------------
ALTER TABLE `{pre}comment` ADD `order_id` MEDIUMINT( 8 ) NOT NULL AFTER `user_id`;