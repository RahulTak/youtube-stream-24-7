<?php declare(strict_types=1);
namespace App\Streaming;

use App\Constants\StreamStatus;
use App\Repositories\{ChannelRepository, PlaylistRepository, StreamRepository};
use App\Services\LogService;
use App\Services\SettingsService;

final class StreamManager
{
    public function __construct(private StreamRepository $streams, private ChannelRepository $channels, private PlaylistRepository $playlists, private SettingsService $settings, private LogService $log) {}

    public function runPending(): void
    {
        $stream = $this->streams->active();
        if (!$stream || $stream['status'] !== StreamStatus::STARTING || ($stream['scheduled_start'] && strtotime($stream['scheduled_start']) > time())) return;
        $this->run($stream);
    }

    private function run(array $stream): void
    {
        $channel = $this->channels->find((int) $stream['channel_id']);
        $videos = $this->playlists->videos((int) $stream['playlist_id']);
        $playlistFile = tempnam(sys_get_temp_dir(), 'broadcast-');
        file_put_contents($playlistFile, implode('', array_map(fn(array $v): string => "file '".str_replace("'", "'\\''", config('settings.upload_directory').'/'.$v['filename'])."'\n", $videos)));
        $logFile = config('settings.log_directory').'/stream-'.$stream['id'].'.log';
        $command = $this->command($channel, $playlistFile);
        $attempt = 0;
        do {
            $this->streams->update((int) $stream['id'], ['status' => $attempt ? StreamStatus::RESTARTING : StreamStatus::RUNNING, 'started_at' => gmdate('Y-m-d H:i:s'), 'restart_count' => $attempt]);
            $process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['file', $logFile, 'a'], 2 => ['file', $logFile, 'a']], $pipes);
            if (!is_resource($process)) throw new \RuntimeException('Could not start FFmpeg.');
            if (isset($pipes[0]) && is_resource($pipes[0])) fclose($pipes[0]);
            $processState=proc_get_status($process);
            $this->streams->update((int)$stream['id'], ['pid'=>(int)$processState['pid']]);
            $stopped = false;
            while (proc_get_status($process)['running']) {
                sleep((int) config('stream.poll_seconds'));
                $fresh = $this->streams->find((int) $stream['id']);
                if (($fresh['stop_requested'] ?? false) || ($fresh['scheduled_end'] && strtotime($fresh['scheduled_end']) <= time())) {
                    $stopped = true;
                    proc_terminate($process, 15);
                    break;
                }
            }
            $exitCode = proc_close($process);
            if ($stopped) {
                $this->streams->update((int) $stream['id'], ['status' => StreamStatus::STOPPED, 'stopped_at' => gmdate('Y-m-d H:i:s'), 'pid' => null]);
                break;
            }
            $attempt++;
            $this->log->error('FFmpeg exited', ['stream_id' => $stream['id'], 'code' => $exitCode, 'attempt' => $attempt]);
            if (($this->settings->all()['auto_restart'] ?? '1') !== '1' || $attempt > (int) config('stream.max_restarts')) {
                $this->streams->update((int) $stream['id'], ['status' => StreamStatus::FAILED, 'pid' => null]);
                break;
            }
            sleep((int) config('stream.restart_delay_seconds'));
        } while (true);
        unlink($playlistFile);
    }

    private function command(array $channel, string $playlistFile): string
    {
        $settings=$this->settings->all();
        return escapeshellarg($settings['ffmpeg_path']).' -hide_banner -loglevel '.escapeshellarg(config('ffmpeg.loglevel')).' -re -stream_loop -1 -f concat -safe 0 -i '.escapeshellarg($playlistFile).' -c:v libx264 -preset '.escapeshellarg(config('ffmpeg.preset')).' -b:v '.escapeshellarg($settings['default_bitrate']).' -c:a aac -b:a '.escapeshellarg(config('ffmpeg.audio_bitrate')).' -f flv '.escapeshellarg($channel['rtmp_url'].'/'.$channel['stream_key']);
    }
}
