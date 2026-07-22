ALTER TABLE streams ADD COLUMN restart_requested BOOLEAN NOT NULL DEFAULT FALSE AFTER stop_requested;
