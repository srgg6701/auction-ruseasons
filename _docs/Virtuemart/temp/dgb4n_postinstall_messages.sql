--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.2.280.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 20.11.2014 10:02:26
-- Версия сервера: 5.1.70-log
-- Версия клиента: 4.1
--


SET NAMES 'utf8';

INSERT INTO admiralaru_new.dgb4n_postinstall_messages(postinstall_message_id, extension_id, title_key, description_key, action_key, language_extension, language_client_id, type, action_file, action, condition_file, condition_method, version_introduced, enabled) VALUES
(1, 700, 'PLG_TWOFACTORAUTH_TOTP_POSTINSTALL_TITLE', 'PLG_TWOFACTORAUTH_TOTP_POSTINSTALL_BODY', 'PLG_TWOFACTORAUTH_TOTP_POSTINSTALL_ACTION', 'plg_twofactorauth_totp', 1, 'action', 'site://plugins/twofactorauth/totp/postinstall/actions.php', 'twofactorauth_postinstall_action', 'site://plugins/twofactorauth/totp/postinstall/actions.php', 'twofactorauth_postinstall_condition', '3.2.0', 1);
INSERT INTO admiralaru_new.dgb4n_postinstall_messages(postinstall_message_id, extension_id, title_key, description_key, action_key, language_extension, language_client_id, type, action_file, action, condition_file, condition_method, version_introduced, enabled) VALUES
(2, 700, 'COM_CPANEL_MSG_EACCELERATOR_TITLE', 'COM_CPANEL_MSG_EACCELERATOR_BODY', 'COM_CPANEL_MSG_EACCELERATOR_BUTTON', 'com_cpanel', 1, 'action', 'admin://components/com_admin/postinstall/eaccelerator.php', 'admin_postinstall_eaccelerator_action', 'admin://components/com_admin/postinstall/eaccelerator.php', 'admin_postinstall_eaccelerator_condition', '3.2.0', 1);
INSERT INTO admiralaru_new.dgb4n_postinstall_messages(postinstall_message_id, extension_id, title_key, description_key, action_key, language_extension, language_client_id, type, action_file, action, condition_file, condition_method, version_introduced, enabled) VALUES
(3, 700, 'COM_CPANEL_WELCOME_BEGINNERS_TITLE', 'COM_CPANEL_WELCOME_BEGINNERS_MESSAGE', '', 'com_cpanel', 1, 'message', '', '', '', '', '3.2.0', 1);
INSERT INTO admiralaru_new.dgb4n_postinstall_messages(postinstall_message_id, extension_id, title_key, description_key, action_key, language_extension, language_client_id, type, action_file, action, condition_file, condition_method, version_introduced, enabled) VALUES
(4, 700, 'COM_CPANEL_MSG_PHPVERSION_TITLE', 'COM_CPANEL_MSG_PHPVERSION_BODY', '', 'com_cpanel', 1, 'message', '', '', 'admin://components/com_admin/postinstall/phpversion.php', 'admin_postinstall_phpversion_condition', '3.2.2', 1);