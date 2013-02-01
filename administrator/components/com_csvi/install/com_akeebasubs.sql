DELETE FROM `#__csvi_template_tables` WHERE `component` = 'com_akeebasubs';
INSERT IGNORE INTO `#__csvi_template_tables` (`template_type_name`, `template_table`, `component`) VALUES
('affiliateexport', 'affiliateexport', 'com_akeebasubs'),
('affiliateexport', 'akeebasubs_affiliates', 'com_akeebasubs'),
('affiliateexport', 'akeebasubs_affpayments', 'com_akeebasubs'),
('affiliateexport', 'users', 'com_akeebasubs'),
('affiliateimport', 'affiliateimport', 'com_akeebasubs'),
('affiliateimport', 'akeebasubs_affiliates', 'com_akeebasubs'),
('affiliateimport', 'akeebasubs_affpayments', 'com_akeebasubs'),
('couponexport', 'akeebasubs_coupons', 'com_akeebasubs'),
('couponexport', 'couponexport', 'com_akeebasubs'),
('couponimport', 'akeebasubs_coupons', 'com_akeebasubs'),
('couponimport', 'couponimport', 'com_akeebasubs'),
('subscriptionexport', 'akeebasubs_subscriptions', 'com_akeebasubs'),
('subscriptionexport', 'akeebasubs_users', 'com_akeebasubs'),
('subscriptionexport', 'subscriptionexport', 'com_akeebasubs'),
('subscriptionimport', 'akeebasubs_subscriptions', 'com_akeebasubs'),
('subscriptionimport', 'akeebasubs_users', 'com_akeebasubs'),
('subscriptionimport', 'subscriptionimport', 'com_akeebasubs');

DELETE FROM `#__csvi_template_types` WHERE `component` = 'com_akeebasubs';
INSERT IGNORE INTO `#__csvi_template_types` (`template_type_name`, `template_type`, `component`, `url`, `options`) VALUES
('subscriptionexport', 'export', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=subscriptions', 'file,fields,subscription,layout,email,limit'),
('affiliateexport', 'export', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=affiliates', 'file,fields,layout,email,limit'),
('couponexport', 'export', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=coupons', 'file,fields,layout,email,limit'),
('couponimport', 'import', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=coupons', 'file,fields,limit'),
('subscriptionimport', 'import', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=subscriptions', 'file,fields,limit'),
('affiliateimport', 'import', 'com_akeebasubs', 'index.php?option=com_akeebasubs&view=affiliates', 'file,fields,limit');