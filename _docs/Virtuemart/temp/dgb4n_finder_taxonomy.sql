--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.2.280.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 20.11.2014 10:01:36
-- Версия сервера: 5.1.70-log
-- Версия клиента: 4.1
--


SET NAMES 'utf8';

INSERT INTO admiralaru_new.dgb4n_finder_taxonomy(id, parent_id, title, state, access, ordering) VALUES
(1, 0, 'ROOT', 0, 0, 0);
INSERT INTO admiralaru_new.dgb4n_finder_taxonomy(id, parent_id, title, state, access, ordering) VALUES
(2, 1, 'Type', 1, 1, 0);
INSERT INTO admiralaru_new.dgb4n_finder_taxonomy(id, parent_id, title, state, access, ordering) VALUES
(10, 2, 'Авто', 1, 1, 0);
INSERT INTO admiralaru_new.dgb4n_finder_taxonomy(id, parent_id, title, state, access, ordering) VALUES
(11, 1, 'Category', 1, 1, 0);
INSERT INTO admiralaru_new.dgb4n_finder_taxonomy(id, parent_id, title, state, access, ordering) VALUES
(12, 11, 'Автомобили', 1, 1, 0);