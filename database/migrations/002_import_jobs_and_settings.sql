CREATE TABLE import_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_url TEXT NOT NULL,
    status VARCHAR(32) NOT NULL,
    error_message TEXT NULL,
    video_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    started_at DATETIME NULL,
    completed_at DATETIME NULL,
    CONSTRAINT fk_import_video FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE SET NULL,
    INDEX idx_import_status (status)
) ENGINE=InnoDB;

INSERT INTO settings (`key`, `value`) VALUES
('ffmpeg_path', '/usr/bin/ffmpeg'), ('ytdlp_path', '/usr/local/bin/yt-dlp'),
('default_resolution', '1280x720'), ('default_bitrate', '2500k'),
('auto_restart', '1'), ('timezone', 'UTC'), ('theme', 'light')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
