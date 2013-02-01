DELETE FROM `#__csvi_template_types` WHERE `component` = 'com_csvi';
INSERT IGNORE INTO `#__csvi_template_types` (`template_type_name`, `template_type`, `component`, `url`, `options`) VALUES
('customexport', 'export', 'com_csvi', '', 'file,fields,layout,email,limit'),
('customimport', 'import', 'com_csvi', '', 'file,fields,limit');