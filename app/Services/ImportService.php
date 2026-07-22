<?php declare(strict_types=1);
namespace App\Services;
final class ImportService {
    public function __construct(private LogService $log) {}
    public function queue(string $url): void {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host || !in_array(strtolower($host), config('youtube.allowed_hosts'), true)) throw new \InvalidArgumentException('Only YouTube URLs are allowed.');
        $dir = config('settings.upload_directory');
        $command = escapeshellarg(config('youtube.ytdlp_path')).' --no-playlist --write-thumbnail --output '.escapeshellarg($dir.'/%(id)s.%(ext)s').' '.escapeshellarg($url);
        $this->log->info('YouTube import queued', ['url' => $url]);
        exec($command.' > /dev/null 2>&1 &');
    }
}
