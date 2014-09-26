--
-- Описание для таблицы auc13_banner_tracks
--
CREATE TABLE IF NOT EXISTS auc13_banner_tracks (
  track_date datetime NOT NULL,
  track_type int(10) UNSIGNED NOT NULL,
  banner_id int(10) UNSIGNED NOT NULL,
  count int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (track_date, track_type, banner_id),
  INDEX idx_banner_id (banner_id),
  INDEX idx_track_date (track_date),
  INDEX idx_track_type (track_type)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;