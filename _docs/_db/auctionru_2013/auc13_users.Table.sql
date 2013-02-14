--
-- Описание для таблицы auc13_users
--
CREATE TABLE IF NOT EXISTS auc13_users (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  middlename varchar(150) NOT NULL COMMENT 'Отчество',
  lastname varchar(200) NOT NULL COMMENT 'Фамилия',
  username varchar(150) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  password varchar(100) NOT NULL DEFAULT '',
  usertype varchar(25) NOT NULL DEFAULT '',
  block tinyint(4) NOT NULL DEFAULT 0,
  sendEmail tinyint(4) DEFAULT 0,
  registerDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  lastvisitDate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  company_name varchar(60) NOT NULL COMMENT 'Орг-я',
  country_id int(11) NOT NULL COMMENT 'id страны',
  zip varchar(12) NOT NULL COMMENT 'zip',
  city varchar(60) NOT NULL COMMENT 'Город',
  street varchar(60) NOT NULL COMMENT 'Улица',
  house_number text NOT NULL COMMENT '№ дома',
  corpus_number text NOT NULL COMMENT '№ корпуса',
  flat_office_number text NOT NULL COMMENT '№ квартиры или офиса',
  phone_number text NOT NULL COMMENT '№ тел.',
  phone2_number text NOT NULL COMMENT '№ доп. телефона',
  activation varchar(100) NOT NULL DEFAULT '',
  params text NOT NULL,
  lastResetTime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of last password reset',
  resetCount int(11) NOT NULL DEFAULT 0 COMMENT 'Count of password resets since lastResetTime',
  PRIMARY KEY (id),
  INDEX email (email),
  INDEX idx_block (block),
  INDEX idx_name (name),
  INDEX username (username),
  INDEX usertype (usertype)
)
ENGINE = MYISAM
AUTO_INCREMENT = 366
AVG_ROW_LENGTH = 252
character SET utf8
COLLATE utf8_general_ci;