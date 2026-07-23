<?php declare(strict_types=1);
namespace App\Services;
final class MediaProbeService {
    public function playlistHasAudio(array $videos): bool { foreach($videos as $video)if($this->hasAudio(config('settings.upload_directory').'/'.$video['filename']))return true;return false; }
    public function hasAudio(string $file): bool { $binary=config('ffmpeg.ffprobe_path');if(!is_file($binary)||!is_executable($binary))throw new \RuntimeException('ffprobe executable is unavailable.');$command=escapeshellarg($binary).' -v error -select_streams a -show_entries stream=index -of csv=p=0 '.escapeshellarg($file);$output=shell_exec($command);return trim((string)$output)!==''; }
    public function version(): string { $output=shell_exec(escapeshellarg(config('ffmpeg.ffprobe_path')).' -version 2>&1');return strtok((string)$output,"\n")?:'Unavailable'; }
}
