# YouTube Broadcaster

A lightweight PHP 8.3/MySQL dashboard and independent FFmpeg worker for one looping YouTube Live broadcast.

## Install on Ubuntu 24.04

1. Install `php8.3-fpm php8.3-mysql php8.3-cli composer mysql-server ffmpeg yt-dlp`.
2. Copy `.env.example` to `.env`, set a long `APP_KEY`, then run `composer install --no-dev --optimize-autoloader`.
3. Create the MySQL database/user and apply all schema changes with `php bin/migrate`.
4. Create writable `uploads`, `logs`, and `cache` directories for `www-data` (not web accessible), then run `php bin/create-admin admin@example.com 'long-password'`.
5. Install `deploy/nginx.conf`, validate Nginx, copy the worker and import service/timer units, and enable them with `systemctl enable --now youtube-broadcaster-worker.timer youtube-broadcaster-import.timer`.

The stream timer invokes a worker every five seconds. FFmpeg remains owned by that worker after a stream starts, keeping PHP-FPM and the dashboard independent. The import timer independently processes one queued yt-dlp import every ten seconds.

## Operational notes

* RTMP keys are stored in MySQL; protect database backups and restrict DB access.
* Videos are validated and kept outside `public/`.
* The worker log is `logs/stream-{id}.log`, and application events are JSON Lines in `logs/app-YYYY-MM-DD.log`.
* Use the server firewall and HTTPS proxy/TLS configuration before exposing the dashboard.

## Verification Required

On the Ubuntu host, run `composer install --no-dev --optimize-autoloader`, `php bin/migrate`, and `php bin/create-admin`. Verify upload, yt-dlp import, start/stop/restart, scheduled duration, recovery after killing FFmpeg, and systemd timer persistence using a private YouTube stream before production use.
