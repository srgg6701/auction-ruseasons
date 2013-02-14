--
-- Описание для таблицы auc13_menu
--
CREATE TABLE IF NOT EXISTS auc13_menu (
  id int(11) NOT NULL AUTO_INCREMENT,
  menutype varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  title varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  note varchar(255) NOT NULL DEFAULT '',
  path varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  link varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  type varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  published tinyint(4) NOT NULL DEFAULT 0 COMMENT 'The published state of the menu link.',
  parent_id int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'The parent menu item in the menu tree.',
  level int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The relative level in the tree.',
  component_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to #__extensions.id',
  ordering int(11) NOT NULL DEFAULT 0 COMMENT 'The relative ordering of the menu item in the tree.',
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to #__users.id',
  checked_out_time timestamp DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  browserNav tinyint(4) NOT NULL DEFAULT 0 COMMENT 'The click behaviour of the link.',
  access int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The access level required to view the menu item.',
  img varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  template_style_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  params text NOT NULL COMMENT 'JSON encoded data for the menu item.',
  lft int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  rgt int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  home tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indicates if this menu item is the home or default page.',
  language char(7) NOT NULL DEFAULT '',
  client_id tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  INDEX idx_alias (alias),
  UNIQUE INDEX idx_client_id_parent_id_alias_language (client_id, parent_id, alias, language),
  INDEX idx_componentid (component_id, menutype, published, access),
  INDEX idx_language (language),
  INDEX idx_left_right (lft, rgt),
  INDEX idx_menutype (menutype),
  INDEX idx_path (path (333))
)
ENGINE = MYISAM
AUTO_INCREMENT = 165
AVG_ROW_LENGTH = 358
character SET utf8
COLLATE utf8_general_ci;