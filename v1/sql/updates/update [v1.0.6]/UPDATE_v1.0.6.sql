DROP TABLE `departments`;
ALTER TABLE `settings` DROP `site_url`;
ALTER TABLE `settings` DROP `button_theme`;
ALTER TABLE `settings` DROP `panel_suspended`;
ALTER TABLE `users` DROP `departments`;
ALTER TABLE `users` ADD `first_login` INT(11) NOT NULL DEFAULT '0' COMMENT '0/Yes - 1/No' AFTER `discord`;
ALTER TABLE `users` DROP `email`;