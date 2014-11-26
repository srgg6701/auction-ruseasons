--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.2.280.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 20.11.2014 9:59:30
-- Версия сервера: 5.1.70-log
-- Версия клиента: 4.1
--


SET NAMES 'utf8';

INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(1, 0, 0, 1913, 0, 'root.1', 'Root Asset', '{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.login.offline":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(2, 1, 1, 2, 1, 'com_admin', 'com_admin', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(3, 1, 3, 6, 1, 'com_banners', 'com_banners', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(4, 1, 7, 8, 1, 'com_cache', 'com_cache', '{"core.admin":{"7":1},"core.manage":{"7":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(5, 1, 9, 10, 1, 'com_checkin', 'com_checkin', '{"core.admin":{"7":1},"core.manage":{"7":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(6, 1, 11, 12, 1, 'com_config', 'com_config', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(7, 1, 13, 16, 1, 'com_contact', 'com_contact', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(8, 1, 17, 1786, 1, 'com_content', 'com_content', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1},"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(9, 1, 1787, 1788, 1, 'com_cpanel', 'com_cpanel', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(10, 1, 1789, 1790, 1, 'com_installer', 'com_installer', '{"core.admin":[],"core.manage":{"7":0},"core.delete":{"7":0},"core.edit.state":{"7":0}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(11, 1, 1791, 1792, 1, 'com_languages', 'com_languages', '{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(12, 1, 1793, 1794, 1, 'com_login', 'com_login', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(13, 1, 1795, 1796, 1, 'com_mailto', 'com_mailto', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(14, 1, 1797, 1798, 1, 'com_massmail', 'com_massmail', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(15, 1, 1799, 1800, 1, 'com_media', 'com_media', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":{"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(16, 1, 1801, 1802, 1, 'com_menus', 'com_menus', '{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(17, 1, 1803, 1804, 1, 'com_messages', 'com_messages', '{"core.admin":{"7":1},"core.manage":{"7":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(18, 1, 1805, 1872, 1, 'com_modules', 'com_modules', '{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(19, 1, 1873, 1876, 1, 'com_newsfeeds', 'com_newsfeeds', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(20, 1, 1877, 1878, 1, 'com_plugins', 'com_plugins', '{"core.admin":{"7":1},"core.manage":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(21, 1, 1879, 1880, 1, 'com_redirect', 'com_redirect', '{"core.admin":{"7":1},"core.manage":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(22, 1, 1881, 1882, 1, 'com_search', 'com_search', '{"core.admin":{"7":1},"core.manage":{"6":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(23, 1, 1883, 1884, 1, 'com_templates', 'com_templates', '{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(24, 1, 1885, 1888, 1, 'com_users', 'com_users', '{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(25, 1, 1889, 1892, 1, 'com_weblinks', 'com_weblinks', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1},"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(26, 1, 1893, 1894, 1, 'com_wrapper', 'com_wrapper', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(27, 8, 18, 21, 2, 'com_content.category.2', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(28, 3, 4, 5, 2, 'com_banners.category.3', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(29, 7, 14, 15, 2, 'com_contact.category.4', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(30, 19, 1874, 1875, 2, 'com_newsfeeds.category.5', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(31, 25, 1890, 1891, 2, 'com_weblinks.category.6', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(32, 24, 1886, 1887, 1, 'com_users.category.7', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(33, 1, 1895, 1896, 1, 'com_finder', 'com_finder', '{"core.admin":{"7":1},"core.manage":{"6":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(34, 1, 1897, 1898, 1, 'com_joomlaupdate', 'com_joomlaupdate', '{"core.admin":[],"core.manage":[],"core.delete":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(35, 1, 1899, 1900, 1, 'com_tags', 'com_tags', '{"core.admin":[],"core.manage":[],"core.manage":[],"core.delete":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(36, 1, 1901, 1902, 1, 'com_contenthistory', 'com_contenthistory', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(37, 1, 1903, 1904, 1, 'com_ajax', 'com_ajax', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(38, 1, 1905, 1906, 1, 'com_postinstall', 'com_postinstall', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(39, 18, 1806, 1807, 2, 'com_modules.module.1', 'Main Menu', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(40, 18, 1808, 1809, 2, 'com_modules.module.2', 'Login', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(41, 18, 1810, 1811, 2, 'com_modules.module.3', 'Popular Articles', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(42, 18, 1812, 1813, 2, 'com_modules.module.4', 'Recently Added Articles', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(43, 18, 1814, 1815, 2, 'com_modules.module.8', 'Toolbar', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(44, 18, 1816, 1817, 2, 'com_modules.module.9', 'Quick Icons', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(45, 18, 1818, 1819, 2, 'com_modules.module.10', 'Logged-in Users', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(46, 18, 1820, 1821, 2, 'com_modules.module.12', 'Admin Menu', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(47, 18, 1822, 1823, 2, 'com_modules.module.13', 'Admin Submenu', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(48, 18, 1824, 1825, 2, 'com_modules.module.14', 'User Status', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(49, 18, 1826, 1827, 2, 'com_modules.module.15', 'Title', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(50, 18, 1828, 1829, 2, 'com_modules.module.16', 'Login Form', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(51, 18, 1830, 1831, 2, 'com_modules.module.17', 'Хлебные крошки', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(52, 18, 1832, 1833, 2, 'com_modules.module.79', 'Multilanguage status', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(53, 18, 1834, 1835, 2, 'com_modules.module.86', 'Joomla Version', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(54, 1, 1907, 1908, 1, 'com_zoo', 'com_zoo', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(55, 18, 1836, 1837, 2, 'com_modules.module.87', 'ZOO Category', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(56, 18, 1838, 1839, 2, 'com_modules.module.88', 'ZOO Comment', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(57, 18, 1840, 1841, 2, 'com_modules.module.89', 'ZOO Item', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(58, 18, 1842, 1843, 2, 'com_modules.module.90', 'ZOO Quick Icons', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(59, 18, 1844, 1845, 2, 'com_modules.module.91', 'ZOO Tag', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(60, 18, 1846, 1847, 2, 'com_modules.module.92', 'Фильтр', '{"core.delete":[],"core.edit":[],"core.edit.state":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(61, 27, 19, 20, 3, 'com_content.article.1', 'Kia Cerato', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(62, 8, 22, 25, 2, 'com_content.category.8', 'Новости', '{"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(63, 62, 23, 24, 3, 'com_content.article.2', 'Автомобили, в которых продумано всё вплоть до деталей', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(64, 8, 26, 27, 2, 'com_content.category.9', 'Авто с пробегом. Статьи.', '{"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(65, 1, 1909, 1910, 1, 'com_j2xml', 'com_j2xml', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(66, 8, 28, 31, 2, 'com_content.category.10', 'Статьи', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(67, 8, 32, 93, 2, 'com_content.category.11', 'Новости автомира', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(68, 66, 29, 30, 3, 'com_content.category.12', 'Статьи', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(69, 67, 33, 34, 3, 'com_content.article.3', 'Бренд Maybach возвратится на рынок уже в ноябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(70, 67, 35, 36, 3, 'com_content.article.4', 'Автомобили, в которых продумано всё вплоть до деталей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(71, 67, 37, 38, 3, 'com_content.article.5', '«Народные» автомобилиToyota обучатся без помощи других тормозить к 2018 году.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(72, 67, 39, 40, 3, 'com_content.article.6', 'Renault изготовит среднеразмерный пикап  Французский производитель автомобилей сможет изготовить авт', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(73, 67, 41, 42, 3, 'com_content.article.7', 'Российские власти хотят узаконить штраф за езду на «лысой резине»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(74, 67, 43, 44, 3, 'com_content.article.8', 'Автомобили в Приморском крае перейдут на газ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(75, 67, 45, 46, 3, 'com_content.article.9', 'В Российской Федерации началась продажа автомобилей Chery местной сборки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(76, 67, 47, 48, 3, 'com_content.article.10', 'Эксперты: Автомобили становятся настоящим бедствием', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(77, 67, 49, 50, 3, 'com_content.article.11', 'Hyundai Sonata сделали 708-сильным', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(78, 67, 51, 52, 3, 'com_content.article.12', 'Mazda выпустит дизель-гибридную силовую установку в 2016 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(79, 67, 53, 54, 3, 'com_content.article.13', 'Опубликованы первые подробности об эксклюзивном седане Aston Martin Lagonda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(80, 67, 55, 56, 3, 'com_content.article.14', 'Огромный внедорожник Cadillac Escalade получит «горячую» модификацию', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(81, 67, 57, 58, 3, 'com_content.article.15', 'В Тольятти начата официальная сборка седанов Datsun on-DO', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(82, 67, 59, 60, 3, 'com_content.article.16', 'В России введены новые категории водительских прав', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(83, 67, 61, 62, 3, 'com_content.article.17', 'BMW отзывает около 200 тысяч машин', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(84, 67, 63, 64, 3, 'com_content.article.18', 'Автошоу в Токио готовится принять серийную новинку Honda Urban SUV', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(85, 67, 65, 66, 3, 'com_content.article.19', 'Число кредитных автомобилей в России к 2014 году может вырасти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(86, 67, 67, 68, 3, 'com_content.article.20', 'Renault Logan II. Бразильский француз скоро в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(87, 67, 69, 70, 3, 'com_content.article.21', 'Ford EcoSport. Купить авто через год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(88, 67, 71, 72, 3, 'com_content.article.22', 'Продажа ввозимых из Европы иномарок может оказаться нелегальной', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(89, 67, 73, 74, 3, 'com_content.article.23', 'Обновленный Subaru Outback. Старт продаж в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(90, 67, 75, 76, 3, 'com_content.article.24', '40 лет без водительских прав', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(91, 67, 77, 78, 3, 'com_content.article.25', 'ЗИЛ займется созданием новых парадных кабриолетов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(92, 67, 79, 80, 3, 'com_content.article.26', 'Покупка авто с пробегом. Опрос', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(93, 67, 81, 82, 3, 'com_content.article.27', 'Индийские машины дорожают. Теперь 3000$', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(94, 67, 83, 84, 3, 'com_content.article.28', 'На что обращают внимание, покупая автомобили с пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(95, 67, 85, 86, 3, 'com_content.article.29', 'Экологический налог на авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(96, 67, 87, 88, 3, 'com_content.article.30', 'Женщинам в Саудовской Аравии могут разрешить сесть за руль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(97, 67, 89, 90, 3, 'com_content.article.31', 'На российский автопром ляжет бремя утилизационных сборов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(98, 67, 91, 92, 3, 'com_content.article.32', 'BMW 4-Series Coupe. Старт продаж в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(99, 8, 94, 1759, 2, 'com_content.category.13', 'Новости автомира', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"core.edit.own":[]}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(100, 99, 95, 96, 3, 'com_content.article.33', 'В пассажирских поездах появятся вагоны для автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(101, 99, 97, 98, 3, 'com_content.article.34', 'Mazda и Fiat совместно разработают новый родстер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(102, 99, 99, 100, 3, 'com_content.article.35', 'Россияне чаще решают купить подержанный автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(103, 99, 101, 102, 3, 'com_content.article.36', 'Побит рекорд остаточной стоимости подержанного авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(104, 99, 103, 104, 3, 'com_content.article.37', 'Рост продаж новых авто в мире', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(105, 99, 105, 106, 3, 'com_content.article.38', 'Daimler - выпуск Smart Fortwo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(106, 99, 107, 108, 3, 'com_content.article.39', 'Новые правила - удобство для владельцев б/у авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(107, 99, 109, 110, 3, 'com_content.article.40', 'Нововведение - ежегодное прохождение техосмотра в  Евросоюзе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(108, 99, 111, 112, 3, 'com_content.article.41', 'Парковка через SMS', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(109, 99, 113, 114, 3, 'com_content.article.42', 'Освобождение от обязательного техосмотра', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(110, 99, 115, 116, 3, 'com_content.article.43', 'С 1 июля в Москве эвакуировать автомобили нельзя', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(111, 99, 117, 118, 3, 'com_content.article.44', 'Повышенная стоимость за принудительную транспортировку авто в Челябинске', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(112, 99, 119, 120, 3, 'com_content.article.45', 'Обновление деталей для авто "Фольксваген Груп Рус"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(113, 99, 121, 122, 3, 'com_content.article.46', 'Регистрация таксистов – онлайн', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(114, 99, 123, 124, 3, 'com_content.article.47', 'Рост продаж подержанных автомобилей.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(115, 99, 125, 126, 3, 'com_content.article.48', 'Обсуждение Госдумой утилизационного сбора на авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(116, 99, 127, 128, 3, 'com_content.article.49', 'Ужесточение  наказания за нарушение норм тонирования авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(117, 99, 129, 130, 3, 'com_content.article.50', 'Автомобильное Tuning Show в Ереване', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(118, 99, 131, 132, 3, 'com_content.article.51', 'Россия вскоре может стать самым крупным европейским авторынком', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(119, 99, 133, 134, 3, 'com_content.article.52', '«Чёрные ящики» для автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(120, 99, 135, 136, 3, 'com_content.article.53', 'В столице появились очереди на растонировку авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(121, 99, 137, 138, 3, 'com_content.article.54', 'Рынок подержанных авто в Украине парализован', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(122, 99, 139, 140, 3, 'com_content.article.55', 'Платная эвакуация авто в Санкт-Петербурге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(123, 99, 141, 142, 3, 'com_content.article.56', 'Пригнать недорогое авто импортного производства из Белоруссии и Казахстана больше не удастся.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(124, 99, 143, 144, 3, 'com_content.article.57', 'ГИБДД сделала отчёт о росте числа ДТП в РФ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(125, 99, 145, 146, 3, 'com_content.article.58', 'Белгородские дороги признанны самыми безопасными в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(126, 99, 147, 148, 3, 'com_content.article.59', 'Самые угоняемые авто в Чебоксарах - "вазовские" модели.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(127, 99, 149, 150, 3, 'com_content.article.60', 'Прибытие в Казань участников автопробега - Пекин – Москва', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(128, 99, 151, 152, 3, 'com_content.article.61', 'Губернатор Ленобласти: КАД - к 2015 году вокруг Санкт-Петербурга будет стоять в пробке', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(129, 99, 153, 154, 3, 'com_content.article.62', 'Принц Монако - распродажа королевского гаража', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(130, 99, 155, 156, 3, 'com_content.article.63', 'Сокращение штрафов за незначительные нарушения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(131, 99, 157, 158, 3, 'com_content.article.64', 'Штраф для организаторов нелегальных стоянок - 500 000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(132, 99, 159, 160, 3, 'com_content.article.65', 'Замена почасовой оплаты, на посуточную, за пребывание автотранспорта на спецстоянке', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(133, 99, 161, 162, 3, 'com_content.article.66', 'В РФ с первого июля возросли штрафы за нарушение ПДД', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(134, 99, 163, 164, 3, 'com_content.article.67', 'Госдума в связи с новыми штрафами за нарушения ПДД,  может обратиться в Конституционный суд', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(135, 99, 165, 166, 3, 'com_content.article.68', 'В Соединённых Штатах Америки выбирают – «Автомобиль года»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(136, 99, 167, 168, 3, 'com_content.article.69', 'Cadillac Аль Капоне - может уйти с молотка', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(137, 99, 169, 170, 3, 'com_content.article.70', 'В Госдуме предлагается введение тестирования на наркотики при получении водительских прав', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(138, 99, 171, 172, 3, 'com_content.article.71', 'В Госдуме не  готовиться законопроект о дифференцированных штрафах для не трезвых водителей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(139, 99, 173, 174, 3, 'com_content.article.72', 'Автомобильные займы в Украине снова стали недорогими', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(140, 99, 175, 176, 3, 'com_content.article.73', 'Россияне бросились скупать автомобили c пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(141, 99, 177, 178, 3, 'com_content.article.74', 'Проще всего купить авто в Санкт-Петербурге марки Daewoo Matiz и Nexia', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(142, 99, 179, 180, 3, 'com_content.article.75', 'Рейтинг 5 самых продаваемых иномарок в Санкт-Петербурге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(143, 99, 181, 182, 3, 'com_content.article.76', 'General Motors начинает собирать Opel Astra на заводе в Санкт-Петербурге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(144, 99, 183, 184, 3, 'com_content.article.77', 'Honda представила европейскую версию CR-V', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(145, 99, 185, 186, 3, 'com_content.article.78', 'Toyota и Ford остановили заводы в Петербурге и Ленобласти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(146, 99, 187, 188, 3, 'com_content.article.79', 'В Калининграде иномарки подорожают на 100 тысяч', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(147, 99, 189, 190, 3, 'com_content.article.80', 'Стратегии развития и инновации автомобильного рынка', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(148, 99, 191, 192, 3, 'com_content.article.81', 'Следственный комитет России приобретает 5 лимузинов BMW', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(149, 99, 193, 194, 3, 'com_content.article.82', 'За мечтой без очереди', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(150, 99, 195, 196, 3, 'com_content.article.83', '«Тойота» и «Лексус»  - без них работать не охота', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(151, 99, 197, 198, 3, 'com_content.article.84', 'Максимальный покупательский потенциал на середину 2012 года зарегистрирован в Санкт-Петербурге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(152, 99, 199, 200, 3, 'com_content.article.85', 'Chevrolet Captiva собрал много гостей на свой День открытых дверей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(153, 99, 201, 202, 3, 'com_content.article.86', 'В 2015 году Lada Priora возможно будет снята АвтоВАЗом с производства', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(154, 99, 203, 204, 3, 'com_content.article.87', 'Ижавто начал производство Lada Granta', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(155, 99, 205, 206, 3, 'com_content.article.88', 'ФАС России разрешила создать СП Соллерс и Mazda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(156, 99, 207, 208, 3, 'com_content.article.89', 'Автоваз снимает с производства  «Четверку»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(157, 99, 209, 210, 3, 'com_content.article.90', 'Разработана Lada Kalina второго поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(158, 99, 211, 212, 3, 'com_content.article.91', 'Peugeot 301 – седан для вождения в сложных условиях', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(159, 99, 213, 214, 3, 'com_content.article.92', 'Около 30 тыс. легковушек экспортировали из России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(160, 99, 215, 216, 3, 'com_content.article.93', 'Новинка - пятидверный седан Seat Toledo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(161, 99, 217, 218, 3, 'com_content.article.94', 'Peugeot Partner – с двигателем в 1,6 л. и мощностью 115 л.с.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(162, 99, 219, 220, 3, 'com_content.article.95', 'Автомобильный рынок пополниться изумрудными и бронзовыми автомобилями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(163, 99, 221, 222, 3, 'com_content.article.96', 'Audi R8 V10 plus теперь оснащен семиступенчатой КПП', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(164, 99, 223, 224, 3, 'com_content.article.97', 'Самой угоняемой маркой автомобиля в Москве является Mazda 3', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(165, 99, 225, 226, 3, 'com_content.article.98', 'Nissan показал кроссовер Pathfinder нового поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(166, 99, 227, 228, 3, 'com_content.article.99', 'Завод  в Тюрингии готовится к запуску производства автомобиля Opel Adam', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(167, 99, 229, 230, 3, 'com_content.article.100', 'Бренд Chevrolet стал спонсором клуба Manchester United', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(168, 99, 231, 232, 3, 'com_content.article.101', 'Автоваз понизил цены на автомобили Lada Priora с кондиционером', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(169, 99, 233, 234, 3, 'com_content.article.102', 'Автомобиль Audi R8 победил в гонке на выносливость', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(170, 99, 235, 236, 3, 'com_content.article.103', 'Chrysler Group LLC повысил прибыль на 141%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(171, 99, 237, 238, 3, 'com_content.article.104', 'Volvo C30 Electric в КНР - заработал звание «Зеленый автомобиль года»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(172, 99, 239, 240, 3, 'com_content.article.105', 'Завод Fiat в Pomigliana Darco получил награду за «бережливое» производство', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(173, 99, 241, 242, 3, 'com_content.article.106', 'Land Rover – «Автопроизводитель 2012 года»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(174, 99, 243, 244, 3, 'com_content.article.107', 'Toyota готова снова стать лидером мирового автопрома', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(175, 99, 245, 246, 3, 'com_content.article.108', 'Июнь – рекордный месяц по продажам новых иностранных автомобилей в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(176, 99, 247, 248, 3, 'com_content.article.109', 'АКПП оборудованы 41,5% новых автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(177, 99, 249, 250, 3, 'com_content.article.110', 'На ММАС’2012 предвидится 70 российских премьер и  14 мировых', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(178, 99, 251, 252, 3, 'com_content.article.111', 'Журналисты АвтоБизнесРевю провели соревнование на Mazda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(179, 99, 253, 254, 3, 'com_content.article.112', 'Новинка – Seat Toledo на самых передовых двигателях', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(180, 99, 255, 256, 3, 'com_content.article.113', 'Импорт легковых автомобилей в 10 раз превысил их экспорт', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(181, 99, 257, 258, 3, 'com_content.article.114', 'АВТОВАЗ на данный момент лидер по продажам в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(182, 99, 259, 260, 3, 'com_content.article.115', 'Hyundai покажет обновленные версии моделей I30 и I40 «Универсал»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(183, 99, 261, 262, 3, 'com_content.article.116', 'Проверка беспилотных авто Google проходит удачно', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(184, 99, 263, 264, 3, 'com_content.article.117', 'Для обладателей  автомобилей KIA создано приложение для IPhone', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(185, 99, 265, 266, 3, 'com_content.article.118', 'Компания Audi рассказала о ценах на новинку - Audi A3', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(186, 99, 267, 268, 3, 'com_content.article.119', 'Теперь придется сдавать по-новому на водительские права', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(187, 99, 269, 270, 3, 'com_content.article.120', 'Спорткупе GT86 теперь имеет комплектации «ЛЮКС АЭРО» и «ПРЕСТИЖ АЭРО»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(188, 99, 271, 272, 3, 'com_content.article.121', 'Lada Granta с АКП оснастили 30-ю новыми узлами и деталями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(189, 99, 273, 274, 3, 'com_content.article.122', 'Мировая премьера нового Chevrolet Tracker состоится 27 сентября в Париже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(190, 99, 275, 276, 3, 'com_content.article.123', 'Автомобильный партнер московского «Динамо» - Mersedes-Benz»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(191, 99, 277, 278, 3, 'com_content.article.124', 'Компания Hyundai стала официальным спонсором чемпионата мира по крикету', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(192, 99, 279, 280, 3, 'com_content.article.125', 'Таможенные издержки на поставку комплектующих для Автоваза будут сняты', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(193, 99, 281, 282, 3, 'com_content.article.126', 'Opel Adam будет показан на мировой премьере в Париже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(194, 99, 283, 284, 3, 'com_content.article.127', 'Программа утилизации старых машин не будет перезапущена', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(195, 99, 285, 286, 3, 'com_content.article.128', 'Этой осенью бензин возможно подоражает на 2-2,5 рубля в среднем', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(196, 99, 287, 288, 3, 'com_content.article.129', 'Ford Sollers будут производить в Татарстане', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(197, 99, 289, 290, 3, 'com_content.article.130', 'Отечественный Автопром будет поддерживаться правительством вплоть до 2020 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(198, 99, 291, 292, 3, 'com_content.article.131', 'Renault показал новый Megane Hatchback', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(199, 99, 293, 294, 3, 'com_content.article.132', 'Infiniti Emerg-E - лучший концепт-кар German Design Council', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(200, 99, 295, 296, 3, 'com_content.article.133', 'Ford Kuga оснащен багажником, который можно открыть даже ногой', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(201, 99, 297, 298, 3, 'com_content.article.134', 'Ford проводит испытания технологий будущего', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(202, 99, 299, 300, 3, 'com_content.article.135', 'В Москве выделенных полос станет меньше', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(203, 99, 301, 302, 3, 'com_content.article.136', 'Renault Master, Fiat Ducato, Iveco Daily теперь будут собирать на ЗИЛ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(204, 99, 303, 304, 3, 'com_content.article.137', 'В России стартовал автопробег «Сделано в Тольятти-2012»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(205, 99, 305, 306, 3, 'com_content.article.138', 'Компания Chevrolet проводит конкурс видеороликов, на которых запечатлены эмоции футбольных болельщик', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(206, 99, 307, 308, 3, 'com_content.article.139', 'В России недавно стартовал автопробег Владивосток-Москва на автомобилях Kia Sorento', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(207, 99, 309, 310, 3, 'com_content.article.140', 'Гоночный болид Audi R8 e-tron теперь имеет цифровое зеркало заднего вида', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(208, 99, 311, 312, 3, 'com_content.article.141', 'Команда BMW Motorrad Motorsport выступит на этапе WSBK на чемпионате мира', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(209, 99, 313, 314, 3, 'com_content.article.142', 'Возможно, будет пересмотрена степень промилле в крови водителей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(210, 99, 315, 316, 3, 'com_content.article.143', 'АВТОВАЗ начал производить электромобили', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(211, 99, 317, 318, 3, 'com_content.article.144', 'Автомобильный бренд Skoda показала рекордные продажи в июле', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(212, 99, 319, 320, 3, 'com_content.article.145', 'Alma Mater BMW появится на рублевке', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(213, 99, 321, 322, 3, 'com_content.article.146', 'Уникальный экземпляр  Chevrolet Tahoe Khl-Edition будет представлен в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(214, 99, 323, 324, 3, 'com_content.article.147', 'Новый мировой рекорд установил немецкий гонщик на Volkswagen Touareg', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(215, 99, 325, 326, 3, 'com_content.article.148', 'Седан Nissan Almera будут собирать на Автовазе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(216, 99, 327, 328, 3, 'com_content.article.149', 'Цены на новый Chevrolet Malibu уже объявлены', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(217, 99, 329, 330, 3, 'com_content.article.150', 'Концерн GM вложит в российское производство еще 1$ млрд.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(218, 99, 331, 332, 3, 'com_content.article.151', 'АВТОВАЗ для Ставраполья соберет 100 ''Электротакси''', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(219, 99, 333, 334, 3, 'com_content.article.152', 'Opel в следующем году планирует продать не менее 80 тыс. автомобилей в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(220, 99, 335, 336, 3, 'com_content.article.153', 'Volkswagen представил на MMAC-2012 сразу 5 премьер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(221, 99, 337, 338, 3, 'com_content.article.154', 'В память о Кэролле Шелби разработан Ford Shelby GT500 Cobra 2013', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(222, 99, 339, 340, 3, 'com_content.article.155', 'За счет утилизационного сбора компания Toyota повысила цены', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(223, 99, 341, 342, 3, 'com_content.article.156', 'Производство "Ё-мобилей" переносится на конец 2014 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(224, 99, 343, 344, 3, 'com_content.article.157', 'Обновленный Logan будут производить на Автовазе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(225, 99, 345, 346, 3, 'com_content.article.158', 'В скором времени россиянам могут позволить рассекать дороги со скоростью 130 км./ч.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(226, 99, 347, 348, 3, 'com_content.article.159', 'Самый продаваемый автомобиль в мире – новый Ford Focus', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(227, 99, 349, 350, 3, 'com_content.article.160', 'Автопилот для автомобилей разработали российские ученые', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(228, 99, 351, 352, 3, 'com_content.article.161', 'Обновленный Kia Ceed получил 5 звезд за безопасность', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(229, 99, 353, 354, 3, 'com_content.article.162', 'Еще 3 награды получила Kia, в этот раз за дизайн', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(230, 99, 355, 356, 3, 'com_content.article.163', 'Обновленный Opel Convertible теперь будет именоваться Cascada', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(231, 99, 357, 358, 3, 'com_content.article.164', 'Теперь известны цены обновленную модель Hyundai Santa Fe', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(232, 99, 359, 360, 3, 'com_content.article.165', 'Новые модели представленные миру – Husqvarna TR 650 Terra и TR 650 Strada', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(233, 99, 361, 362, 3, 'com_content.article.166', 'Автоваз выпустил Largus-такси', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(234, 99, 363, 364, 3, 'com_content.article.167', 'Автоваз выпустил Largus-такси', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(235, 99, 365, 366, 3, 'com_content.article.168', 'УАЗ освежил Pickup и Patriot', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(236, 99, 367, 368, 3, 'com_content.article.169', 'На рынке России в скором времени появиться новый автопроизводитель', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(237, 99, 369, 370, 3, 'com_content.article.170', 'На дорогах России будут ходить электрические маршрутки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(238, 99, 371, 372, 3, 'com_content.article.171', 'Kia Motors профинансирует научные исследования на 1,81 млрд. евро', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(239, 99, 373, 374, 3, 'com_content.article.172', 'Ровно 50 лет исполняется компактным седанам Opel', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(240, 99, 375, 376, 3, 'com_content.article.173', 'Volkswagen Touareg – лучший внедорожник по итогам московского автосалона', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(241, 99, 377, 378, 3, 'com_content.article.174', 'Audi R8 заработал 2 награды', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(242, 99, 379, 380, 3, 'com_content.article.175', 'Рекордные продажи в России за август принес Audi', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(243, 99, 381, 382, 3, 'com_content.article.176', 'Компания Jaguar вложит порядка 370 млн. фунтов стерлингов в создание нового Range  Rover', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(244, 99, 383, 384, 3, 'com_content.article.177', 'Следующий год на рынке России начнется с появления кроссовера  Infiniti JX', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(245, 99, 385, 386, 3, 'com_content.article.178', 'Ford планирует выпустить бюджетный автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(246, 99, 387, 388, 3, 'com_content.article.179', 'Volkswagen Golf снова в продаже!', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(247, 99, 389, 390, 3, 'com_content.article.180', 'Европейский дебют Infiniti LE Concept состоялся в Париже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(248, 99, 391, 392, 3, 'com_content.article.181', 'В планах у Skoda создание новейшего центра по тестированию двигателей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(249, 99, 393, 394, 3, 'com_content.article.182', 'Киноальманах под названием «Astra, I Love You» выиграл на фестивале  «Киношок-2012»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(250, 99, 395, 396, 3, 'com_content.article.183', 'Chery M11 в раллийной версии прошла боевое крещение', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(251, 99, 397, 398, 3, 'com_content.article.184', 'Новый автомобиль Clio расширит линейку Renault', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(252, 99, 399, 400, 3, 'com_content.article.185', 'На российской территории продан  100-тысячный автомобиль марки Volkswagen Polo седан', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(253, 99, 401, 402, 3, 'com_content.article.186', 'Volvo показал новый V40 Cross Country', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(254, 99, 403, 404, 3, 'com_content.article.187', 'GM и Ford разработают АКПП С 9-ю и более ступенями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(255, 99, 405, 406, 3, 'com_content.article.188', 'За вождение в нетрезвом виде будут лишать прав сроком на 10 лет', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(256, 99, 407, 408, 3, 'com_content.article.189', 'Журнал Forbes назвал BMW влиятельнейшим автомобильным брендом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(257, 99, 409, 410, 3, 'com_content.article.190', 'Автомобильный бренд Fiat показал на автосалоне в Париже 3 новых модели Panda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(258, 99, 411, 412, 3, 'com_content.article.191', 'В Калуге с конвейра сошла 200-тысячная Skoda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(259, 99, 413, 414, 3, 'com_content.article.192', 'Первая партия Volvo V60 Plug-in Hybrid была распродана до появления в салоне', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(260, 99, 415, 416, 3, 'com_content.article.193', 'Концептуальный кроссовер Suzuki сегмента «С» S-Cross', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(261, 99, 417, 418, 3, 'com_content.article.194', 'Компания Honda показала новый Accord 9-го поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(262, 99, 419, 420, 3, 'com_content.article.195', 'Не обыкновенная кампания бренда Mini', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(263, 99, 421, 422, 3, 'com_content.article.196', 'Kia Motors в списке - 100 лучших мировых брендов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(264, 99, 423, 424, 3, 'com_content.article.197', 'В.В.Путин будет ездить на российском лимузине', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(265, 99, 425, 426, 3, 'com_content.article.198', 'Цена бренда Hyundai остановилась на историческом максимуме', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(266, 99, 427, 428, 3, 'com_content.article.199', 'Новый Touareg Editiob X наконец-то показали', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(267, 99, 429, 430, 3, 'com_content.article.200', 'General Motors открывает двери нового центра Информационных технологий', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(268, 99, 431, 432, 3, 'com_content.article.201', 'На АвтоВАЗе машины персонала будут «Просвечивать»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(269, 99, 433, 434, 3, 'com_content.article.202', 'В Париже прошла премьера концепт-кара Peugeot Onyx', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(270, 99, 435, 436, 3, 'com_content.article.203', 'Продажа легковых автомобилей Volkswagen впервые за свою историю превысила 4 млн.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(271, 99, 437, 438, 3, 'com_content.article.204', 'Депутаты внесли предложение о лишении прав на пожизненный срок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(272, 99, 439, 440, 3, 'com_content.article.205', 'За  предоставления услуг такси на неисправных машинах грозит штраф до 500 тыс. руб.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(273, 99, 441, 442, 3, 'com_content.article.206', 'Генеральным директором УАЗа стал нынешний гендиректор «СОЛЛЕРС»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(274, 99, 443, 444, 3, 'com_content.article.207', 'SsangYong-Actyon-obladaet-novymi-optsiyami', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(275, 99, 445, 446, 3, 'com_content.article.208', 'Kia сeed_SW второго поколения появилась в продаже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(276, 99, 447, 448, 3, 'com_content.article.209', 'Домашний кинотеатр появился в модели Cadillac SRX 2013 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(277, 99, 449, 450, 3, 'com_content.article.210', 'Уровень продаж в мире автомобилей Audi в сентябре 2012 года вырос на 13,6%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(278, 99, 451, 452, 3, 'com_content.article.211', 'Продажи BMW Group в сентябре побили все рекорды', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(279, 99, 453, 454, 3, 'com_content.article.212', 'Скоро в Москве пройдет Mersedes-Benz Fashion Week Russia - 25-я неделя моды', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(280, 99, 455, 456, 3, 'com_content.article.213', 'Усовершенствованную коробку передач установили на Lada Granta и Kalina', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(281, 99, 457, 458, 3, 'com_content.article.214', 'LADA Samara - завершается производство вслед за классикой', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(282, 99, 459, 460, 3, 'com_content.article.215', 'Акцию от Hyundai на преобретение хетчбэка и универсала i30', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(283, 99, 461, 462, 3, 'com_content.article.216', 'Toyota Yaris находится в «десятке» европейских бестселлеров', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(284, 99, 463, 464, 3, 'com_content.article.217', 'Subaru Forester tS начал продаваться в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(285, 99, 465, 466, 3, 'com_content.article.218', 'В Москве на Неделе Моды покажут Volvo V40 Cross Country', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(286, 99, 467, 468, 3, 'com_content.article.219', 'АвтоВАЗ будет производить двигатели и коробки передач для Renault и Nissan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(287, 99, 469, 470, 3, 'com_content.article.220', 'В сентябре 2012 года мировые поставки SKODA выросли на 3,3%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(288, 99, 471, 472, 3, 'com_content.article.221', 'Vortex Tingo FL с «робот» трансмиссией появился в продаже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(289, 99, 473, 474, 3, 'com_content.article.222', 'На Toyota Alphard в новых комплектациях начался прием заказов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(290, 99, 475, 476, 3, 'com_content.article.223', 'В январе 2013 года будет представлен Chevrolet Corvette следующего поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(291, 99, 477, 478, 3, 'com_content.article.224', 'Первый автодром международного уровня заработал в Москве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(292, 99, 479, 480, 3, 'com_content.article.225', 'Россияне должны научиться культурно утилизовывать автохлам', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(293, 99, 481, 482, 3, 'com_content.article.226', 'Бензин из воздуха научились делать британские ученые', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(294, 99, 483, 484, 3, 'com_content.article.227', 'Крупнейшим экспортером стала китайская Lifan Industry', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(295, 99, 485, 486, 3, 'com_content.article.228', 'Автомобили марки Volvo будут «оповещать» друг друга о пробках', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(296, 99, 487, 488, 3, 'com_content.article.229', 'Hyundai показала новый кроссовер HB20X', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(297, 99, 489, 490, 3, 'com_content.article.230', 'Автомобиль Honda - самый угоняемой в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(298, 99, 491, 492, 3, 'com_content.article.231', 'На основе LADA Granta разработали полурамный пикап', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(299, 99, 493, 494, 3, 'com_content.article.232', 'Chevrolet покажет на SEMA Show версию Malibu «с зарядом»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(300, 99, 495, 496, 3, 'com_content.article.233', 'Концепт Volkswagen Taigun теперь оснащен новым двигателем TSI', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(301, 99, 497, 498, 3, 'com_content.article.234', 'Комплектации нового Honda CR-V представлены для российского рынка', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(302, 99, 499, 500, 3, 'com_content.article.235', 'Автомобили Honda тоже будут «общаться» с другими автомобилями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(303, 99, 501, 502, 3, 'com_content.article.236', 'Импорт машин с пробегом в России поднялся на 74,9%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(304, 99, 503, 504, 3, 'com_content.article.237', 'Более 57,6 тыс.  подписей собрала ФАР за отмену транспортного налога', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(305, 99, 505, 506, 3, 'com_content.article.238', 'Компания BMW отзывает около 45 тыс. автомобилей 7 серии', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(306, 99, 507, 508, 3, 'com_content.article.239', 'Chevrolet запустил серийную версию седана Sonic Dusk', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(307, 99, 509, 510, 3, 'com_content.article.240', 'Теперь Mazda будет выпускать модель исключительно для России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(308, 99, 511, 512, 3, 'com_content.article.241', 'Maruti Suzuki представила новый Alto 800', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(309, 99, 513, 514, 3, 'com_content.article.242', 'Началась реализация «люксовых» LADA Granta', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(310, 99, 515, 516, 3, 'com_content.article.243', 'Цены на нефтепродукты поднялись на 3,5% в октябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(311, 99, 517, 518, 3, 'com_content.article.244', 'Начались продажи нового Kia Sorento в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(312, 99, 519, 520, 3, 'com_content.article.245', 'Для ликвидации последствий урагана «Сэнди» Chevrolet отдала Красному Кресту свои автомобили', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(313, 99, 521, 522, 3, 'com_content.article.246', 'Будущим владельцам показали сбор автомобиля Kia Rio', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(314, 99, 523, 524, 3, 'com_content.article.247', 'Уже 68% водителей европейской части России поменяли резину на зимнюю', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(315, 99, 525, 526, 3, 'com_content.article.248', 'За 495 тыс. долларов продали Aston Martin Пола Маккартни', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(316, 99, 527, 528, 3, 'com_content.article.249', 'Под игрушечную машинку Hot Wheels стилизовали Chevrolet Camaro', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(317, 99, 529, 530, 3, 'com_content.article.250', 'Определены пункты утилизации автохлама', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(318, 99, 531, 532, 3, 'com_content.article.251', 'Honda сообщила об особенностях нового Accord', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(319, 99, 533, 534, 3, 'com_content.article.252', 'На компактные модели Toyota установит новый бесступенчатый вариатор', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(320, 99, 535, 536, 3, 'com_content.article.253', 'На Азиатско-Тихоокеанском чемпионате по ралли SKODA одержала победу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(321, 99, 537, 538, 3, 'com_content.article.254', 'Opel осваивает рынок Турции', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(322, 99, 539, 540, 3, 'com_content.article.255', 'Стоимость и комплектация универсала Hyundai i40 - теперь известны', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(323, 99, 541, 542, 3, 'com_content.article.256', 'Специальная акция от Mitsubishi на Pajero Sport, ASX и L200', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(324, 99, 543, 544, 3, 'com_content.article.257', 'Комплектация автомобиля Audi Q7 расширена', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(325, 99, 545, 546, 3, 'com_content.article.258', 'Suzuki сменить профиль на продажу мотовездеходов и мотоциклов в США', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(326, 99, 547, 548, 3, 'com_content.article.259', 'Volvo снизила время до 1,5 часов для зарядки аккумуляторов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(327, 99, 549, 550, 3, 'com_content.article.260', 'Неделя качества в АвтоВАЗе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(328, 99, 551, 552, 3, 'com_content.article.261', 'Эксклюзивное партнерство Opel с E.O.F.T заключено', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(329, 99, 553, 554, 3, 'com_content.article.262', 'Продана 100 тыс. LADA Granta в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(330, 99, 555, 556, 3, 'com_content.article.263', 'Медосмотр водителей и порядок его проведения зарегламентирован Минздравом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(331, 99, 557, 558, 3, 'com_content.article.264', 'Ford Ranger получил звание - лучший пикап в мире', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(332, 99, 559, 560, 3, 'com_content.article.265', 'Компании Тойота ровно 75-лет', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(333, 99, 561, 562, 3, 'com_content.article.266', 'Эксперимент с платными парковками за неделю в Москве принес свыше 700 тыс. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(334, 99, 563, 564, 3, 'com_content.article.267', 'Chevrolet дает рекомендации по покупке машины для молодых автолюбителей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(335, 99, 565, 566, 3, 'com_content.article.268', 'Популярнейшая модель автомобиля в России - Lada Kalina', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(336, 99, 567, 568, 3, 'com_content.article.269', 'На территории РФ стартовал прием заказов на новый автомобиль - Toyota Auris', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(337, 99, 569, 570, 3, 'com_content.article.270', 'С 2013 года доверенность на автомобиль станет не нужна', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(338, 99, 571, 572, 3, 'com_content.article.271', 'Компания Toyota отзывает порядка 3 млн. автомобилей на ремонт', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(339, 99, 573, 574, 3, 'com_content.article.272', 'Компания Nissan начнет продавать экономные Datsun посредством своей дилерской сети', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(340, 99, 575, 576, 3, 'com_content.article.273', 'Chevrolet покажет в Лос-Анджелесе Spark с электроприводом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(341, 99, 577, 578, 3, 'com_content.article.274', 'Количество автомобилей к 2035 году достигнет отметки в 1,7 млн. экземпляров', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(342, 99, 579, 580, 3, 'com_content.article.275', 'Бренду Porsche грозит сжатие объемов производства', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(343, 99, 581, 582, 3, 'com_content.article.276', 'Автомобиль LADA Priora скоро преобразится', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(344, 99, 583, 584, 3, 'com_content.article.277', 'Во Всеволожске Руководство Ford остановило конвейер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(345, 99, 585, 586, 3, 'com_content.article.278', 'Ford Focus снабдили инновационной системой для защиты кромок дверей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(346, 99, 587, 588, 3, 'com_content.article.279', 'Продажи в России легковых автомобилей Volkswagen увеличились вдвое', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(347, 99, 589, 590, 3, 'com_content.article.280', 'Украинский Bogdan довольно популярен в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(348, 99, 591, 592, 3, 'com_content.article.281', 'Hyundai Sonata теперь дешевле на 60 тыс. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(349, 99, 593, 594, 3, 'com_content.article.282', '100% покрытие самых крупных российских автотрасс будет обеспечено сотовыми операторами в 2013 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(350, 99, 595, 596, 3, 'com_content.article.283', 'Реализация Alfa Romeo упала до уровня 1969 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(351, 99, 597, 598, 3, 'com_content.article.284', 'Потребители смогут сами подбирать комплектацию для автомобилей Lada', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(352, 99, 599, 600, 3, 'com_content.article.285', 'Свой 40-летний юбилей отмечает симметричный полный привод Subaru', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(353, 99, 601, 602, 3, 'com_content.article.286', 'Renault получит контроль на АвтоВАЗе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(354, 99, 603, 604, 3, 'com_content.article.287', 'BMW изготовит переднеприводные модели', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(355, 99, 605, 606, 3, 'com_content.article.288', 'Dacia дебютирует пикап и универсал Logan в 2013 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(356, 99, 607, 608, 3, 'com_content.article.289', '95-летие отмечают пикапы Chevrolet', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(357, 99, 609, 610, 3, 'com_content.article.290', 'Импорт бензина – вот что вскоре начнет делать Россия', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(358, 99, 611, 612, 3, 'com_content.article.291', 'Самым популярным автомобилем в Европе по-прежнему остается Volkswagen Golf', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(359, 99, 613, 614, 3, 'com_content.article.292', 'С 24 ноября отменена доверенность на управление автомобилем в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(360, 99, 615, 616, 3, 'com_content.article.293', 'Новый хэтчбек от Chrysler', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(361, 99, 617, 618, 3, 'com_content.article.294', 'Цена на бензин по России перескочила 30-рублевую отметку', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(362, 99, 619, 620, 3, 'com_content.article.295', 'За «невидимые» номера автовладелец потеряет права', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(363, 99, 621, 622, 3, 'com_content.article.296', 'Стартовали продажи Renault Duster в России с системой ESP', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(364, 99, 623, 624, 3, 'com_content.article.297', 'В России такси станет «электрическим»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(365, 99, 625, 626, 3, 'com_content.article.298', 'Opel Mokka получил максимальный рейтинг Euro NCAP', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(366, 99, 627, 628, 3, 'com_content.article.299', 'На выставке экстремальной фотографии прошла премьера новой Mazda CX-9', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(367, 99, 629, 630, 3, 'com_content.article.300', 'Свыше 1,5 тыс. внедорожников UAZ получат российские полицейские', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(368, 99, 631, 632, 3, 'com_content.article.301', 'Обновленная версия JAGUAR Land Rover запущена', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(369, 99, 633, 634, 3, 'com_content.article.302', 'Плавучие парковки скоро появятся в Москве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(370, 99, 635, 636, 3, 'com_content.article.303', 'Chevrolet Cobalt будут собирать из российского металла', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(371, 99, 637, 638, 3, 'com_content.article.304', 'В России Hyundai Sonata больше не будет продаваться', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(372, 99, 639, 640, 3, 'com_content.article.305', 'Раскрыт мощнейший седан Jaguar XF', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(373, 99, 641, 642, 3, 'com_content.article.306', 'Вновь обновлена американская версия седана Civic', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(374, 99, 643, 644, 3, 'com_content.article.307', 'Самым стильным автомобилем признан Aston Martin Vanquish', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(375, 99, 645, 646, 3, 'com_content.article.308', 'Теперь автодилеры будут проводить технические осмотры', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(376, 99, 647, 648, 3, 'com_content.article.309', 'Новый Volkswagen Golf завоевал пять звезд от Euro NCAP за безопасность', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(377, 99, 649, 650, 3, 'com_content.article.310', 'Четыре новые модели выпустит Porsche', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(378, 99, 651, 652, 3, 'com_content.article.311', 'Седан Volkswagen Polo получил дополнительное оборудование', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(379, 99, 653, 654, 3, 'com_content.article.312', 'Компания Audi переходит на сервисные книжки в электронном виде', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(380, 99, 655, 656, 3, 'com_content.article.313', 'Audi представила новую RS6', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(381, 99, 657, 658, 3, 'com_content.article.314', 'По-прежнему автомобили "Жигули" самые популярные у угонщиков', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(382, 99, 659, 660, 3, 'com_content.article.315', 'В Болонье на 37-м Международном мотор-шоу Opel представил ADAM', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(383, 99, 661, 662, 3, 'com_content.article.316', 'Очереди на обновленные Lada растянулись на 6 месяцев вперед', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(384, 99, 663, 664, 3, 'com_content.article.317', 'BMW отзывает 250 тыс. кроссоверов X5', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(385, 99, 665, 666, 3, 'com_content.article.318', 'Гран-при журнала За рулем получила LADA Granta', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(386, 99, 667, 668, 3, 'com_content.article.319', 'Двукратный победитель Гран-при «За рулем» - Chevrolet', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(387, 99, 669, 670, 3, 'com_content.article.320', 'Завод Ford в России с 17 декабря по 8 января остановит конвейер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(388, 99, 671, 672, 3, 'com_content.article.321', 'Премьера длиннобазного Santa Fe от Hyundai', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(389, 99, 673, 674, 3, 'com_content.article.322', 'Toyota теперь будет сертифицировать и авто с пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(390, 99, 675, 676, 3, 'com_content.article.323', 'Госдума повысит штраф за съемную тонировку', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(391, 99, 677, 678, 3, 'com_content.article.324', 'Немцы предпочитают больше Lada, чем Cadillac', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(392, 99, 679, 680, 3, 'com_content.article.325', 'Kia pro_ceed был награжден премией iF Design Award', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(393, 99, 681, 682, 3, 'com_content.article.326', 'Первый камень в основание завода Volkswagen заложен Медведевым', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(394, 99, 683, 684, 3, 'com_content.article.327', 'LADA Granta будет поставляться в Европу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(395, 99, 685, 686, 3, 'com_content.article.328', 'На АвтоВАЗе будут собирать автомобили Datsun', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(396, 99, 687, 688, 3, 'com_content.article.329', 'В регионах водители предпочитают «механику», а в Москве - «автомат»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(397, 99, 689, 690, 3, 'com_content.article.330', 'Бестселлером в России стал Mitsubishi Outlander', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(398, 99, 691, 692, 3, 'com_content.article.331', 'Skoda по ошибке раскрыла новую модель Octavia RS', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(399, 99, 693, 694, 3, 'com_content.article.332', 'Автомобильный бренд Lexus опередил люксовых производителей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(400, 99, 695, 696, 3, 'com_content.article.333', 'С 1 января Opel повышает цены на все модели', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(401, 99, 697, 698, 3, 'com_content.article.334', 'Boeing и BMW Group будут сотрудничать', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(402, 99, 699, 700, 3, 'com_content.article.335', 'Самая крупная дилерская сеть в России насчитывает 435 центров', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(403, 99, 701, 702, 3, 'com_content.article.336', 'Сколько будет стоить Cruze универсал, объявила Chevrolet', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(404, 99, 703, 704, 3, 'com_content.article.337', 'Премьер-министр внес законопроект в Госдуму об ОСАГО', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(405, 99, 705, 706, 3, 'com_content.article.338', 'Двигатели от компании Honda признаны лучшими', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(406, 99, 707, 708, 3, 'com_content.article.339', 'В Москве будут заглушать сигнал приемников для передачи сообщений о заторах', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(407, 99, 709, 710, 3, 'com_content.article.340', 'Компания Bosch собирается строить в России еще один завод', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(408, 99, 711, 712, 3, 'com_content.article.341', 'Определились финалисты конкурса "Автомобиль года" в Европе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(409, 99, 713, 714, 3, 'com_content.article.342', 'Теперь грозит штраф за первый выезд на «встречку»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(410, 99, 715, 716, 3, 'com_content.article.343', 'За рассказ о взятках автолюбителям подарят России видеорегистратор', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(411, 99, 717, 718, 3, 'com_content.article.344', 'PSA и GM разработают три совместные платформы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(412, 99, 719, 720, 3, 'com_content.article.345', 'Российские автолюбители отдают предпочтение седанам', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(413, 99, 721, 722, 3, 'com_content.article.346', 'Рядом с Автовазом будут выпускать комплектующие для Toyota, Ford и Mitsubishi', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(414, 99, 723, 724, 3, 'com_content.article.347', '15 гибридов от Nissan – планы на будущее четыре года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(415, 99, 725, 726, 3, 'com_content.article.348', 'При установлении дорожных видеокамер разворовано 25 млн. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(416, 99, 727, 728, 3, 'com_content.article.349', 'Автомобили Nissan и Renault будет выпускать на ИжАвто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(417, 99, 729, 730, 3, 'com_content.article.350', 'Масштабный тест-драйв Peugeot 408 Road Show завершился', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(418, 99, 731, 732, 3, 'com_content.article.351', 'Глобальную награду «Лучший Nissan 2012 года по качеству выпускаемой продукции» получил  завод Nissan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(419, 99, 733, 734, 3, 'com_content.article.352', 'Компания General Motors отзывает порядка 145 тыс. автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(420, 99, 735, 736, 3, 'com_content.article.353', '50% автосалонов России сосредоточено в 11 регионах', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(421, 99, 737, 738, 3, 'com_content.article.354', 'Suzuki скоро выведет бюджетный бренд Maruti на мировые рынки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(422, 99, 739, 740, 3, 'com_content.article.355', 'КамАЗ остановит производство на новогодние праздники', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(423, 99, 741, 742, 3, 'com_content.article.356', 'Камеры проследят за нарушениями автомобилистов на переездах', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(424, 99, 743, 744, 3, 'com_content.article.357', 'Цены на бензин за ноябрь 2012 года в России выросли на 0,5%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(425, 99, 745, 746, 3, 'com_content.article.358', 'Geely выпустит кроссовер похожий на Range Rover Evoque', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(426, 99, 747, 748, 3, 'com_content.article.359', 'На рынок России выходит бюджетный седан Citroen C-Elysée', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(427, 99, 749, 750, 3, 'com_content.article.360', 'На АвтоВАЗе соберут более 1 тыс. Nissan Almera в январе 2013', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(428, 99, 751, 752, 3, 'com_content.article.361', 'На КАМАЗе будут собирать бронеавтомобили Iveco', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(429, 99, 753, 754, 3, 'com_content.article.362', 'Ericsson и Volvo совместно разрабатывают глобальные беспроводные сервисы, применяемые в автомобилях', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(430, 99, 755, 756, 3, 'com_content.article.363', 'Новые стандарты обработки алюминия разработает BMW Group', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(431, 99, 757, 758, 3, 'com_content.article.364', 'Последний LFA от Toyota сошел с конвейера', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(432, 99, 759, 760, 3, 'com_content.article.365', 'LADA Granta сделают безопаснее и комфортнее', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(433, 99, 761, 762, 3, 'com_content.article.366', 'Mercedes-Benz обзавелся новым полным приводом 4MATIC', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(434, 99, 763, 764, 3, 'com_content.article.367', 'Автомобильный бизнес ищет закупщиков', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(435, 99, 765, 766, 3, 'com_content.article.368', 'Депутаты предлагают разрешить для суда записи видеорегистраторов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(436, 99, 767, 768, 3, 'com_content.article.369', 'Opel продал GM шесть дочек, для погашения кредита', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(437, 99, 769, 770, 3, 'com_content.article.370', 'Минпромторг оценил бренд Lada свыше $1 млрд. долларов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(438, 99, 771, 772, 3, 'com_content.article.371', 'LADA Granta теперь оснастят 106-сильным (78 кВт) 16-клапанным мотором', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(439, 99, 773, 774, 3, 'com_content.article.372', 'АвтоВаз откажется от модели седана LADA Samara', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(440, 99, 775, 776, 3, 'com_content.article.373', 'С 1 января 2013 года запрещена продажа бензина Евро-2', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(441, 99, 777, 778, 3, 'com_content.article.374', 'Лучшим в автоконцерне признан российский завод Hyundai', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(442, 99, 779, 780, 3, 'com_content.article.375', 'Honda Accord обладает наивысшим баллом по результатам краш-тестов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(443, 99, 781, 782, 3, 'com_content.article.376', 'Универсал Dacia Logan представят на Женевском мотор-шоу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(444, 99, 783, 784, 3, 'com_content.article.377', 'Самые забавные автополитические инициативы прошлого года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(445, 99, 785, 786, 3, 'com_content.article.378', 'Специальные цены объявила Suzuki на городской кроссовер SX4', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(446, 99, 787, 788, 3, 'com_content.article.379', 'Наиболее популярной моделью АвтоВАЗа в минувшем году была LADA Priora', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(447, 99, 789, 790, 3, 'com_content.article.380', 'Первые официальные фото самого нового кроссовера Peugeot 2008 наконец-то опубликованы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(448, 99, 791, 792, 3, 'com_content.article.381', 'Пассажиров общественного транспорта застрахуют от аварий', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(449, 99, 793, 794, 3, 'com_content.article.382', 'Renault увеличила цены на Duster и Logan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(450, 99, 795, 796, 3, 'com_content.article.383', 'Ford Focus удостоверил статус безопасного автомобиля', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(451, 99, 797, 798, 3, 'com_content.article.384', 'В России Daewoo представит седан С-класса', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(452, 99, 799, 800, 3, 'com_content.article.385', 'BMW планирует расширить производственные мощности в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(453, 99, 801, 802, 3, 'com_content.article.386', 'В российском представительстве Jeep, Fiat, Dodge и Chrysler – новый гендиректор', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(454, 99, 803, 804, 3, 'com_content.article.387', 'Volkswagen запускает дешевый бренд специально для России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(455, 99, 805, 806, 3, 'com_content.article.388', 'Chevrolet TuneIn на международной выставке электроники раскроет все свои возможности', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(456, 99, 807, 808, 3, 'com_content.article.389', 'Volkswagen Golf станет четырехдверным купе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(457, 99, 809, 810, 3, 'com_content.article.390', 'Авторынку России грозит кризис перепроизводства', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(458, 99, 811, 812, 3, 'com_content.article.391', 'Рекорд продаж в 2012 году поставила BMW Group Россия', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(459, 99, 813, 814, 3, 'com_content.article.392', 'Авторынок России "потерял" еще одну модель Ford', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(460, 99, 815, 816, 3, 'com_content.article.393', 'У российских дилеров появился Nissan GT-R 2013 модельного года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(461, 99, 817, 818, 3, 'com_content.article.394', '«АвтоВАЗ» усилит производство за счет заказов от Nissan и Renault', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(462, 99, 819, 820, 3, 'com_content.article.395', 'Облик нового Infiniti Q50 рассекречен', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(463, 99, 821, 822, 3, 'com_content.article.396', 'Концептуальный кроссовер Resonance от Nissan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(464, 99, 823, 824, 3, 'com_content.article.397', 'Самая популярная модель в России по результатам 2012 года - Lada Priora', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(465, 99, 825, 826, 3, 'com_content.article.398', 'Новый Mercedes-Benz CLA официально представлен', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(466, 99, 827, 828, 3, 'com_content.article.399', 'Opel создал новый дизельный двигатель', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(467, 99, 829, 830, 3, 'com_content.article.400', 'В России автомобили Datsun будут стоить до 400 тыс. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(468, 99, 831, 832, 3, 'com_content.article.401', 'Россияне стали выбирать новые автомобили с «автоматом»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(469, 99, 833, 834, 3, 'com_content.article.402', 'KIA сообщила российские цены на Quoris', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(470, 99, 835, 836, 3, 'com_content.article.403', 'Авторынок Европы переживает тяжелые времена', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(471, 99, 837, 838, 3, 'com_content.article.404', 'Audi пополнил модельную линейку RS', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(472, 99, 839, 840, 3, 'com_content.article.405', 'АвтоВАЗ продает LADA Kalina за бесценок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(473, 99, 841, 842, 3, 'com_content.article.406', 'В России повысился спрос на авто с пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(474, 99, 843, 844, 3, 'com_content.article.407', 'Объявлена стоимость на Solaris 2013 модельного года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(475, 99, 845, 846, 3, 'com_content.article.408', 'Российский завод Ford подает в суд на своих рабочих', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(476, 99, 847, 848, 3, 'com_content.article.409', 'Легендарный «Бэтмобиль» продали за 4,6 млн. долларов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(477, 99, 849, 850, 3, 'com_content.article.410', 'На российском авторынке доля премиум-класса достигла 6,7%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(478, 99, 851, 852, 3, 'com_content.article.411', 'Цена владения автомобилем в России больше, чем в США', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(479, 99, 853, 854, 3, 'com_content.article.412', 'За неправильную парковку будут жестко наказывать в Москве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(480, 99, 855, 856, 3, 'com_content.article.413', 'В 2012 году российский автопром установил рекорд', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(481, 99, 857, 858, 3, 'com_content.article.414', 'Предлагается увеличить штрафы за неимение детского автокресла до 3 тыс. руб.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(482, 99, 859, 860, 3, 'com_content.article.415', 'На покупку автомобилей россияне потратили 75 млрд. долларов за прошлый год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(483, 99, 861, 862, 3, 'com_content.article.416', 'За кражу автомобильных номеров предлагают наказывать более жестко', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(484, 99, 863, 864, 3, 'com_content.article.417', 'Появился самый дорогой в мире автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(485, 99, 865, 866, 3, 'com_content.article.418', 'В том, что никогда не используют ремни безопасности - признались 11% опрошенных россиян', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(486, 99, 867, 868, 3, 'com_content.article.419', 'Лучший автомобиль 2013 года - Kia Optima', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(487, 99, 869, 870, 3, 'com_content.article.420', 'Наиболее успешной в России китайской маркой стала Lifan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(488, 99, 871, 872, 3, 'com_content.article.421', 'Renault в 2012 году в России поставила рекорд', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(489, 99, 873, 874, 3, 'com_content.article.422', 'Компания Toyota отзывает 16 моделей с рынка', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(490, 99, 875, 876, 3, 'com_content.article.423', 'В России образовался Клуб поклонников автомобиля Opel Mokka', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(491, 99, 877, 878, 3, 'com_content.article.424', 'Завод Nissan в Петербурге из-за понижения спроса сокращает рабочую неделю', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(492, 99, 879, 880, 3, 'com_content.article.425', 'Иномарки составляют 74% от проданных машин с пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(493, 99, 881, 882, 3, 'com_content.article.426', 'Общественный транспорт предлагают оснастить алкозамками', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(494, 99, 883, 884, 3, 'com_content.article.427', 'Новый автомобиль BMW 7 серии признан лучшим среди авто представительского класса', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(495, 99, 885, 886, 3, 'com_content.article.428', 'Новый Golf получил награду как лучший автомобиль гольф-класса', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(496, 99, 887, 888, 3, 'com_content.article.429', 'На авторынок России придут белорусско-китайские автомобили', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(497, 99, 889, 890, 3, 'com_content.article.430', 'Корейское авто "японец" признано лучшим', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(498, 99, 891, 892, 3, 'com_content.article.431', 'Началась распродажа автомобилей с ПТС 2012 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(499, 99, 893, 894, 3, 'com_content.article.432', 'В России уже продали 50-тысячный Renault Duster', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(500, 99, 895, 896, 3, 'com_content.article.433', 'Citroen готовит к выпуску еще два кроссовера', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(501, 99, 897, 898, 3, 'com_content.article.434', 'При подборе автомобиля россияне выбирают интернет', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(502, 99, 899, 900, 3, 'com_content.article.435', 'Только 1/3 поставщиков АвтоВАЗа соответствуют всем требованиям Renault-Nissan', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(503, 99, 901, 902, 3, 'com_content.article.436', 'SKODA AUTO уже принимает заказы на модель Yeti 1.4 TSI DSG', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(504, 99, 903, 904, 3, 'com_content.article.437', 'В России стартовало производство Chevrolet Aveo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(505, 99, 905, 906, 3, 'com_content.article.438', 'АвтоВАЗ вложит 378 млн. евро в производство двигателей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(506, 99, 907, 908, 3, 'com_content.article.439', 'KIA покажет провокационный концепт-кар на автосалоне в Женеве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(507, 99, 909, 910, 3, 'com_content.article.440', 'Ученые провели исследование автомобилей на "запах"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(508, 99, 911, 912, 3, 'com_content.article.441', 'В прошлом году 88% водителей нарушили правила', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(509, 99, 913, 914, 3, 'com_content.article.442', 'Nissan демонстриует Nissan Qashqai 360', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(510, 99, 915, 916, 3, 'com_content.article.443', 'Toyota планирует выпустить заднеприводный хэтчбек', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(511, 99, 917, 918, 3, 'com_content.article.444', 'Запрет Евро-2 и Акцизы подстегнут цены на бензин', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(512, 99, 919, 920, 3, 'com_content.article.445', 'В России намереваются ввести новую категорию "М"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(513, 99, 921, 922, 3, 'com_content.article.446', 'В ретроралли «Пекин-Париж» Впервые за победу будут бороться русские', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(514, 99, 923, 924, 3, 'com_content.article.447', '"Фонящие" иномарки из Японии попытались ввезти в Россию', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(515, 99, 925, 926, 3, 'com_content.article.448', 'Audi создала светодиодные инновационные фары - Matrix LED', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(516, 99, 927, 928, 3, 'com_content.article.449', 'На рынке вторичных автомобилей самой популярной стала LADA 2107', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(517, 99, 929, 930, 3, 'com_content.article.450', 'LADA Priora теперь в  «суперлюксовой» версии', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(518, 99, 931, 932, 3, 'com_content.article.451', 'Рынок автомобилей коммерческих за январь вырос на 7%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(519, 99, 933, 934, 3, 'com_content.article.452', 'На дороги России выходят первые электромобили LADA', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(520, 99, 935, 936, 3, 'com_content.article.453', 'Новый флагман от KIA появится на авторынке России в марте', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(521, 99, 937, 938, 3, 'com_content.article.454', 'Manchester United вручили автомобили Chevrolet в рамках спонсорской программы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(522, 99, 939, 940, 3, 'com_content.article.455', 'ТагАЗ приступает к испытуемой сборке первого российского спорткара', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(523, 99, 941, 942, 3, 'com_content.article.456', 'От Toyota с BMW "родится" новая Supra', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(524, 99, 943, 944, 3, 'com_content.article.457', 'За последние 8 лет в России автотранспорта стало больше почти в 1,5 раза', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(525, 99, 945, 946, 3, 'com_content.article.458', 'На федеральных трассах "Телефонных ям"  больше не будет', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(526, 99, 947, 948, 3, 'com_content.article.459', 'Средний размер выплаты по ОСАГО подрос на 10%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(527, 99, 949, 950, 3, 'com_content.article.460', 'Завод в Елабуге Ford Sollers увеличивает темпы производства', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(528, 99, 951, 952, 3, 'com_content.article.461', 'Автомобили ДПС снабдят терминалами для быстрой оплаты штрафов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(529, 99, 953, 954, 3, 'com_content.article.462', 'Ferrari получил признание - самый влиятельный бренд в мире', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(530, 99, 955, 956, 3, 'com_content.article.463', 'На рынок России вышли новые Toyota RAV4, Auris и Verso', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(531, 99, 957, 958, 3, 'com_content.article.464', 'Новый беспилотный автомобиль прошел испытания', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(532, 99, 959, 960, 3, 'com_content.article.465', 'В России чаще всего нарушают ПДД и общественный порядок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(533, 99, 961, 962, 3, 'com_content.article.466', 'Повысить лимиты выплат по ОСАГО предлагает правительство', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(534, 99, 963, 964, 3, 'com_content.article.467', 'Трамвайные линии наконец-то защитят от автомобилистов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(535, 99, 965, 966, 3, 'com_content.article.468', 'Новый Golf R Cabriolet от Volkswagen', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(536, 99, 967, 968, 3, 'com_content.article.469', 'Самые надежные подержанные автомобили, по мнению немцев', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(537, 99, 969, 970, 3, 'com_content.article.470', 'Suzuki будет выпускать водородные машины', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(538, 99, 971, 972, 3, 'com_content.article.471', 'Испанцы создали 900-сильный суперкар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(539, 99, 973, 974, 3, 'com_content.article.472', 'Свой Renault Logan будет и у России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(540, 99, 975, 976, 3, 'com_content.article.473', 'Renault Symbol больше не будут реализовывать в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(541, 99, 977, 978, 3, 'com_content.article.474', '"АвтоВАЗ" будет удерживать рост стоимости на автомобили ниже инфляции', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(542, 99, 979, 980, 3, 'com_content.article.475', '"АвтоВАЗ" уже готов производить машины для людей с ограниченными возможностями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(543, 99, 981, 982, 3, 'com_content.article.476', 'Chevrolet Tracker  в России появится с опозданием', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(544, 99, 983, 984, 3, 'com_content.article.477', 'Peugeot оснастила новый седан 408 дизелем с мощностью 112 л.с.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(545, 99, 985, 986, 3, 'com_content.article.478', 'Первая партия внедорожников Land Cruiser от Toyota отправлена в регионы России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(546, 99, 987, 988, 3, 'com_content.article.479', 'Россияне скупают люксовые иномарки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(547, 99, 989, 990, 3, 'com_content.article.480', 'Nissan представила первый тизер дешевого суббренда Datsun', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(548, 99, 991, 992, 3, 'com_content.article.481', 'Volvo экскаваторный завод открывает в Калуге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(549, 99, 993, 994, 3, 'com_content.article.482', 'Составлен топ лучших «новичков» этого года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(550, 99, 995, 996, 3, 'com_content.article.483', 'АвтоВАЗ будет выпускать Lada 4x4 вплоть до 2016 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(551, 99, 997, 998, 3, 'com_content.article.484', 'Geely и Volvo Cars создают центр разработок и исследований', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(552, 99, 999, 1000, 3, 'com_content.article.485', 'Российских дилеров Renault Trucks и Volvo объединят', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(553, 99, 1001, 1002, 3, 'com_content.article.486', 'Наконец-то представили официально рестайлинговую Nissan Teana', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(554, 99, 1003, 1004, 3, 'com_content.article.487', 'АвтоВАЗ готовится запустить в производство Lada Kalina 2', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(555, 99, 1005, 1006, 3, 'com_content.article.488', 'Chevrolet в Россию привез Trailblazer за 1,5 млн. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(556, 99, 1007, 1008, 3, 'com_content.article.489', '7 лет гарантии дала KIA на свой новый флагманский седан', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(557, 99, 1009, 1010, 3, 'com_content.article.490', 'У Ford Focus появился конкурент - Honda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(558, 99, 1011, 1012, 3, 'com_content.article.491', 'Шестиколесный Mercedes-Benz будет запущен в серийное производство', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(559, 99, 1013, 1014, 3, 'com_content.article.492', 'Самая «клевая» LADA появилась в автосалонах', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(560, 99, 1015, 1016, 3, 'com_content.article.493', 'Новый Fluence привезет в Россию Renault', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(561, 99, 1017, 1018, 3, 'com_content.article.494', 'Renault Kangoo в России получил дизельный двигатель', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(562, 99, 1019, 1020, 3, 'com_content.article.495', 'Volkswagen планирует в мае запустить сборку Audi в Калуге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(563, 99, 1021, 1022, 3, 'com_content.article.496', 'Volvo показала на Женевском автосалоне целый ряд новинок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(564, 99, 1023, 1024, 3, 'com_content.article.497', 'Московское такси станет желтым', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(565, 99, 1025, 1026, 3, 'com_content.article.498', 'Peugeot создал суперкар из уникальных материалов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(566, 99, 1027, 1028, 3, 'com_content.article.499', 'В этом году отремонтируют федеральные автодороги в России протяженностью 9 тыс. км', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(567, 99, 1029, 1030, 3, 'com_content.article.500', 'В России будут производить Kia Cerato и Quoris нового поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(568, 99, 1031, 1032, 3, 'com_content.article.501', 'Ford по всему миру отзывает 230 тыс. авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(569, 99, 1033, 1034, 3, 'com_content.article.502', 'Стоимость на 16-клапанную LADA Samara опустилась до 299 тыс. руб.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(570, 99, 1035, 1036, 3, 'com_content.article.503', 'Dacia возможно выпустит две новинки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(571, 99, 1037, 1038, 3, 'com_content.article.504', 'Москва на транспортную инфраструктуру тратит по $10 млрд. в год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(572, 99, 1039, 1040, 3, 'com_content.article.505', 'Nissan более трети автомобилей в феврале продал в кредит', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(573, 99, 1041, 1042, 3, 'com_content.article.506', 'Компания Hyundai обновила версию i20 для ралли', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(574, 99, 1043, 1044, 3, 'com_content.article.507', 'Mitsubishi Motors внедрит технологии 100%-ных электромобилей к 2020 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(575, 99, 1045, 1046, 3, 'com_content.article.508', 'Эксперты российскому авторынку предвещают продажи в 2,86 млн. легковых новых автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(576, 99, 1047, 1048, 3, 'com_content.article.509', 'LADA 4x4 скоро обновит дизайн', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(577, 99, 1049, 1050, 3, 'com_content.article.510', 'Виновными в шпионаже признали автомобили Google', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(578, 99, 1051, 1052, 3, 'com_content.article.511', 'SMS-информирование о штрафах, что об этом думают водители?', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(579, 99, 1053, 1054, 3, 'com_content.article.512', 'Автомобили, припаркованные неправильно, предложили утилизировать', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(580, 99, 1055, 1056, 3, 'com_content.article.513', 'Toyota премьерует полностью новый Highlander', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(581, 99, 1057, 1058, 3, 'com_content.article.514', 'Chery стала самой красивой европейской машиной', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(582, 99, 1059, 1060, 3, 'com_content.article.515', 'Продажи российского спорткара Tagaz Aquila стартовали', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(583, 99, 1061, 1062, 3, 'com_content.article.516', 'Компания Hyundai представит на рынок России Grand Santa Fe', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(584, 99, 1063, 1064, 3, 'com_content.article.517', 'Наиболее доступные автомобили в России - Daewoo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(585, 99, 1065, 1066, 3, 'com_content.article.518', 'Авторынок России в феврале стал по величине вторым в Европе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(586, 99, 1067, 1068, 3, 'com_content.article.519', 'Цены на 5 новых версий LADA Granta рассекречены', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(587, 99, 1069, 1070, 3, 'com_content.article.520', 'Audi соберет автомобили "олимпийской" серии', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(588, 99, 1071, 1072, 3, 'com_content.article.521', 'Начались продажи Citroёn DS4 дизельной версии в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(589, 99, 1073, 1074, 3, 'com_content.article.522', 'Через 3 недели стартуют продажи Nissan Almera в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(590, 99, 1075, 1076, 3, 'com_content.article.523', 'В России дорожает бензин, но дешевеет дизтопливо', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(591, 99, 1077, 1078, 3, 'com_content.article.524', 'Водители теперь не обязаны выходить из автомобиля для составления протокола', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(592, 99, 1079, 1080, 3, 'com_content.article.525', 'Марка LIFAN выпускает кроссовер стилизованный под Suzuki SX4', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(593, 99, 1081, 1082, 3, 'com_content.article.526', 'Toyota - самый дорогой автомобильный бренд', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(594, 99, 1083, 1084, 3, 'com_content.article.527', 'Модель Hyundai ix35 поменяла комплектации', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(595, 99, 1085, 1086, 3, 'com_content.article.528', 'Две спецверсии Toyota Corolla 2013-го модельного года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(596, 99, 1087, 1088, 3, 'com_content.article.529', 'Хэтчбек Nissan Micra обновлен', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(597, 99, 1089, 1090, 3, 'com_content.article.530', '«Кубическая» новинка от KIA', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(598, 99, 1091, 1092, 3, 'com_content.article.531', 'LADA Granta теперь будет доступна и в дальнем зарубежье', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(599, 99, 1093, 1094, 3, 'com_content.article.532', 'На автомобили в России введут паспорта в электронном виде', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(600, 99, 1095, 1096, 3, 'com_content.article.533', 'Кроссовер для России создаст SKODA', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(601, 99, 1097, 1098, 3, 'com_content.article.534', 'В Россию привезут новую Acura MDX', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(602, 99, 1099, 1100, 3, 'com_content.article.535', 'В прошлом году в России угнали свыше 52 тыс. авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(603, 99, 1101, 1102, 3, 'com_content.article.536', 'На автосалоне в Нью-Йорке представили самые лучшие автомобили', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(604, 99, 1103, 1104, 3, 'com_content.article.537', 'Mitsubishi вместе с Nissan выпустят микромобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(605, 99, 1105, 1106, 3, 'com_content.article.538', 'Автолюбители России меняют автомобиль раз в 3 - 5 лет', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(606, 99, 1107, 1108, 3, 'com_content.article.539', 'Настал черед ё-самолета', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(607, 99, 1109, 1110, 3, 'com_content.article.540', 'В России доступен Volkswagen Scirocco с мощность 122 л.с.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(608, 99, 1111, 1112, 3, 'com_content.article.541', 'Официально представлен новый внедорожник от Toyota', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(609, 99, 1113, 1114, 3, 'com_content.article.542', 'Volvo разработала внешнюю подушку безопасности для автомобиля', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(610, 99, 1115, 1116, 3, 'com_content.article.543', 'Скоро в России будут следить за парковкой  вертолеты-эвакуаторы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(611, 99, 1117, 1118, 3, 'com_content.article.544', 'Компания Mercedes огласила новые цены на автомобили для России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(612, 99, 1119, 1120, 3, 'com_content.article.545', 'Модели Renault снабдят люксовыми версиями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(613, 99, 1121, 1122, 3, 'com_content.article.546', 'Прием заказов на инновационный городской кроссовер Toyota начался', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(614, 99, 1123, 1124, 3, 'com_content.article.547', 'О комплектациях и ценах на новый KIA Cerato теперь известно всем', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(615, 99, 1125, 1126, 3, 'com_content.article.548', 'Citroen вложил 120 млн. евро в производство седана С4 в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(616, 99, 1127, 1128, 3, 'com_content.article.549', 'MINI показал самый необычный коммерческий автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(617, 99, 1129, 1130, 3, 'com_content.article.550', 'По результатам 2012 года наиболее загруженным городом названа Москва', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(618, 99, 1131, 1132, 3, 'com_content.article.551', 'Шины Nokian Tyres заполучили 1/4 шинного рынка РФ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(619, 99, 1133, 1134, 3, 'com_content.article.552', 'Mitsubishi реализовала в марте в России рекордное число внедорожников', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(620, 99, 1135, 1136, 3, 'com_content.article.553', 'Отзыв автомобилей Renault Koleos коснулся и клиентов России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(621, 99, 1137, 1138, 3, 'com_content.article.554', 'Kia в России отзывает 49 тыс. автомобилей из-за возникших проблем с тормозами', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(622, 99, 1139, 1140, 3, 'com_content.article.555', 'Автомобиль Mazda CX-5 в России снабжен двигателем с мощностью 192 л.с.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(623, 99, 1141, 1142, 3, 'com_content.article.556', 'Renault назвал цены на новый автомобиль Fluence', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(624, 99, 1143, 1144, 3, 'com_content.article.557', 'За пару месяцев в Россию ввезли авто на сумму на 2,4 млрд. долларов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(625, 99, 1145, 1146, 3, 'com_content.article.558', 'Honda представила два концепта', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(626, 99, 1147, 1148, 3, 'com_content.article.559', 'Renault показал долгожданный заднеприводный электрокар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(627, 99, 1149, 1150, 3, 'com_content.article.560', 'Столицу России назвали одной из дорогих мегаполисов для автовладельцев', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(628, 99, 1151, 1152, 3, 'com_content.article.561', 'Suzuki стала банкротом в Соединенных Штатах Америки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(629, 99, 1153, 1154, 3, 'com_content.article.562', 'Автомобиль Volvo S60 Polestar пустят в серийное производство', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(630, 99, 1155, 1156, 3, 'com_content.article.563', 'В России автомобили хотят на газ перевести', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(631, 99, 1157, 1158, 3, 'com_content.article.564', 'В Москве намереваются открыть прокат электромобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(632, 99, 1159, 1160, 3, 'com_content.article.565', 'Самый реализуемый автомобиль в мире - Ford Focus', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(633, 99, 1161, 1162, 3, 'com_content.article.566', 'BMW вместе с китайцами создаст совместный бренд', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(634, 99, 1163, 1164, 3, 'com_content.article.567', 'Kia создает заднеприводное купе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(635, 99, 1165, 1166, 3, 'com_content.article.568', 'Skoda открыла всю информацию об обновленном Superb', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(636, 99, 1167, 1168, 3, 'com_content.article.569', 'В этом году в Москве построят перехватывающие подземные парковки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(637, 99, 1169, 1170, 3, 'com_content.article.570', 'Lada Granta, предназначенная для экспорта, стоит в 1,5 раза дороже', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(638, 99, 1171, 1172, 3, 'com_content.article.571', 'Honda продемонстрировала седан всего за 280 000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(639, 99, 1173, 1174, 3, 'com_content.article.572', 'GM и Ford разработают 10-ступенчатую автоматическую трансмиссию', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(640, 99, 1175, 1176, 3, 'com_content.article.573', 'Mazda скоро представит моноприводного конкурента автомобиля Opel Mokka', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(641, 99, 1177, 1178, 3, 'com_content.article.574', 'BMW сделает из i3 универсал и купе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(642, 99, 1179, 1180, 3, 'com_content.article.575', 'Стартовали авто продажи Kia Cerato нового поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(643, 99, 1181, 1182, 3, 'com_content.article.576', 'Peugeot 308 подорожал и поменял место сборки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(644, 99, 1183, 1184, 3, 'com_content.article.577', 'В ноябре представят новый Qashqai', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(645, 99, 1185, 1186, 3, 'com_content.article.578', 'Hyundai делает отзыв свыше 11 тысяч авто в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(646, 99, 1187, 1188, 3, 'com_content.article.579', 'Honda раскрыла комплектации нового Crosstour', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(647, 99, 1189, 1190, 3, 'com_content.article.580', 'Названы популярные авто марта этого года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(648, 99, 1191, 1192, 3, 'com_content.article.581', 'Volkswagen Tiguan станет конкурентом Range Rover Evoque', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(649, 99, 1193, 1194, 3, 'com_content.article.582', 'Saab до банкротства планировал показать четырехместное купе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(650, 99, 1195, 1196, 3, 'com_content.article.583', 'Toyota получила патент на родстер GT 86', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(651, 99, 1197, 1198, 3, 'com_content.article.584', 'Honda показала новый седан из бюджетных', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(652, 99, 1199, 1200, 3, 'com_content.article.585', 'Toyota продемонстрировала тизер 4Runner', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(653, 99, 1201, 1202, 3, 'com_content.article.586', 'Авто продажи простенькой Hyundai Sonata наступят в этом году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(654, 99, 1203, 1204, 3, 'com_content.article.587', 'Suzuki показала новый седан', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(655, 99, 1205, 1206, 3, 'com_content.article.588', 'С конвейера завода «Соллерс - Дальний Восток» сошла Mazda6', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(656, 99, 1207, 1208, 3, 'com_content.article.589', 'Стартовали авто продажи Volkswagen Scirocco GTS', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(657, 99, 1209, 1210, 3, 'com_content.article.590', 'Toyota показала футуристичный концепт', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(658, 99, 1211, 1212, 3, 'com_content.article.591', 'Ford Escort возможно привезут в Европу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(659, 99, 1213, 1214, 3, 'com_content.article.592', 'Внедорожный SEAT Leon появится в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(660, 99, 1215, 1216, 3, 'com_content.article.593', 'На базе Chevrolet Lacetti Daewoo создал седан', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(661, 99, 1217, 1218, 3, 'com_content.article.594', 'Volkswagen Jetta станет дешевле для России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(662, 99, 1219, 1220, 3, 'com_content.article.595', 'В России стартовали авто продажи нового Infiniti JX', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(663, 99, 1221, 1222, 3, 'com_content.article.596', 'Fiat 500 станет семиместным кроссовером', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(664, 99, 1223, 1224, 3, 'com_content.article.597', 'Volvo не будет выпускать конкурента Audi A8', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(665, 99, 1225, 1226, 3, 'com_content.article.598', 'Kia сообщила цены на новый автомобиль pro_ceed', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(666, 99, 1227, 1228, 3, 'com_content.article.599', 'В России стартовали авто продажи автомобиля Honda CR-V с новым двигателем', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(667, 99, 1229, 1230, 3, 'com_content.article.600', 'Сроки оплаты штрафов в добровольном порядке увеличили в 2 раза', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(668, 99, 1231, 1232, 3, 'com_content.article.601', 'Стала известна цена проезда по трассе М11, точнее по платному участку', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(669, 99, 1233, 1234, 3, 'com_content.article.602', 'Получать водительские права можно будет и на авто с АКПП', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(670, 99, 1235, 1236, 3, 'com_content.article.603', 'Новую комплектацию получила Nissan Teana', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(671, 99, 1237, 1238, 3, 'com_content.article.604', 'Новый Megane от Renault покажут в сентябре этого года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(672, 99, 1239, 1240, 3, 'com_content.article.605', 'Volkswagen показал миру Golf R-Line', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(673, 99, 1241, 1242, 3, 'com_content.article.606', 'Fiat создаст 8-цилиндровый дизельный двигатель', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(674, 99, 1243, 1244, 3, 'com_content.article.607', 'Volkswagen за будущие пять лет намерен выпустить еще 60 рестайлинговых и новых моделей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(675, 99, 1245, 1246, 3, 'com_content.article.608', 'В Интернет попали запатентованные фотографии Volkswagen Taigun', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(676, 99, 1247, 1248, 3, 'com_content.article.609', 'SEAT начинает в июне авто продажи минивена Alhambra', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(677, 99, 1249, 1250, 3, 'com_content.article.610', 'Налог на «богатые» автомобили начнет действовать с ноября этого года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(678, 99, 1251, 1252, 3, 'com_content.article.611', 'Новинку BMW X5 увидели на испытаниях', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(679, 99, 1253, 1254, 3, 'com_content.article.612', 'Электрокары от BMW в России появятся через 12 месяцев', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(680, 99, 1255, 1256, 3, 'com_content.article.613', 'Audi скоро представит модель Q8', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(681, 99, 1257, 1258, 3, 'com_content.article.614', 'Автомобили с пробегом можно будет узнавать по VIN', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(682, 99, 1259, 1260, 3, 'com_content.article.615', 'Alfa Romeo кроссовер выпустит через 2 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(683, 99, 1261, 1262, 3, 'com_content.article.616', 'Новинка от Nissan- раскрыты подробности', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(684, 99, 1263, 1264, 3, 'com_content.article.617', 'Mazda MX-5 возможно оснастят дизельным движком', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(685, 99, 1265, 1266, 3, 'com_content.article.618', 'Dacia Duster станет пикапом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(686, 99, 1267, 1268, 3, 'com_content.article.619', 'Nissan не будет поставлять Datsun в страны Западной Европы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(687, 99, 1269, 1270, 3, 'com_content.article.620', 'Skoda представит новый спортавтомобиль на основе Rapid-модели', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(688, 99, 1271, 1272, 3, 'com_content.article.621', 'Водородная модель бренда Toyota можно будет купить за $50 - 100 тысяч', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(689, 99, 1273, 1274, 3, 'com_content.article.622', 'Chevrolet Niva подоражает  с первого мая 2013 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(690, 99, 1275, 1276, 3, 'com_content.article.623', 'Следующая модель Nissan Qashqai станет мини', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(691, 99, 1277, 1278, 3, 'com_content.article.624', 'Mazda6 с сильным движком обойдется чуть больше чем в 1 млн. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(692, 99, 1279, 1280, 3, 'com_content.article.625', 'Range Rover Sport станет гибридом уже скоро', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(693, 99, 1281, 1282, 3, 'com_content.article.626', 'VW скоро представит экстремальный Golf за последние годы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(694, 99, 1283, 1284, 3, 'com_content.article.627', 'В тюнинг-ателье Brabus доработали компактные автомобили Mercedes-Benz', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(695, 99, 1285, 1286, 3, 'com_content.article.628', 'Автомобиль BMW M8 будет стоить 1/4 млн. евро', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(696, 99, 1287, 1288, 3, 'com_content.article.629', 'Audi TT следующего поколения будет на пятицилиндровом двигателе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(697, 99, 1289, 1290, 3, 'com_content.article.630', 'Хетчбэк Kia pro_cee’d трехдверный сегодня появился в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(698, 99, 1291, 1292, 3, 'com_content.article.631', 'Начались авто продажи Peugeot 301 в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(699, 99, 1293, 1294, 3, 'com_content.article.632', 'Авто продажи в России новых машин падают не первый месяц', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(700, 99, 1295, 1296, 3, 'com_content.article.633', 'Honda выстроит завод для производства суперкара NSX', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(701, 99, 1297, 1298, 3, 'com_content.article.634', 'Автомобиль Opel Astra 10 лошадиных сил разменяет на 50 Нм', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(702, 99, 1299, 1300, 3, 'com_content.article.635', 'Стоимость новой Lada Kalina начинается с 324000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(703, 99, 1301, 1302, 3, 'com_content.article.636', 'Продажи роскошных автомобилей в РФ растут даже на фоне общего падение рынка', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(704, 99, 1303, 1304, 3, 'com_content.article.637', 'Впервые подушку безопасности в центре поставили на серийный автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(705, 99, 1305, 1306, 3, 'com_content.article.638', 'Новый S-класс Mercedes-Benz отличает животных от людей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(706, 99, 1307, 1308, 3, 'com_content.article.639', 'Мотоциклистам теперь разрешат ездить в Москве по выделенным полосам', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(707, 99, 1309, 1310, 3, 'com_content.article.640', 'Новинка от Infiniti выйдет за рамки классов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(708, 99, 1311, 1312, 3, 'com_content.article.641', 'За гибридной моделью Volvo V60 выстраиваются длинные очереди', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(709, 99, 1313, 1314, 3, 'com_content.article.642', 'На территории России стартуют авто продажи новинки Seat Leon', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(710, 99, 1315, 1316, 3, 'com_content.article.643', 'Дешевая новинка кроссовер Hyundai в продажу поступит только в 2015 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(711, 99, 1317, 1318, 3, 'com_content.article.644', 'Toyota и BMW разработают на платформе GT86 новый спорткар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(712, 99, 1319, 1320, 3, 'com_content.article.645', 'Популярнейшим авто в Европе до сих пор остается VW Golf', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(713, 99, 1321, 1322, 3, 'com_content.article.646', 'Бренд Renault окрасил модель  Megane RS в черно-желтые оттенки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(714, 99, 1323, 1324, 3, 'com_content.article.647', 'Наиболее дорогим автобрендом в мире стала Toyota', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(715, 99, 1325, 1326, 3, 'com_content.article.648', 'Hyundai планирует автомобили на водороде сделать массовыми', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(716, 99, 1327, 1328, 3, 'com_content.article.649', 'Стоимость на новый седан Audi A3 стартует с отметки 870 000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(717, 99, 1329, 1330, 3, 'com_content.article.650', 'Nissan по всему миру отзывает свыше 840 тысяч машин', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(718, 99, 1331, 1332, 3, 'com_content.article.651', 'Renault в следующем году обновит линейку моделей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(719, 99, 1333, 1334, 3, 'com_content.article.652', 'Новинка BMW M3 будет быстрее и легче предшественника', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(720, 99, 1335, 1336, 3, 'com_content.article.653', 'Новинку Skoda Octavia в России можно будет купить от 589900 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(721, 99, 1337, 1338, 3, 'com_content.article.654', 'Компания Kia планирует выпустить массовый электрокар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(722, 99, 1339, 1340, 3, 'com_content.article.655', 'Daewoo рассказала  о выпуске нового экономного седана', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(723, 99, 1341, 1342, 3, 'com_content.article.656', 'Honda сообщила о комплектациях обновленного Civic', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(724, 99, 1343, 1344, 3, 'com_content.article.657', 'Купить автомобиль Range Rover Sport можно будет от 3 миллионов рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(725, 99, 1345, 1346, 3, 'com_content.article.658', '10 изменений в Ниве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(726, 99, 1347, 1348, 3, 'com_content.article.659', 'Tesla разработает экономный электрокар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(727, 99, 1349, 1350, 3, 'com_content.article.660', 'Pininfarina и BMW создали роскошное купе в одном экземпляре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(728, 99, 1351, 1352, 3, 'com_content.article.661', 'Следующее поколение BMW X5 будет брать количеством', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(729, 99, 1353, 1354, 3, 'com_content.article.662', 'Суперкар от Mercedes будет мощнее и экономнее Porsche 911', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(730, 99, 1355, 1356, 3, 'com_content.article.663', 'Lada Granta с сильным агрегатом оценена в 406 тысяч рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(731, 99, 1357, 1358, 3, 'com_content.article.664', 'Toyota Corolla раскрыла секрет масштабной модели', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(732, 99, 1359, 1360, 3, 'com_content.article.665', 'Insignia пополнит ряд универсалом с повышенной проходимостью', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(733, 99, 1361, 1362, 3, 'com_content.article.666', 'Обновленный Dacia Duster увидели на тестах', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(734, 99, 1363, 1364, 3, 'com_content.article.667', 'ТагАЗ будет собирать автомобили Jeep и Chery', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(735, 99, 1365, 1366, 3, 'com_content.article.668', 'Самым дешевым Lamborghini станет кроссовер Urus', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(736, 99, 1367, 1368, 3, 'com_content.article.669', 'Стартовала сборка в России автомобилей Mercedes', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(737, 99, 1369, 1370, 3, 'com_content.article.670', 'Skoda открыла производство рейсталингового Superb', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(738, 99, 1371, 1372, 3, 'com_content.article.671', 'По результатам 2013 года авторынок России может снизиться на 10%', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(739, 99, 1373, 1374, 3, 'com_content.article.672', 'Lada Granta теперь на газу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(740, 99, 1375, 1376, 3, 'com_content.article.673', 'Новая Mazda2 будет основываться на платформе CX-5', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(741, 99, 1377, 1378, 3, 'com_content.article.674', 'Pagani разработал заключительную версию суперкара Zonda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(742, 99, 1379, 1380, 3, 'com_content.article.675', 'BMW 8 в кузове купе станет серийным', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(743, 99, 1381, 1382, 3, 'com_content.article.676', 'Следующее поколение Renault Espace станет кроссовером', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(744, 99, 1383, 1384, 3, 'com_content.article.677', 'Ford Focus оснастят российскими двигателями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(745, 99, 1385, 1386, 3, 'com_content.article.678', 'Лучший мотор года - 1-литровый двигатель от Ford', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(746, 99, 1387, 1388, 3, 'com_content.article.679', 'Российские владельцы автомобилей в среднем тратят 4000 рублей в месяц на содержание своего автомобил', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(747, 99, 1389, 1390, 3, 'com_content.article.680', 'Концерн Toyota отзывает по всему миру гибриды', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(748, 99, 1391, 1392, 3, 'com_content.article.681', '5 "формульных" команд на Moscow City Racing вместе с Виталием Петровым', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(749, 99, 1393, 1394, 3, 'com_content.article.682', 'Купить автомобиль Lexus IS можно за 1377000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(750, 99, 1395, 1396, 3, 'com_content.article.683', 'Завод Volkswagen в Калуге выпустил 600 тыс. автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(751, 99, 1397, 1398, 3, 'com_content.article.684', 'Автомобильная  марка TVR из Британии возвращается на авторынок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(752, 99, 1399, 1400, 3, 'com_content.article.685', 'BMW показала обновленную ConnectedDrive систему', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(753, 99, 1401, 1402, 3, 'com_content.article.686', 'Mercedes возможно выпустит хэтчбек С-класса', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(754, 99, 1403, 1404, 3, 'com_content.article.687', 'Новинка Nissan Terrano на бюджетной платформе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(755, 99, 1405, 1406, 3, 'com_content.article.688', 'Ram решил собрать люксовый пикап', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(756, 99, 1407, 1408, 3, 'com_content.article.689', 'В России планируют открыться 3 завода производителей автокомпонентов Японии', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(757, 99, 1409, 1410, 3, 'com_content.article.690', 'В РФ будут реализовывать новый кроссовер Nomad от SsangYong', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(758, 99, 1411, 1412, 3, 'com_content.article.691', 'Прошла презентация "нового поколения" A1 e-tron от Audi', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(759, 99, 1413, 1414, 3, 'com_content.article.692', 'Суперкар родом из Греции разгоняется до 100 км. за 3,8 секунды', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(760, 99, 1415, 1416, 3, 'com_content.article.693', 'Kia: модель pro_cee''d в РФ не имеет конкурентов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(761, 99, 1417, 1418, 3, 'com_content.article.694', 'В обновленном Opel Insignia стало меньше кнопок, а двигателей – больше', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(762, 99, 1419, 1420, 3, 'com_content.article.695', 'Seat сделал из Leon гибрид стоимостью  34 млн. евро', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(763, 99, 1421, 1422, 3, 'com_content.article.696', 'Bentley показал новый автомобиль стоимостью за 10,7 млн. рублей в Москве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(764, 99, 1423, 1424, 3, 'com_content.article.697', 'Новая Skoda Octavia стала "тягачом года"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(765, 99, 1425, 1426, 3, 'com_content.article.698', 'В России начались авто продажи Toyota Venza, стоимостью 1,5 млн. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(766, 99, 1427, 1428, 3, 'com_content.article.699', 'Автозавод Ford в Татарстане начал производство Tourneo Custom', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(767, 99, 1429, 1430, 3, 'com_content.article.700', 'В Тольятти приступили к строительству завода для сборки новой "Нивы"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(768, 99, 1431, 1432, 3, 'com_content.article.701', 'На территорию России будут импортировать Opel Corsa собранной в Белоруссии', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(769, 99, 1433, 1434, 3, 'com_content.article.702', 'Бренд Citroen роскошную малолитражку запустит в серийное производство', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(770, 99, 1435, 1436, 3, 'com_content.article.703', 'Audi планирует представить во Франкфурте самый быстрый свой спортивный автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(771, 99, 1437, 1438, 3, 'com_content.article.704', 'Suzuki покажет в России полноприводный Swift', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(772, 99, 1439, 1440, 3, 'com_content.article.705', 'В России в августе можно будет купить Toyota Corolla нового поколения', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(773, 99, 1441, 1442, 3, 'com_content.article.706', 'Alfa Romeo обновила модель MiTo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(774, 99, 1443, 1444, 3, 'com_content.article.707', 'Ford заменил роботами водителей-испытателей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(775, 99, 1445, 1446, 3, 'com_content.article.708', 'В дилерские центры поступила новая Lada Kalina', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(776, 99, 1447, 1448, 3, 'com_content.article.709', 'Московский автомобильный салон-2014 уже принимает заявки на участие', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(777, 99, 1449, 1450, 3, 'com_content.article.710', 'В Нижнем Новгороде запустили сборку новой Skoda Octavia', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(778, 99, 1451, 1452, 3, 'com_content.article.711', 'Кроссовер тайваньского производства будут теперь собирать и в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(779, 99, 1453, 1454, 3, 'com_content.article.712', 'В компании Renault собрали тихоходный электрофургон', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(780, 99, 1455, 1456, 3, 'com_content.article.713', 'Volkswagen Passat получил звание - самого экономичного серийного автомобиля в мире', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(781, 99, 1457, 1458, 3, 'com_content.article.714', 'SsangYong Kyron с задним приводом начинает реализацию 1 июля', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(782, 99, 1459, 1460, 3, 'com_content.article.715', 'Мощнейшая версия Renault Clio скоро официально будет продаваться в РФ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(783, 99, 1461, 1462, 3, 'com_content.article.716', 'Премьера новой Mazda3 прошла 26 июня в Санкт-Петербурге', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(784, 99, 1463, 1464, 3, 'com_content.article.717', 'Opel намеревается продавать автомобили по льготным тарифам автокредита', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(785, 99, 1465, 1466, 3, 'com_content.article.718', 'Mazda сообщила о российских ценах на дизельную модель CX-5', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(786, 99, 1467, 1468, 3, 'com_content.article.719', 'Ford будет реализовывать в России еще больше внедорожников', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(787, 99, 1469, 1470, 3, 'com_content.article.720', 'Citroen показал новинку - Grand C4 Picasso', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(788, 99, 1471, 1472, 3, 'com_content.article.721', 'Электромобиль Nissan Leaf прокачало ателье Nismo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(789, 99, 1473, 1474, 3, 'com_content.article.722', 'Mercedes сообщил о ценах на длиннейший вариант S-class', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(790, 99, 1475, 1476, 3, 'com_content.article.723', 'Suzuki сообщила о новом названии автомобиля для дам', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(791, 99, 1477, 1478, 3, 'com_content.article.724', 'Завод моторов для новой Chevrolet Niva построят в Тольятти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(792, 99, 1479, 1480, 3, 'com_content.article.725', 'Завод моторов для новой Chevrolet Niva построят в Тольятти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(793, 99, 1481, 1482, 3, 'com_content.article.726', 'Новый Nissan Murano стал дешевле на 5000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(794, 99, 1483, 1484, 3, 'com_content.article.727', 'Первый водородный автомобиль Toyota покажут в ноябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(795, 99, 1485, 1486, 3, 'com_content.article.728', 'Первый автомобиль Datsun представят 15 июля', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(796, 99, 1487, 1488, 3, 'com_content.article.729', 'Стартовала сборка Mitsubishi Pajero Sport в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(797, 99, 1489, 1490, 3, 'com_content.article.730', 'BMW покажет серийный электроавтомобиль модели i3 29 июля', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(798, 99, 1491, 1492, 3, 'com_content.article.731', 'Внешний вид нового Mini рассекречен', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(799, 99, 1493, 1494, 3, 'com_content.article.732', 'Фотошпионы запечатлели новые Skoda Octavia RS', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(800, 99, 1495, 1496, 3, 'com_content.article.733', 'Google вполне может скоро и электрокары разрабатывать', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(801, 99, 1497, 1498, 3, 'com_content.article.734', 'Honda и GM совместно создадут водородный автомобиль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(802, 99, 1499, 1500, 3, 'com_content.article.735', 'Возрождается автомобильный бренд Minerva', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(803, 99, 1501, 1502, 3, 'com_content.article.736', 'Opel показал внедорожник Insignia', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(804, 99, 1503, 1504, 3, 'com_content.article.737', 'Результаты конкурса "Автомобиль для президента"', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(805, 99, 1505, 1506, 3, 'com_content.article.738', 'Land Rover создает версию Range Rover подлиннее', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(806, 99, 1507, 1508, 3, 'com_content.article.739', 'Новинка от Renault и Caterham за 40 тысяч евро', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(807, 99, 1509, 1510, 3, 'com_content.article.740', 'Стартовали авто продажи модели Lada Granta со 106-сильным силовым агрегатом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(808, 99, 1511, 1512, 3, 'com_content.article.741', 'Автомобиль Lada Kalina 1-го поколения стала дешевле на 30 тысяч рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(809, 99, 1513, 1514, 3, 'com_content.article.742', 'Opel осенью представит новое спорткупе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(810, 99, 1515, 1516, 3, 'com_content.article.743', 'Porsche Macan в авто продаже появится через год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(811, 99, 1517, 1518, 3, 'com_content.article.744', 'Закончилось производство BMW M3 в кузове купе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(812, 99, 1519, 1520, 3, 'com_content.article.745', 'Электрокар от VW получился несколько дороже конкурентов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(813, 99, 1521, 1522, 3, 'com_content.article.746', 'Обновленную модель Skoda Rapid оснастят новым мотором и ксеноновыми фарами', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(814, 99, 1523, 1524, 3, 'com_content.article.747', 'Автомобиль Tagaz Aquila дорабатывается в Тольятти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(815, 99, 1525, 1526, 3, 'com_content.article.748', 'Jaguar скоро представит новый концепткар', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(816, 99, 1527, 1528, 3, 'com_content.article.749', 'Новинка Subaru Legacy предстанет миру через год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(817, 99, 1529, 1530, 3, 'com_content.article.750', 'Теперь экономичный Volkswagen будет еще и необыкновенно быстрым', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(818, 99, 1531, 1532, 3, 'com_content.article.751', 'Ford Fiesta стал лучшим женским авто 2013 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(819, 99, 1533, 1534, 3, 'com_content.article.752', 'Автомобильный парк России располагает 37 млн. автомобилями', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(820, 99, 1535, 1536, 3, 'com_content.article.753', 'Серийник X4 от BMW покажут на Франкфуртском автосалоне', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(821, 99, 1537, 1538, 3, 'com_content.article.754', 'Suzuki модернизировала модель Swift', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(822, 99, 1539, 1540, 3, 'com_content.article.755', 'Автозавод в Горькове начал собирать автомобили марки Mercedes-Benz', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(823, 99, 1541, 1542, 3, 'com_content.article.756', 'Первый автомобиль возрожденного автобренда Datsun показан миру', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(824, 99, 1543, 1544, 3, 'com_content.article.757', 'Новый молодежный хэтчбек от Skoda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(825, 99, 1545, 1546, 3, 'com_content.article.758', 'Toyota рассказала о создании своей модульной платформы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(826, 99, 1547, 1548, 3, 'com_content.article.759', 'Новинка от Peugeot модель 308 получила цену', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(827, 99, 1549, 1550, 3, 'com_content.article.760', 'Hyundai скоро подарит миру компактный и недорогой кроссовер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(828, 99, 1551, 1552, 3, 'com_content.article.761', 'Новинку в кузове купе Lexus RC продемонстрируют в ноябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(829, 99, 1553, 1554, 3, 'com_content.article.762', 'Новинка Mercedes S 63 AMG оснащена двигателем V8', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(830, 99, 1555, 1556, 3, 'com_content.article.763', 'Автомобильный рынок Европы падает не первый месяц', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(831, 99, 1557, 1558, 3, 'com_content.article.764', 'Новинка Chevrolet Corvette летом 2014 года появится в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(832, 99, 1559, 1560, 3, 'com_content.article.765', 'Готовиться новое поколение Mazda2', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(833, 99, 1561, 1562, 3, 'com_content.article.766', 'Nissan Qashqai представят миру раньше, чем все ожидали', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(834, 99, 1563, 1564, 3, 'com_content.article.767', 'Новый Ford Mustang начинает 2014 модельный год с половинки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(835, 99, 1565, 1566, 3, 'com_content.article.768', 'BMW рассекретила подробности будущего купе версии M4', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(836, 99, 1567, 1568, 3, 'com_content.article.769', 'Volvo модель V40 Cross Country на дизельном движке оценена в 1 млн. рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(837, 99, 1569, 1570, 3, 'com_content.article.770', 'Mitsubishi превратит Lancer Evo в конкурента модели Nissan GT-R', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(838, 99, 1571, 1572, 3, 'com_content.article.771', 'В декабре 2013 года начинается сборка новой модели Porsche', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(839, 99, 1573, 1574, 3, 'com_content.article.772', 'Новое поколение Honda Fit', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(840, 99, 1575, 1576, 3, 'com_content.article.773', 'Немцы создали трековый спортивный автомобиль для улицы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(841, 99, 1577, 1578, 3, 'com_content.article.774', 'Mercedes-Benz класса Е будет оснащаться 9-ступенчатой АКПП', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(842, 99, 1579, 1580, 3, 'com_content.article.775', 'Seat производит серийный внедорожник и компактный кроссовер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(843, 99, 1581, 1582, 3, 'com_content.article.776', 'Доступные модели Jaguar можно будет купить через 1,5 месяца', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(844, 99, 1583, 1584, 3, 'com_content.article.777', 'Премьера марки Acura в России пройдет в сентябре 2013 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(845, 99, 1585, 1586, 3, 'com_content.article.778', 'Honda Civic в кузове универсал представят во Франкфурте', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(846, 99, 1587, 1588, 3, 'com_content.article.779', 'Премиум-седан FAW можно купить в России за 820000 рублей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(847, 99, 1589, 1590, 3, 'com_content.article.780', 'Обновленный Renault Kangoo – скоро авто продажи в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(848, 99, 1591, 1592, 3, 'com_content.article.781', 'Марка Ferrari готова "зарядить" модель 458 Italia', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(849, 99, 1593, 1594, 3, 'com_content.article.782', 'Компания Ford свои заводы из Англии перевозит в Турцию', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(850, 99, 1595, 1596, 3, 'com_content.article.783', 'Модель Touareg марки Volkswagen стал еще дешевле и роскошнее', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(851, 99, 1597, 1598, 3, 'com_content.article.784', 'На определенных зонах достигать 130 км/ч по скорости скоро станет законно', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(852, 99, 1599, 1600, 3, 'com_content.article.785', 'В России собрали супер-БТР-гибрид', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(853, 99, 1601, 1602, 3, 'com_content.article.786', 'Премьера новой Suzuki Grand Vitara состоится во Франкфурте', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(854, 99, 1603, 1604, 3, 'com_content.article.787', 'В течение 1,5 лет первый кроссовер от марки Alfa Romeo поступит на авторынки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(855, 99, 1605, 1606, 3, 'com_content.article.788', 'Новинка от  Chery в России появится в конце 2013 года', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(856, 99, 1607, 1608, 3, 'com_content.article.789', '1/3 часть иномарок, реализуемых в России, находятся в Москве', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(857, 99, 1609, 1610, 3, 'com_content.article.790', 'Citroen скоро дебютирует бюджетным концепт-каром', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(858, 99, 1611, 1612, 3, 'com_content.article.791', 'Mercedes планирует собирать легковые автомобили в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(859, 99, 1613, 1614, 3, 'com_content.article.792', 'Nissan представит миру новый "паркетник" уже 20 августа', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(860, 99, 1615, 1616, 3, 'com_content.article.793', 'Известный пикап от Ford - F-150 теперь газовый', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(861, 99, 1617, 1618, 3, 'com_content.article.794', 'На компанию Ford наложат штраф в 17 млн. долларов за поздний отзыв Escape', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(862, 99, 1619, 1620, 3, 'com_content.article.795', 'Mercedes привезет в Детройт флагман S600 V12, а в Лос-Анджелес S65 AMG', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(863, 99, 1621, 1622, 3, 'com_content.article.796', 'Заряженная версия Subaru BRZ STi появиться осенью 2013', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(864, 99, 1623, 1624, 3, 'com_content.article.797', 'В США представлен ездовой прототип модели Acura NSX', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(865, 99, 1625, 1626, 3, 'com_content.article.798', 'BMW начал серийное производство новинки X5', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(866, 99, 1627, 1628, 3, 'com_content.article.799', 'Серийным станет семиместный кроссовер Volkswagen', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(867, 99, 1629, 1630, 3, 'com_content.article.800', 'Audi Q7 станет электрокаром e-tron', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(868, 99, 1631, 1632, 3, 'com_content.article.801', 'Chevrolet сделал дешевле электрокар Volt на  5 тысяч долларов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(869, 99, 1633, 1634, 3, 'com_content.article.802', 'Тюнинг-бюро сделало из Audi R8 - Audi R8 GT-850 White Phoenix', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(870, 99, 1635, 1636, 3, 'com_content.article.803', 'Российские автолюбители самой востребованным подержанным авто сегмента В назвали Opel Corsa', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(871, 99, 1637, 1638, 3, 'com_content.article.804', 'Недорогой минивэн Dacia Lodgy возможно появится в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(872, 99, 1639, 1640, 3, 'com_content.article.805', 'Недорогой Maserati в Россию приедет в сентябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(873, 99, 1641, 1642, 3, 'com_content.article.806', 'Дорогим машинам - значительные штрафы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(874, 99, 1643, 1644, 3, 'com_content.article.807', 'Рецессия в экономике – спад автомобильных продаж', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(875, 99, 1645, 1646, 3, 'com_content.article.808', 'Встречайте - Honda Civic Tourer', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(876, 99, 1647, 1648, 3, 'com_content.article.809', 'Счастливые пробок не наблюдают', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(877, 99, 1649, 1650, 3, 'com_content.article.810', 'Renault Duster растет в цене', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(878, 99, 1651, 1652, 3, 'com_content.article.811', 'Концепт Infiniti Q30', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(879, 99, 1653, 1654, 3, 'com_content.article.812', 'В Петербурге вскоре появится сеть платных парковок', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(880, 99, 1655, 1656, 3, 'com_content.article.813', 'Зеленый цвет дает дорогу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(881, 99, 1657, 1658, 3, 'com_content.article.814', 'Nissan Terrano. Японский индиец', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(882, 99, 1659, 1660, 3, 'com_content.article.815', 'Стартовали российские продажи ŠKODA Superb и Superb Combi', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(883, 99, 1661, 1662, 3, 'com_content.article.816', 'В России введут обязательное чипирование автономеров', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(884, 99, 1663, 1664, 3, 'com_content.article.817', 'Петербург предоставит дорогим гостям перевозки VIP-класса', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(885, 99, 1665, 1666, 3, 'com_content.article.818', 'Петербургский завод Nissan Motor приступит к сборке рестайлинговой версии Teana', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(886, 99, 1667, 1668, 3, 'com_content.article.819', 'Опубликован рейтинг надежности подержанных автомобилей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(887, 99, 1669, 1670, 3, 'com_content.article.820', 'В нижегородском бизнес-инкубаторе "выведут" новый кроссовер', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(888, 99, 1671, 1672, 3, 'com_content.article.821', 'Французы представят в России обновленный Renault Kangoo', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(889, 99, 1673, 1674, 3, 'com_content.article.822', 'Новые программы автокредитования: доступность рождает спрос', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(890, 99, 1675, 1676, 3, 'com_content.article.823', 'Новинка Mazda 3 - технически подкована и умна', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(891, 99, 1677, 1678, 3, 'com_content.article.824', 'Nissan X-Trail. Теперь семиместный', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(892, 99, 1679, 1680, 3, 'com_content.article.825', 'Показатели спроса на Hyundai в России подросли', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(893, 99, 1681, 1682, 3, 'com_content.article.826', 'SUV Kia Soul нового поколения - путь из Америки в Европу', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(894, 99, 1683, 1684, 3, 'com_content.article.827', 'Тачка на прокачку. Nissan с рулем сзади!', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(895, 99, 1685, 1686, 3, 'com_content.article.828', 'Закономерность - Чем дороже машины, тем чаще их меняют', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(896, 99, 1687, 1688, 3, 'com_content.article.829', 'Свежие новости о BMW 7-Series', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(897, 99, 1689, 1690, 3, 'com_content.article.830', 'Французский дебютант Citroen Cactus встанет на поток', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(898, 99, 1691, 1692, 3, 'com_content.article.831', 'Российские дилеры ожидают новинку Opel Insignia', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(899, 99, 1693, 1694, 3, 'com_content.article.832', 'Кортеж – отечественное авто для чиновников', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(900, 99, 1695, 1696, 3, 'com_content.article.833', 'Lada Granta.  Наши в городе', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(901, 99, 1697, 1698, 3, 'com_content.article.834', 'Новый порядок регистрации авто. Похитители номеров лишатся наживы', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(902, 99, 1699, 1700, 3, 'com_content.article.835', 'BMW 4-Series Coupe. Старт продаж в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(903, 99, 1701, 1702, 3, 'com_content.article.836', 'На российский автопром ляжет бремя утилизационных сборов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(904, 99, 1703, 1704, 3, 'com_content.article.837', 'Женщинам в Саудовской Аравии могут разрешить сесть за руль', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(905, 99, 1705, 1706, 3, 'com_content.article.838', 'Экологический налог на авто', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(906, 99, 1707, 1708, 3, 'com_content.article.839', 'На что обращают внимание, покупая автомобили с пробегом', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(907, 99, 1709, 1710, 3, 'com_content.article.840', 'Индийские машины дорожают. Теперь 3000$', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(908, 99, 1711, 1712, 3, 'com_content.article.841', 'Покупка авто с пробегом. Опрос', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(909, 99, 1713, 1714, 3, 'com_content.article.842', 'ЗИЛ займется созданием новых парадных кабриолетов', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(910, 99, 1715, 1716, 3, 'com_content.article.843', '40 лет без водительских прав', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(911, 99, 1717, 1718, 3, 'com_content.article.844', 'Обновленный Subaru Outback. Старт продаж в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(912, 99, 1719, 1720, 3, 'com_content.article.845', 'Продажа ввозимых из Европы иномарок может оказаться нелегальной', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(913, 99, 1721, 1722, 3, 'com_content.article.846', 'Ford EcoSport. Купить авто через год', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(914, 99, 1723, 1724, 3, 'com_content.article.847', 'Renault Logan II. Бразильский француз скоро в России', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(915, 99, 1725, 1726, 3, 'com_content.article.848', 'Число кредитных автомобилей в России к 2014 году может вырасти', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(916, 99, 1727, 1728, 3, 'com_content.article.849', 'Автошоу в Токио готовится принять серийную новинку Honda Urban SUV', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(917, 99, 1729, 1730, 3, 'com_content.article.850', 'BMW отзывает около 200 тысяч машин', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(918, 99, 1731, 1732, 3, 'com_content.article.851', 'В России введены новые категории водительских прав', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(919, 99, 1733, 1734, 3, 'com_content.article.852', 'В Тольятти начата официальная сборка седанов Datsun on-DO', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(920, 99, 1735, 1736, 3, 'com_content.article.853', 'Огромный внедорожник Cadillac Escalade получит «горячую» модификацию', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(921, 99, 1737, 1738, 3, 'com_content.article.854', 'Опубликованы первые подробности об эксклюзивном седане Aston Martin Lagonda', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(922, 99, 1739, 1740, 3, 'com_content.article.855', 'Mazda выпустит дизель-гибридную силовую установку в 2016 году', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(923, 99, 1741, 1742, 3, 'com_content.article.856', 'Hyundai Sonata сделали 708-сильным', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(924, 99, 1743, 1744, 3, 'com_content.article.857', 'Эксперты: Автомобили становятся настоящим бедствием', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(925, 99, 1745, 1746, 3, 'com_content.article.858', 'В Российской Федерации началась продажа автомобилей Chery местной сборки', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(926, 99, 1747, 1748, 3, 'com_content.article.859', 'Автомобили в Приморском крае перейдут на газ', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(927, 99, 1749, 1750, 3, 'com_content.article.860', 'Российские власти хотят узаконить штраф за езду на «лысой резине»', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(928, 99, 1751, 1752, 3, 'com_content.article.861', 'Renault изготовит среднеразмерный пикап  Французский производитель автомобилей сможет изготовить авт', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(929, 99, 1753, 1754, 3, 'com_content.article.862', '«Народные» автомобилиToyota обучатся без помощи других тормозить к 2018 году.', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(930, 99, 1755, 1756, 3, 'com_content.article.863', 'Автомобили, в которых продумано всё вплоть до деталей', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(931, 99, 1757, 1758, 3, 'com_content.article.864', 'Бренд Maybach возвратится на рынок уже в ноябре', '');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(932, 18, 1848, 1849, 2, 'com_modules.module.93', 'Меню1', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(933, 18, 1850, 1851, 2, 'com_modules.module.94', 'Меню2', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(934, 18, 1852, 1853, 2, 'com_modules.module.95', 'Поиск', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(935, 18, 1854, 1855, 2, 'com_modules.module.96', 'Меню3', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(936, 18, 1856, 1857, 2, 'com_modules.module.97', 'Online оценка автомобиля', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(937, 18, 1858, 1859, 2, 'com_modules.module.98', 'Выкуп авто', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(938, 18, 1860, 1861, 2, 'com_modules.module.99', 'Авто в кредит', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(939, 18, 1862, 1863, 2, 'com_modules.module.100', 'Отзывы наших клиентов', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(940, 18, 1864, 1865, 2, 'com_modules.module.101', 'Новости', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(941, 18, 1866, 1867, 2, 'com_modules.module.102', 'Текст на главной', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(942, 18, 1868, 1869, 2, 'com_modules.module.103', 'Авто на главной', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(943, 18, 1870, 1871, 2, 'com_modules.module.104', 'Баннер-верх', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(944, 8, 1760, 1785, 2, 'com_content.category.14', 'Главная', '{"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(945, 944, 1761, 1762, 3, 'com_content.article.865', 'Выкуп авто. Срочный выкуп авто в СПб', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(946, 1, 1911, 1912, 1, 'com_jce', 'jce', '{}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(947, 944, 1763, 1764, 3, 'com_content.article.866', 'Авто в кредит СПб, авто с пробегом в кредит, автокредит без первоначального взноса, без КАСКО', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(948, 944, 1765, 1766, 3, 'com_content.article.867', 'Автострахование в Санкт-Петербурге - КАСКО, ОСАГО в СПб', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(949, 944, 1767, 1768, 3, 'com_content.article.868', 'Трейд ин в СПб. Обмен авто с пробегом на авто с пробегом', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(950, 944, 1769, 1770, 3, 'com_content.article.869', 'Продать авто в СПб. Продажа авто с пробегом в Cанкт Петербурге', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(951, 944, 1771, 1772, 3, 'com_content.article.870', 'Купить авто с пробегом в СПб', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(952, 944, 1773, 1774, 3, 'com_content.article.871', 'Автосалон в СПб с пробегом: салон бу авто Адмирал Моторс', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(953, 944, 1775, 1776, 3, 'com_content.article.872', 'Online оценка автомобиля', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(954, 944, 1777, 1778, 3, 'com_content.article.873', 'Отзывы наших клиентов', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(955, 944, 1779, 1780, 3, 'com_content.article.874', 'Контакты', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(956, 944, 1781, 1782, 3, 'com_content.article.875', 'Вопрос - ответ', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');
INSERT INTO admiralaru_new.dgb4n_assets(id, parent_id, lft, rgt, level, name, title, rules) VALUES
(957, 944, 1783, 1784, 3, 'com_content.article.876', 'Услуги', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');