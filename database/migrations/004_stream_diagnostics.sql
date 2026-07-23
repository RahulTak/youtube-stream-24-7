ALTER TABLE streams ADD COLUMN last_error TEXT NULL AFTER restart_requested;
ALTER TABLE streams ADD COLUMN last_restart_at DATETIME NULL AFTER restart_count;
