-- ------------------------------------------------------------
-- author:xiao rui
-- date:20160629
-- description:新增自由组合插件的数据表
-- ------------------------------------------------------------
ALTER TABLE `{pre}group_goods` ADD `group_id` tinyint(3) UNSIGNED NOT NULL AFTER `admin_id`;

ALTER TABLE `{pre}cart` ADD `group_id` varchar(255) NOT NULL AFTER `goods_attr_id`;

CREATE TABLE '{pre}cart_combo' like '{pre}cart';